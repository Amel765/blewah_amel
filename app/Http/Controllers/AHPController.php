<?php

namespace App\Http\Controllers;

use App\Models\Comparison;
use App\Models\Criteria;
use App\Models\Alternative;
use App\Services\AHPService;
use Illuminate\Http\Request;

class AHPController extends Controller
{
    protected $ahpService;

    public function __construct(AHPService $ahpService)
    {
        $this->ahpService = $ahpService;
    }

    public function index()
    {
        $criteria = Criteria::whereNull('submission_id')->orderBy('id')->get();
        $results = null;
        $savedComparisons = [];

        // Load saved comparisons for display (only global comparisons)
        $comparisons = Comparison::whereHas('criteria1', function($q) {
            $q->whereNull('submission_id');
        })->get();
        foreach ($comparisons as $comp) {
            $savedComparisons[$comp->criteria_id_1][$comp->criteria_id_2] = $comp->value;
        }

        // Attempt to calculate if comparisons exist
        if (Comparison::whereHas('criteria1', function($q) {
            $q->whereNull('submission_id');
        })->exists()) {
            $results = $this->ahpService->calculateWeights();
        }

        return view('pages.ahp.index', compact('criteria', 'results', 'savedComparisons'));
    }

    public function calculate(Request $request)
    {
        $comparisons = $request->input('comparison');

        if ($comparisons) {
            foreach ($comparisons as $id1 => $others) {
                foreach ($others as $id2 => $value) {
                    if ($id1 != $id2 && $value != 0) {
                        // Simpan nilai asli (misal: WP vs JBP = 2)
                        Comparison::updateOrCreate(
                            ['criteria_id_1' => $id1, 'criteria_id_2' => $id2],
                            ['value' => $value]
                        );

                        // Simpan kebalikannya (misal: JBP vs WP = 1/2)
                        Comparison::updateOrCreate(
                            ['criteria_id_1' => $id2, 'criteria_id_2' => $id1],
                            ['value' => 1 / $value]
                        );
                    }
                }
            }
        }

        return redirect()->route('admin.ahp.index')->with('success', 'Perhitungan AHP berhasil diperbarui');
    }
}
