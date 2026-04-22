<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Services\AHPService;
use App\Services\COCOSOService;
use Illuminate\Http\Request;

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
            'ranking' => array_values($request->ranking), // Reset keys
        ];

        // Sort by rank just in case
        usort($resultData['ranking'], fn ($a, $b) => $a['rank'] <=> $b['rank']);

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

    public function autoProcess($id)
    {
        $submission = Submission::with('criteria', 'alternatives')->findOrFail($id);

        try {
            $ahpResults = $this->ahpService->calculateWeights($submission->id);

            if (! isset($ahpResults['cr']) || $ahpResults['cr'] >= 0.1) {
                return back()->with('error', 'Consistency Ratio (CR) > 0.1. Silakan perbaiki perbandingan kriteria.');
            }

            $suggestions = $this->cocosoService->calculateRanking($ahpResults['weights'], null, $submission->id);

            if (empty($suggestions)) {
                return back()->with('error', 'Gagal menghitung ranking. Cek data alternatif dan scores.');
            }

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

            $ri = [0, 0, 0.58, 0.90, 1.12, 1.24, 1.32, 1.41, 1.45];
            $n = count($ahpResults['criteria']);
            $riValue = isset($ri[$n]) ? $ri[$n] : 1.45;
            $ci = ($ahpResults['cr'] > 0 && $riValue > 0) ? $ahpResults['cr'] * $riValue : 0;

            $bestAlt = $suggestions[0]['alternative']->name ?? 'Unknown';
            $autoText = 'Hasil perhitungan otomatis dengan menggunakan metode AHP untuk pembobotan kriteria dan CoCoSo untuk perankingan alternatif. Consistency Ratio (CR) = '.number_format($ahpResults['cr'], 4).' (konsisten). Berdasarkan hasil perhitungan, alternatif dengan nilai tertinggi adalah '.$bestAlt.'.';

            $resultData = [
                'manual' => false,
                'manual_text' => $autoText,
                'ranking' => $ranking,
                'weights' => $weights,
                'cr' => $ahpResults['cr'],
                'ci' => $ci,
                'ri' => $riValue,
                'n_criteria' => $n,
                'calculated_at' => now()->toDateTimeString(),
            ];

            $submission->update([
                'status' => 'processed',
                'result_data' => $resultData,
            ]);

            return redirect()->route('admin.submissions.index')
                ->with('success', 'Perhitungan otomatis berhasil! Hasil telah dikirim ke user.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal auto-process: '.$e->getMessage());
        }
    }
}
