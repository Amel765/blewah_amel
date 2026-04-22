<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\Criteria;
use App\Models\Submission;
use App\Models\SubmissionComparison;
use App\Models\SubmissionScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserSubmissionController extends Controller
{
    public function index()
    {
        $submissions = Submission::where('user_id', auth()->id())->latest()->get();

        return view('pages.user.dashboard', compact('submissions'));
    }

    public function create()
    {
        return view('pages.user.submission_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $submission = Submission::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'draft', // User needs to input values
        ]);

        return redirect()->route('user.submission.manage_data', $submission->id);
    }

    public function manageData($id)
    {
        $submission = Submission::where('user_id', auth()->id())->findOrFail($id);
        $criteria = $submission->criteria;
        $alternatives = $submission->alternatives;

        return view('pages.user.submission_manage', compact('submission', 'criteria', 'alternatives'));
    }

    public function storeCriteria(Request $request, $id)
    {
        $submission = Submission::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:benefit,cost',
        ]);

        $submission->criteria()->create($request->all());

        return back()->with('success', 'Kriteria berhasil ditambahkan');
    }

    public function destroyCriteria($id)
    {
        $criteria = Criteria::whereHas('submission', function ($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($id);

        $criteria->delete();

        return back()->with('success', 'Kriteria berhasil dihapus');
    }

    public function storeAlternative(Request $request, $id)
    {
        $submission = Submission::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $submission->alternatives()->create($request->all());

        return back()->with('success', 'Alternatif berhasil ditambahkan');
    }

    public function destroyAlternative($id)
    {
        $alternative = Alternative::whereHas('submission', function ($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($id);

        $alternative->delete();

        return back()->with('success', 'Alternatif berhasil dihapus');
    }

    public function inputValues($id)
    {
        $submission = Submission::where('user_id', auth()->id())->with(['criteria', 'alternatives'])->findOrFail($id);

        if ($submission->criteria->count() < 2 || $submission->alternatives->count() < 2) {
            return back()->with('error', 'Silakan tambahkan minimal 2 kriteria dan 2 alternatif terlebih dahulu.');
        }

        return view('pages.user.submission_form', [
            'submission' => $submission,
            'criteria' => $submission->criteria,
            'alternatives' => $submission->alternatives,
        ]);
    }

    public function submitValues(Request $request, $id)
    {
        $submission = Submission::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'comparison' => 'required|array',
            'score' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            // Clear existing data if any (re-entry)
            $submission->comparisons()->delete();
            $submission->scores()->delete();

            // Save Comparisons
            foreach ($request->comparison as $id1 => $others) {
                foreach ($others as $id2 => $value) {
                    if ($id1 != $id2 && $value != 0) {
                        SubmissionComparison::create([
                            'submission_id' => $submission->id,
                            'criteria_id_1' => $id1,
                            'criteria_id_2' => $id2,
                            'value' => $value,
                        ]);
                    }
                }
            }

            // Save Scores
            foreach ($request->score as $altId => $criteriaScores) {
                foreach ($criteriaScores as $critId => $value) {
                    SubmissionScore::create([
                        'submission_id' => $submission->id,
                        'alternative_id' => $altId,
                        'criteria_id' => $critId,
                        'value' => $value,
                    ]);
                }
            }

            // Set to pending (finalized by user)
            $submission->update(['status' => 'pending']);

            DB::commit();

            return redirect()->route('user.dashboard')->with('success', 'Pengajuan berhasil dikirim. Menunggu proses admin.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan saat menyimpan data: '.$e->getMessage());
        }
    }

    public function show($id)
    {
        $submission = Submission::where('user_id', auth()->id())->findOrFail($id);

        return view('pages.user.submission_show', compact('submission'));
    }

    public function destroy($id)
    {
        // Pastikan submission milik user yang sedang login
        $submission = Submission::where('user_id', auth()->id())->findOrFail($id);

        // Hapus submission (ini akan otomatis menghapus kriteria/alternatif jika Anda setting cascade di database)
        $submission->delete();

        return redirect()->route('user.dashboard')->with('success', 'Pengajuan berhasil dihapus');
    }

    public function resend($id)
    {
        $submission = Submission::where('user_id', auth()->id())->findOrFail($id);

        // Only allow resend if submission is already processed/completed
        if ($submission->status !== 'processed') {
            return back()->with('error', 'Hanya pengajuan yang sudah selesai diproses yang dapat dikirim ulang.');
        }

        // Clear previous results and reset to draft for editing
        $submission->update([
            'status' => 'draft',
            'result_data' => null,
        ]);

        return redirect()->route('user.submission.manage_data', $submission->id)
            ->with('success', 'Pengajuan dikirim ulang. Silakan edit data sebelum dikirim kembali ke admin.');
    }
}
