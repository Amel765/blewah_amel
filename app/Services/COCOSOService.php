<?php

namespace App\Services;

use App\Models\Alternative;
use App\Models\Criteria;
use App\Models\Score;
use App\Models\SubmissionScore;

class COCOSOService
{
    public function calculateRanking($weights, $criteria = null, $submissionId = null)
    {
        if ($criteria === null) {
            $criteria = Criteria::where('submission_id', $submissionId)->orderBy('id')->get();
        }

        $alternatives = Alternative::where('submission_id', $submissionId)->get();

        if ($criteria->isEmpty() || $alternatives->isEmpty()) {
            return [];
        }

        if ($submissionId) {
            $decisionMatrix = $this->getDecisionMatrixFromSubmission($submissionId, $criteria, $alternatives);
        } else {
            $decisionMatrix = $this->getDecisionMatrix($criteria, $alternatives);
        }

        $normalizedMatrix = $this->normalizeMatrix($decisionMatrix, $criteria);

        // Si (Weighted Sum) and Pi (Weighted Product)
        $si = [];
        $pi = [];

        foreach ($alternatives as $i => $alt) {
            $siSum = 0;
            $piProduct = 1.0;

            foreach ($criteria as $j => $crit) {
                $val = $normalizedMatrix[$i][$j];

                // Get weight
                $weight = 0;
                if (isset($weights[$crit->id]) && isset($weights[$crit->id]['weight'])) {
                    $weight = (float) $weights[$crit->id]['weight'];
                } elseif (is_array($weights) && isset($weights[$j])) {
                    $weight = (float) ($weights[$j]['weight'] ?? $weights[$j] ?? 0);
                }

                // Si = Σ (w_j * r_ij)
                $siSum += $val * $weight;

                // Pi = Π (r_ij ^ w_j)
                $piProduct *= pow(($val == 0 ? 0.0001 : $val), $weight);
            }
            $si[$i] = $siSum;
            $pi[$i] = $piProduct;
        }

        $minSi = min($si);
        $maxSi = max($si);
        $minPi = min($pi);
        $maxPi = max($pi);
        $sumSi = array_sum($si);
        $sumPi = array_sum($pi);

        $results = [];
        foreach ($alternatives as $i => $alt) {
            // K_a: (Si-minSi)/(maxSi-minSi) + (Pi-minPi)/(maxPi-minPi)
            $siNorm = ($maxSi != $minSi) ? ($si[$i] - $minSi) / ($maxSi - $minSi) : 1;
            $piNorm = ($maxPi != $minPi) ? ($pi[$i] - $minPi) / ($maxPi - $minPi) : 1;
            $ka = $siNorm + $piNorm;

            // K_b: (Si/minSi) + (Pi/minPi)
            //$kb = ($minSi > 0 ? $si[$i] / $minSi : 0) + ($minPi > 0 ? $pi[$i] / $minPi : 0);
            // K_b: Relatif terhadap nilai minimum gabungan (Rumus Standar CoCoSo)
$kb = ($minSi + $minPi != 0) ? ($si[$i] + $pi[$i]) / ($minSi + $minPi) : 0;

            // K_c: Si/ΣSi + Pi/ΣPi
            $kc = ($sumSi > 0 ? $si[$i] / $sumSi : 0) + ($sumPi > 0 ? $pi[$i] / $sumPi : 0);

            // Qi = (ka * kb * kc)^(1/3) + (ka + kb + kc) / 3
            $product = max($ka * $kb * $kc, 0.0001);
            $qi = pow($product, 1 / 3) + ($ka + $kb + $kc) / 3;

            $results[] = [
                'alternative' => $alt,
                'si' => $si[$i],
                'pi' => $pi[$i],
                'ka' => $ka,
                'kb' => $kb,
                'kc' => $kc,
                'qi' => $qi,
            ];
        }

        usort($results, fn ($a, $b) => $b['qi'] <=> $a['qi']);

        return $results;
    }

    private function getDecisionMatrixFromSubmission($submissionId, $criteria, $alternatives)
    {
        $matrix = [];
        foreach ($alternatives as $i => $alt) {
            foreach ($criteria as $j => $crit) {
                $score = SubmissionScore::where('submission_id', $submissionId)
                    ->where('alternative_id', $alt->id)
                    ->where('criteria_id', $crit->id)
                    ->first();
                $matrix[$i][$j] = $score ? (float) $score->value : 0;
            }
        }

        return $matrix;
    }

    private function getDecisionMatrix($criteria, $alternatives)
    {
        $matrix = [];
        foreach ($alternatives as $i => $alt) {
            foreach ($criteria as $j => $crit) {
                $score = Score::where('alternative_id', $alt->id)
                    ->where('criteria_id', $crit->id)
                    ->first();
                $matrix[$i][$j] = $score ? (float) $score->value : 0;
            }
        }

        return $matrix;
    }

    private function normalizeMatrix($matrix, $criteria)
    {
        $normalized = [];
        $colMax = [];
        $colMin = [];

        $m = count($matrix);
        $n = count($criteria);

        for ($j = 0; $j < $n; $j++) {
            $col = array_column($matrix, $j);
            $colMax[$j] = max($col);
            $colMin[$j] = min($col);
        }

        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $val = $matrix[$i][$j];
                $max = $colMax[$j];
                $min = $colMin[$j];
                $diff = $max - $min;

                if ($diff == 0) {
                    $normalized[$i][$j] = 1;
                } elseif ($criteria[$j]->type === 'benefit') {
                    $normalized[$i][$j] = ($val - $min) / $diff;
                } else {
                    $normalized[$i][$j] = ($max - $val) / $diff;
                }
            }
        }

        return $normalized;
    }
}
