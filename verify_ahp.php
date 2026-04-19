<?php
$matrix = [
    [1.0, 0.5, 3.0, 2.0, 4.0, 5.0],
    [2.0, 1.0, 4.0, 3.0, 5.0, 6.0],
    [0.333, 0.25, 1.0, 0.5, 2.0, 3.0],
    [0.5, 0.333, 2.0, 1.0, 3.0, 4.0],
    [0.25, 0.2, 0.5, 0.333, 1.0, 2.0],
    [0.2, 0.167, 0.333, 0.25, 0.5, 1.0],
];

$n = count($matrix);
$colSums = array_fill(0, $n, 0);
for ($j = 0; $j < $n; $j++) {
    for ($i = 0; $i < $n; $i++) {
        $colSums[$j] += $matrix[$i][$j];
    }
}

echo "Column Sums: " . implode(", ", $colSums) . "\n";

$normalized = [];
for ($i = 0; $i < $n; $i++) {
    for ($j = 0; $j < $n; $j++) {
        $normalized[$i][$j] = $matrix[$i][$j] / $colSums[$j];
    }
}

$weights = [];
for ($i = 0; $i < $n; $i++) {
    $weights[$i] = array_sum($normalized[$i]) / $n;
}

echo "Weights: " . implode(", ", $weights) . "\n";
