<?php

namespace App\Http\Controllers;

use App\Models\Comparison;
use App\Models\Criteria;
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
        $criteria = Criteria::orderBy('id')->get();
        $results = null;
        $savedComparisons = [];

        // Load saved comparisons for display
        $comparisons = Comparison::all();
        foreach ($comparisons as $comp) {
            $savedComparisons[$comp->criteria_id_1][$comp->criteria_id_2] = $comp->value;
        }

        // Attempt to calculate if comparisons exist
        if (Comparison::exists()) {
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
                        Comparison::updateOrCreate(
                            ['criteria_id_1' => $id1, 'criteria_id_2' => $id2],
                            ['value' => $value]
                        );
                    }
                }
            }
        }

        return redirect()->route('ahp.index')->with('success', 'Perhitungan AHP berhasil diperbarui');
    }
}
