<?php

namespace App\Services;

use App\Models\Alternative;
use App\Models\Criteria;
use App\Models\Score;

class COCOSOService
{
    public function calculateRanking($weights, $criteria = null)
    {
        if ($criteria === null) {
            $criteria = Criteria::orderBy('id')->get();
        }

        $alternatives = Alternative::all();

        if ($criteria->isEmpty() || $alternatives->isEmpty()) {
            return [];
        }

        $decisionMatrix = $this->getDecisionMatrix($criteria, $alternatives);
        $normalizedMatrix = $this->normalizeMatrix($decisionMatrix, $criteria);

        // Si (Weighted Sum) and Pi (Weighted Product)
        $si = [];
        $pi = [];

        foreach ($alternatives as $i => $alt) {
            $siSum = 0;
            $piSum = 1; // Start with 1 for multiplication

            foreach ($criteria as $j => $crit) {
                $val = $normalizedMatrix[$i][$j];

                // Get weight by criteria_id - weights format: [criteria_id => ['weight'=>x, 'type'=>y]]
                $weight = 0;
                if (isset($weights[$crit->id]) && isset($weights[$crit->id]['weight'])) {
                    $weight = (float) $weights[$crit->id]['weight'];
                } elseif (is_array($weights) && isset($weights[$j])) {
                    // Fallback to indexed array
                    $weight = (float) ($weights[$j]['weight'] ?? $weights[$j] ?? 0);
                }

                // Si = Weighted Sum (Σ w_j * r_ij)
                $siSum += $val * $weight;

                // Pi = Weighted Product (∏ r_ij ^ w_j)
                // Using multiplication, not power addition
                if ($val > 0 && $weight > 0) {
                    $piSum *= pow($val, $weight);
                }
            }
            $si[$i] = $siSum;
            $pi[$i] = $piSum;
        }

        $minSi = min($si);
        $maxSi = max($si);
        $minPi = min($pi);
        $maxPi = max($pi);

        $totalSi = array_sum($si);
        $totalPi = array_sum($pi);

        $results = [];
        foreach ($alternatives as $i => $alt) {
            // k_a - Max strategy (sum of normalized scores)
            // k_a = (Si - min(Si)) / (max(Si) - min(Si)) + (Pi - min(Pi)) / (max(Pi) - min(Pi))
            $siNorm = ($maxSi != $minSi) ? ($si[$i] - $minSi) / ($maxSi - $minSi) : 1;
            $piNorm = ($maxPi != $minPi) ? ($pi[$i] - $minPi) / ($maxPi - $minPi) : 1;
            $ka = $siNorm + $piNorm;

            // k_b - Max relative advantage (compared to min)
            $kb = ($minSi > 0) ? $si[$i] / $minSi : 0;

            // k_c - Average relative advantage (sum of ratios)
            $siRatio = ($totalSi > 0) ? $si[$i] / $totalSi : 0;
            $piRatio = ($totalPi > 0) ? $pi[$i] / $totalPi : 0;
            $kc = $siRatio + $piRatio;

            // Qi - Combined compromise score (COCOSO standard formula)
            // Qi = (k_a * k_b * k_c)^(1/3) + (k_a + k_b + k_c) / 3
            $qi = pow($ka * $kb * $kc, 1 / 3) + ($ka + $kb + $kc) / 3;

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

        // Sort by Qi descending
        usort($results, fn ($a, $b) => $b['qi'] <=> $a['qi']);

        return $results;
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

        $m = count($matrix); // alternatives
        $n = count($criteria); // criteria

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
                    // All values are the same - use 1 as neutral value
                    $normalized[$i][$j] = 1;
                } elseif ($criteria[$j]->type === 'benefit') {
                    // Higher is better: (x - min) / (max - min)
                    $normalized[$i][$j] = ($val - $min) / $diff;
                } else {
                    // Lower is better: (max - x) / (max - min)
                    $normalized[$i][$j] = ($max - $val) / $diff;
                }
            }
        }

        return $normalized;
    }
}
