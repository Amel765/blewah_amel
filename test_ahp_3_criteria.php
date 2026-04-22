<?php

/**
 * Test script for AHP autoProcess with 3 criteria
 * Run: php test_ahp_3_criteria.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Criteria;
use App\Models\Submission;
use App\Models\SubmissionComparison;
use App\Models\SubmissionScore;
use App\Services\AHPService;

echo "=== AHP Test: 3 Criteria - AutoProcess Verification ===\n\n";

// Create test submission with 3 criteria
$submission = Submission::create([
    'user_id' => 1,
    'title' => 'Test 3 Criteria',
    'description' => 'Testing with 3 criteria for proper RI=0.58',
    'status' => 'pending',
]);

// Create 3 criteria
$c1 = $submission->criteria()->create(['name' => 'Harga', 'type' => 'benefit']);
$c2 = $submission->criteria()->create(['name' => 'Kualitas', 'type' => 'benefit']);
$c3 = $submission->criteria()->create(['name' => 'Layanan', 'type' => 'benefit']);

// Create 3 alternatives
$alt1 = $submission->alternatives()->create(['name' => 'Produk A']);
$alt2 = $submission->alternatives()->create(['name' => 'Produk B']);
$alt3 = $submission->alternatives()->create(['name' => 'Produk C']);

// Use consistent pairwise comparisons (Saaty scale)
// Matrix (3x3) with moderate consistency
$comparisons = [
    [$c1->id, $c2->id, 3.0],    // Harga > Kualitas (sedikit lebih penting)
    [$c1->id, $c3->id, 5.0],    // Harga > Layanan (lebih penting)
    [$c2->id, $c3->id, 3.0],    // Kualitas > Layanan (sedikit lebih penting)
];

foreach ($comparisons as [$id1, $id2, $val]) {
    SubmissionComparison::create([
        'submission_id' => $submission->id,
        'criteria_id_1' => $id1,
        'criteria_id_2' => $id2,
        'value' => $val,
    ]);
    SubmissionComparison::create([
        'submission_id' => $submission->id,
        'criteria_id_1' => $id2,
        'criteria_id_2' => $id1,
        'value' => 1 / $val,
    ]);
}

// Add scores (0-100)
$scores = [
    [$alt1->id, $c1->id, 80], [$alt1->id, $c2->id, 90], [$alt1->id, $c3->id, 85],
    [$alt2->id, $c1->id, 90], [$alt2->id, $c2->id, 80], [$alt2->id, $c3->id, 75],
    [$alt3->id, $c1->id, 70], [$alt3->id, $c2->id, 85], [$alt3->id, $c3->id, 95],
];
foreach ($scores as [$alt, $crit, $val]) {
    SubmissionScore::create([
        'submission_id' => $submission->id,
        'alternative_id' => $alt,
        'criteria_id' => $crit,
        'value' => $val,
    ]);
}

echo "Test data created (ID: {$submission->id})\n";
$n = 3;
echo "Number of criteria (n): $n\n";

// Calculate with AHPService
$ahpService = new AHPService;
$results = $ahpService->calculateWeights($submission->id);

echo "\n--- AHPService Results ---\n";
echo 'CR (Consistency Ratio): '.($results['cr'] ?? 'N/A')."\n";
echo 'Weights: ';
foreach ($results['weightsIndexed'] as $w) {
    echo number_format($w, 4).' ';
}
echo "\n";

// Simulate autoProcess RI
$ri = [
    1 => 0.00, 2 => 0.00, 3 => 0.58, 4 => 0.90, 5 => 1.12,
    6 => 1.24, 7 => 1.32, 8 => 1.41, 9 => 1.45, 10 => 1.49,
];
$riValue = $ri[$n] ?? 1.49;
$cr = $results['cr'] ?? 0;
$ci = $cr * $riValue;

echo "\n--- RI/CI Derivation ---\n";
echo "RI for n=3: $riValue (expected 0.58)\n";
if (abs($riValue - 0.58) < 0.001) {
    echo "✅ RI correct\n";
} else {
    echo "❌ RI wrong: expected 0.58, got $riValue\n";
}

echo "CR: $cr\n";
echo "CI (derived: CR × RI): $ci\n";

// Validate CR < 0.1
if ($cr < 0.1) {
    echo "✅ CR < 0.1 — matrix consistent\n";
} else {
    echo "❌ CR >= 0.1 — matrix inconsistent, autoProcess would fail\n";
}

echo "\n=== Test Complete ===\n";
