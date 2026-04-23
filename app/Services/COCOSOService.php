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

        // Si (Weighted Sum) and Pi (Weighted Product Sum)
        $si = [];
        $pi = [];

        foreach ($alternatives as $i => $alt) {
            $siSum = 0;
            $piSum = 0; // Sum of (r_ij ^ w_j)

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

                // Pi = Σ (r_ij ^ w_j)   — sum of powers, not product
                // Use epsilon for zero to avoid pow(0, w)=0
                $piSum += pow(($val == 0 ? 0 : $val), $weight);
            }
            $si[$i] = $siSum;
            $pi[$i] = $piSum;
        }

        $minSi = min($si);
        $maxSi = max($si);
        $minPi = min($pi);
        $maxPi = max($pi);

        $results = [];
        foreach ($alternatives as $i => $alt) {
            // K_a: Min-Max normalized compromise
            //$siNorm = ($maxSi != $minSi) ? ($si[$i] - $minSi) / ($maxSi - $minSi) : 1;
            //$piNorm = ($maxPi != $minPi) ? ($pi[$i] - $minPi) / ($maxPi - $minPi) : 1;
            $ka = ($si[$i] + $pi[$i]) / (array_sum($si) + array_sum($pi));

            // K_b: Maximalist strategy (relative to minimum)
            // Standard: S_i / min_S ; Extended variant: also add P_i / min_P
            //$kb = ($minSi > 0 ? $si[$i] / $minSi : 0) + ($minPi > 0 ? $pi[$i] / $minPi : 0);
            $kb = ($minSi + $minPi != 0) ? ($si[$i] + $pi[$i]) / ($minSi + $minPi) : 0;
            // K_c: Balance strategy (lambda-weighted)
            $lambda = 0.5;
            $kc_num = $lambda * $si[$i] + (1 - $lambda) * $pi[$i];
            $kc_den = $lambda * $maxSi + (1 - $lambda) * $maxPi;
            $kc = ($kc_den != 0) ? $kc_num / $kc_den : 0;

            // Qi = (K_a * K_b * K_c)^(1/3) + (K_a + K_b + K_c) / 3
            $product = $ka * $kb * $kc;
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
