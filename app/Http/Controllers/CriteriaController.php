<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Criteria;

class CriteriaController extends Controller
{
    public function index()
    {
        $criteria = Criteria::whereNull('submission_id')->get();
        return view('pages.criteria.index', compact('criteria'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:benefit,cost',
        ]);

        $data = $request->all();
        $data['submission_id'] = null;
        Criteria::create($data);

        return redirect()->back()->with('success', 'Kriteria berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:benefit,cost',
        ]);

        $criteria = Criteria::findOrFail($id);
        $criteria->update($request->all());

        return redirect()->back()->with('success', 'Kriteria berhasil diperbarui');
    }

    public function destroy($id)
    {
        $criteria = Criteria::findOrFail($id);
        $criteria->delete();

        return redirect()->back()->with('success', 'Kriteria berhasil dihapus');
    }
}
