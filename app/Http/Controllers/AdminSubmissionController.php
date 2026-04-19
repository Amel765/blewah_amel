<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Submission;
use App\Models\Criteria;
use App\Services\AHPService;
use App\Services\COCOSOService;

class AdminSubmissionController extends Controller
{
    protected $ahpService;
    protected $cocosoService;

    public function __construct(AHPService $ahpService, COCOSOService $cocosoService)
    {
        $this->ahpService = $ahpService;
        $this->cocosoService = $cocosoService;
    }

    public function index()
    {
        $submissions = Submission::where('is_hidden_from_admin', false)
            ->with('user')
            ->latest()
            ->get();
        return view('pages.admin.submissions.index', compact('submissions'));
    }

    public function show($id)
    {
        $submission = Submission::with('user', 'comparisons', 'scores', 'criteria', 'alternatives')->findOrFail($id);
        return view('pages.admin.submissions.show', compact('submission'));
    }

    public function inputResult($id)
    {
        $submission = Submission::with('user', 'criteria', 'alternatives')->findOrFail($id);
        
        // Calculate suggestions based on user input
        $suggestions = null;
        try {
            $ahpResults = $this->ahpService->calculateWeights($submission->id);
            if (isset($ahpResults['cr']) && $ahpResults['cr'] < 0.1) {
                $suggestions = $this->cocosoService->calculateRanking($ahpResults['weights'], null, $submission->id);
            }
        } catch (\Exception $e) {
            // Ignore error for suggestions
        }

        return view('pages.admin.submissions.input_result', compact('submission', 'suggestions'));
    }

    public function storeResult(Request $request, $id)
    {
        $submission = Submission::findOrFail($id);

        $request->validate([
            'ranking' => 'required|array',
            'ranking.*.name' => 'required|string',
            'ranking.*.rank' => 'required|integer',
            'ranking.*.qi' => 'required|numeric',
        ]);

        // Format result data
        $resultData = [
            'manual' => true,
            'manual_text' => $request->manual_text,
            'ranking' => array_values($request->ranking) // Reset keys
        ];

        // Sort by rank just in case
        usort($resultData['ranking'], fn($a, $b) => $a['rank'] <=> $b['rank']);

        $submission->update([
            'status' => 'processed',
            'result_data' => $resultData,
        ]);

        return redirect()->route('admin.submissions.index')->with('success', 'Hasil berhasil disimpan secara manual dan dikirim ke user.');
    }

    public function destroy($id)
    {
        $submission = Submission::findOrFail($id);
        
        // Hide from admin instead of deleting (so user can still see it)
        $submission->update(['is_hidden_from_admin' => true]);
        
        return redirect()->route('admin.submissions.index')->with('success', 'Perhitungan berhasil dihapus dari daftar.');
    }
}
