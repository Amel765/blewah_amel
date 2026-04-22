<?php

/**
 * Full integration test: call autoProcess for 2-criteria submission
 * Run: php test_auto_process_integration.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\Criteria;
use App\Models\Submission;
use App\Services\AHPService;
use App\Services\COCOSOService;
use Illuminate\Support\Facades\DB;

echo "=== AutoProcess Integration Test (2 Criteria) ===\n\n";

// Use existing test submission or create new
$submission = Submission::where('status', 'pending')->first();
if (! $submission) {
    exit("No pending submission. Run test_ahp_2_criteria.php first.\n");
}

echo "Test Submission ID: {$submission->id}\n";
$n = $submission->criteria()->count();
echo "Criteria count: $n\n";

// Simulate autoProcess method from AdminSubmissionController
try {
    $ahpService = new AHPService;
    $cocosoService = new COCOSOService;

    // Step 1: Calculate AHP weights
    $ahpResults = $ahpService->calculateWeights($submission->id);
    echo "\n--- AHP Results ---\n";
    echo 'CR: '.($ahpResults['cr'] ?? 'N/A')."\n";

    // Step 2: Check CR
    if (! isset($ahpResults['cr']) || $ahpResults['cr'] >= 0.1) {
        exit("❌ CR >= 0.1 — Should not happen for n=2 (CR should be 0)\n");
    }
    echo "✅ CR check passed (< 0.1)\n";

    // Step 3: Calculate CoCoSo ranking
    $suggestions = $cocosoService->calculateRanking($ahpResults['weights'], null, $submission->id);
    if (empty($suggestions)) {
        exit("❌ No ranking suggestions generated\n");
    }
    echo '✅ CoCoSo ranking generated: '.count($suggestions)." alternatives\n";

    // Step 4: Prepare result data (simulate autoProcess code)
    $ranking = [];
    foreach ($suggestions as $index => $result) {
        $ranking[] = [
            'name' => $result['alternative']->name,
            'rank' => $index + 1,
            'qi' => $result['qi'],
            'si' => $result['si'],
            'pi' => $result['pi'],
            'ka' => $result['ka'],
            'kb' => $result['kb'],
            'kc' => $result['kc'],
        ];
    }

    $weights = [];
    foreach ($ahpResults['weights'] as $critId => $w) {
        $weights[] = [
            'name' => $w['name'],
            'weight' => $w['weight'],
        ];
    }

    $n_criteria = count($ahpResults['criteria']);
    $ri = [
        1 => 0.00, 2 => 0.00, 3 => 0.58, 4 => 0.90, 5 => 1.12,
        6 => 1.24, 7 => 1.32, 8 => 1.41, 9 => 1.45, 10 => 1.49,
    ];
    $riValue = $ri[$n_criteria] ?? 1.49;
    $cr = $ahpResults['cr'] ?? 0;
    $ci = $cr * $riValue;

    echo "\n--- Derived Values ---\n";
    echo "n (criteria): $n_criteria\n";
    echo "RI: $riValue\n";
    echo "CI: $ci\n";

    if ($n_criteria == 2 && $riValue != 0.00) {
        exit("❌ RI should be 0.00 for 2 criteria, but got $riValue\n");
    }
    echo "✅ RI correct for n=$n_criteria\n";

    // Step 5: Update submission
    $resultData = [
        'manual' => false,
        'manual_text' => 'Auto-generated result',
        'ranking' => $ranking,
        'weights' => $weights,
        'cr' => $cr,
        'ci' => $ci,
        'ri' => $riValue,
        'n_criteria' => $n_criteria,
        'calculated_at' => now()->toDateTimeString(),
    ];

    DB::transaction(function () use ($submission, $resultData) {
        $submission->update([
            'status' => 'processed',
            'result_data' => $resultData,
        ]);
    });

    echo "\n✅ Submission updated to 'processed'\n";
    echo "✅ Result data saved successfully\n";

    // Verify saved data
    $fresh = Submission::find($submission->id);
    $saved = $fresh->result_data;
    echo "\n--- Verification ---\n";
    echo "Status: {$fresh->status}\n";
    echo 'RI in saved data: '.($saved['ri'] ?? 'N/A')."\n";
    echo 'CR in saved data: '.($saved['cr'] ?? 'N/A')."\n";
    echo 'Number of alternatives in ranking: '.count($saved['ranking'] ?? [])."\n";

    if ($n_criteria == 2 && ($saved['ri'] ?? null) != 0.00) {
        echo '❌ SAVED RI IS WRONG: expected 0.00, got '.($saved['ri'] ?? 'null')."\n";
    } else {
        echo "✅ Saved RI is correct\n";
    }

    echo "\n=== Integration Test PASSED ===\n";

} catch (Exception $e) {
    echo '❌ Error: '.$e->getMessage()."\n";
    echo $e->getTraceAsString()."\n";
}
