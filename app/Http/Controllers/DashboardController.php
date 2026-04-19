<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Criteria;
use App\Models\Alternative;
use App\Models\Submission;
use App\Models\Comparison;
use App\Models\Score;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $criteriaCount = Criteria::whereNull('submission_id')->count();
        $alternativeCount = Alternative::whereNull('submission_id')->count();
        $submissionCount = Submission::count();
        return view('pages.dashboard', compact('criteriaCount', 'alternativeCount', 'submissionCount'));
    }

    public function reset()
    {
        try {
            DB::beginTransaction();

            // Selective Purge: Only global data (submission_id is null)
            
            // Delete global scores and comparisons first due to FK constraints
            Score::whereHas('alternative', function($q) {
                $q->whereNull('submission_id');
            })->delete();

            Comparison::whereHas('criteria1', function($q) {
                $q->whereNull('submission_id');
            })->delete();

            // Delete global criteria and alternatives
            Criteria::whereNull('submission_id')->delete();
            Alternative::whereNull('submission_id')->delete();

            DB::commit();

            return redirect()->route('admin.dashboard')->with('success', 'Dashboard berhasil dibersihkan. Semua data perhitungan telah dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.dashboard')->with('error', 'Gagal membersihkan dashboard: ' . $e->getMessage());
        }
    }
}
