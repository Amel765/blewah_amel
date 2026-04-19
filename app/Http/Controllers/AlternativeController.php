<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Alternative;

class AlternativeController extends Controller
{
    public function index()
    {
        $alternatives = Alternative::whereNull('submission_id')->get();
        return view('pages.alternative.index', compact('alternatives'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $data = $request->all();
        $data['submission_id'] = null;
        Alternative::create($data);

        return redirect()->back()->with('success', 'Alternatif berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $alternative = Alternative::findOrFail($id);
        $alternative->update($request->all());

        return redirect()->back()->with('success', 'Alternatif berhasil diperbarui');
    }

    public function destroy($id)
    {
        $alternative = Alternative::findOrFail($id);
        $alternative->delete();

        return redirect()->back()->with('success', 'Alternatif berhasil dihapus');
    }
}
