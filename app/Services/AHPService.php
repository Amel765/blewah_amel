<?php

namespace App\Services;

use App\Models\Comparison;
use App\Models\Criteria;

class AHPService
{
    public function calculateWeights()
    {
        $criteria = Criteria::orderBy('id')->get();
        $n = $criteria->count();
        if ($n === 0) {
            return [];
        }

        $matrix = $this->getPairwiseMatrix($criteria);
        $normalizedMatrix = $this->normalizeMatrix($matrix, $n);
        $weights = $this->calculateEigenvector($normalizedMatrix, $n);

        $cr = $this->calculateConsistencyRatio($matrix, $weights, $n);

        // Save weights to criteria table
        $criteriaArray = $criteria->toArray();
        $weightedCriteria = [];
        foreach ($criteria as $index => $crit) {
            $weight = $weights[$index] ?? 0;
            $crit->update(['weight' => $weight]);
            $weightedCriteria[$crit->id] = [
                'criteria_id' => $crit->id,
                'name' => $crit->name,
                'weight' => $weight,
            ];
        }

        return [
            'weightsIndexed' => $weights, // For view display (indexed by position)
            'weights' => $weightedCriteria, // For COCOSO (indexed by criteria_id)
            'cr' => $cr,
            'matrix' => $matrix,
            'normalizedMatrix' => $normalizedMatrix,
            'criteria' => $criteria,
        ];
    }

    public function getWeightsFromCriteria()
    {
        $criteria = Criteria::orderBy('id')->get();
        $weights = [];

        foreach ($criteria as $crit) {
            $weights[$crit->id] = [
                'criteria_id' => $crit->id,
                'name' => $crit->name,
                'weight' => (float) $crit->weight,
                'type' => $crit->type,
            ];
        }

        return $weights;
    }

    private function getPairwiseMatrix($criteria)
    {
        $matrix = [];
        $criteriaIds = $criteria->pluck('id')->toArray();
        $n = count($criteriaIds);

        for ($i = 0; $i < $n; $i++) {
            $id1 = $criteriaIds[$i];
            for ($j = 0; $j < $n; $j++) {
                $id2 = $criteriaIds[$j];

                if ($id1 == $id2) {
                    $matrix[$i][$j] = 1.0;
                } else {
                    $comparison = Comparison::where('criteria_id_1', $id1)
                        ->where('criteria_id_2', $id2)
                        ->first();

                    if ($comparison) {
                        $matrix[$i][$j] = (float) $comparison->value;
                    } else {
                        // Check for reverse comparison
                        $reverse = Comparison::where('criteria_id_1', $id2)
                            ->where('criteria_id_2', $id1)
                            ->first();

                        if ($reverse && $reverse->value != 0) {
                            $matrix[$i][$j] = 1.0 / (float) $reverse->value;
                        } else {
                            $matrix[$i][$j] = 1.0; // Default if not found
                        }
                    }
                }
            }
        }

        return $matrix;
    }

    private function normalizeMatrix($matrix, $n)
    {
        $columnSums = array_fill(0, $n, 0);
        for ($j = 0; $j < $n; $j++) {
            for ($i = 0; $i < $n; $i++) {
                $columnSums[$j] += $matrix[$i][$j];
            }
        }

        $normalizedMatrix = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $normalizedMatrix[$i][$j] = $columnSums[$j] != 0 ? $matrix[$i][$j] / $columnSums[$j] : 0;
            }
        }

        return $normalizedMatrix;
    }

    private function calculateEigenvector($normalizedMatrix, $n)
    {
        $weights = [];
        for ($i = 0; $i < $n; $i++) {
            $weights[$i] = array_sum($normalizedMatrix[$i]) / $n;
        }

        return $weights;
    }

    private function calculateConsistencyRatio($matrix, $weights, $n)
    {
        if ($n <= 2) {
            return 0;
        }

        // Calculate Ax
        $ax = [];
        for ($i = 0; $i < $n; $i++) {
            $ax[$i] = 0;
            for ($j = 0; $j < $n; $j++) {
                $ax[$i] += $matrix[$i][$j] * $weights[$j];
            }
        }

        // Calculate lambda max
        $lambdaMax = 0;
        for ($i = 0; $i < $n; $i++) {
            if ($weights[$i] != 0) {
                $lambdaMax += $ax[$i] / $weights[$i];
            }
        }
        $lambdaMax /= $n;

        // CI
        $ci = ($lambdaMax - $n) / ($n - 1);

        // RI (Random Index) values
        $ri = [0, 0, 0, 0.58, 0.90, 1.12, 1.24, 1.32, 1.41, 1.45];
        $riValue = $ri[$n] ?? 1.45;

        return $riValue != 0 ? $ci / $riValue : 0;
    }
}
