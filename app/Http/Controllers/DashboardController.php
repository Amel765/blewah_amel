<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Criteria;
use App\Models\Alternative;

class DashboardController extends Controller
{
    public function index()
    {
        $criteriaCount = Criteria::count();
        $alternativeCount = Alternative::count();
        return view('pages.dashboard', compact('criteriaCount', 'alternativeCount'));
    }
}
