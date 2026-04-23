<?php

/**
 * Test corrected CoCoSo calculation with 3 criteria example
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\Criteria;
use App\Models\Submission;
use App\Services\AHPService;
use App\Services\COCOSOService;

echo "=== CoCoSo Test: 3 Criteria with Fixed Formulas ===\n\n";

// Use existing test submission ID 16 (from earlier test)
$submissionId = 16;
$submission = Submission::find($submissionId);

if (! $submission) {
    exit("Submission not found.\n");
}

echo "Submission ID: $submissionId\n";
$n = $submission->criteria()->count();
echo "Criteria count: $n\n";

// Calculate AHP weights first
$ahp = new AHPService;
$ahpResults = $ahp->calculateWeights($submissionId);
$weights = $ahpResults['weights']; // associative by criteria_id

echo "\n--- AHP Weights ---\n";
foreach ($weights as $id => $w) {
    echo "  {$w['name']}: ".number_format($w['weight'], 4)."\n";
}

// Run CoCoSo
$cocoso = new COCOSOService;
$suggestions = $cocoso->calculateRanking($weights, null, $submissionId);

echo "\n--- CoCoSo Results ---\n";
foreach ($suggestions as $idx => $res) {
    $rank = $idx + 1;
    echo sprintf(
        "#%d %-20s  Qi=%.6f  Si=%.2f  Pi=%.2f  ka=%.4f  kb=%.4f  kc=%.4f\n",
        $rank,
        $res['alternative']->name,
        $res['qi'],
        $res['si'],
        $res['pi'],
        $res['ka'],
        $res['kb'],
        $res['kc']
    );
}

// Check if Qi values are in expected range (0.5-1.0 typically)
$qis = array_column($suggestions, 'qi');
$maxQi = max($qis);
$minQi = min($qis);
echo "\nQi range: ".number_format($minQi, 4).' - '.number_format($maxQi, 4)."\n";

if ($maxQi > 0 && $maxQi < 2) {
    echo "✅ Qi values are in reasonable range.\n";
} else {
    echo "⚠️  Qi values seem unusual (expected ~0.5-1.5).\n";
}

// Verify sum of ka+...
echo "\n--- Verification ---\n";
foreach ($suggestions as $idx => $res) {
    $product = $res['ka'] * $res['kb'] * $res['kc'];
    $sum = ($res['ka'] + $res['kb'] + $res['kc']) / 3;
    $expectedQi = pow($product, 1 / 3) + $sum;
    $diff = abs($expectedQi - $res['qi']);
    echo "Alt {$res['alternative']->name}: expected Qi ≈ ".number_format($expectedQi, 6).', actual = '.number_format($res['qi'], 6);
    echo ($diff < 0.0001) ? " ✅\n" : " ❌ (diff: $diff)\n";
}

echo "\n=== Test Complete ===\n";
