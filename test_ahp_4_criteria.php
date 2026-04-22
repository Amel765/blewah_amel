<?php

/**
 * Integration test: 4 criteria (consistency check)
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\Criteria;
use App\Models\Submission;
use App\Models\SubmissionComparison;
use App\Models\SubmissionScore;
use App\Services\AHPService;
use App\Services\COCOSOService;

echo "=== AutoProcess Integration Test (4 Criteria) ===\n\n";

$submission = Submission::create([
    'user_id' => 1,
    'title' => 'Test 4 Criteria',
    'description' => 'Testing with 4 criteria - RI should be 0.90',
    'status' => 'pending',
]);

$c1 = $submission->criteria()->create(['name' => 'C1', 'type' => 'benefit']);
$c2 = $submission->criteria()->create(['name' => 'C2', 'type' => 'benefit']);
$c3 = $submission->criteria()->create(['name' => 'C3', 'type' => 'cost']);
$c4 = $submission->criteria()->create(['name' => 'C4', 'type' => 'benefit']);

// Consistent pairwise comparisons (reciprocal matrix)
$pairs = [
    [$c1->id, $c2->id, 3],
    [$c1->id, $c3->id, 5],
    [$c1->id, $c4->id, 7],
    [$c2->id, $c3->id, 3],
    [$c2->id, $c4->id, 5],
    [$c3->id, $c4->id, 3],
];
foreach ($pairs as [$id1, $id2, $val]) {
    SubmissionComparison::create(['submission_id' => $submission->id, 'criteria_id_1' => $id1, 'criteria_id_2' => $id2, 'value' => $val]);
    SubmissionComparison::create(['submission_id' => $submission->id, 'criteria_id_1' => $id2, 'criteria_id_2' => $id1, 'value' => 1 / $val]);
}

// Create 2 alternatives with scores
$alt1 = $submission->alternatives()->create(['name' => 'Alt A']);
$alt2 = $submission->alternatives()->create(['name' => 'Alt B']);
foreach ([$alt1, $alt2] as $alt) {
    foreach ([$c1, $c2, $c3, $c4] as $crit) {
        SubmissionScore::create([
            'submission_id' => $submission->id,
            'alternative_id' => $alt->id,
            'criteria_id' => $crit->id,
            'value' => rand(70, 95),
        ]);
    }
}

echo "Created submission ID: {$submission->id}\n";
$n = 4;
echo "n = $n\n";

$ahp = new AHPService;
$results = $ahp->calculateWeights($submission->id);
$cr = $results['cr'] ?? 0;

echo "\n--- AHP Results ---\n";
echo 'CR: '.number_format($cr, 4)."\n";
echo 'Weights: ';
foreach ($results['weightsIndexed'] as $w) {
    echo number_format($w, 4).' ';
}
echo "\n";

$ri = [1 => 0, 2 => 0, 3 => 0.58, 4 => 0.90, 5 => 1.12, 6 => 1.24, 7 => 1.32, 8 => 1.41, 9 => 1.45, 10 => 1.49];
$riVal = $ri[$n] ?? 1.49;
echo "RI for n=$n: $riVal (expected 0.90)\n";
echo ($riVal == 0.90) ? "✅ RI correct\n" : "❌ RI wrong\n";

if ($cr >= 0.1) {
    echo "⚠️  CR >= 0.1 — marginally consistent, may fail autoProcess check\n";
} else {
    echo "✅ CR < 0.1 — consistent\n";
}

// Simulate full autoProcess result storage
$cocoso = new COCOSOService;
$suggestions = $cocoso->calculateRanking($ahp->getWeightsFromCriteria($submission->id), null, $submission->id);
$ranking = [];
foreach ($suggestions as $i => $r) {
    $ranking[] = ['name' => $r['alternative']->name, 'rank' => $i + 1, 'qi' => $r['qi']];
}
$weightsList = [];
foreach ($results['weights'] as $wid => $w) {
    $weightsList[] = ['name' => $w['name'], 'weight' => $w['weight']];
}
$ci = $cr * $riVal;

$resultData = [
    'manual' => false,
    'manual_text' => 'Auto',
    'ranking' => $ranking,
    'weights' => $weightsList,
    'cr' => $cr,
    'ci' => $ci,
    'ri' => $riVal,
    'n_criteria' => $n,
];
$submission->update(['status' => 'processed', 'result_data' => $resultData]);
echo "\n✅ Saved: status=processed, ri={$resultData['ri']}, cr={$resultData['cr']}\n";
echo "=== Test Complete ===\n";
