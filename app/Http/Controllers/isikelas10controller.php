<?php

namespace App\Http\Controllers;

use App\Models\IsiKelas10;
use Illuminate\Http\Request;

class IsiKelas10Controller extends Controller
{
    // Jika diperlukan, tambahkan create untuk add siswa
    public function create($kelasId)
    {
        $modelkelas10 = \App\Models\ModelKelas10::findOrFail($kelasId);
        return view('dashboard.kelas.kelas10.isikelas10.create', compact('modelkelas10'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'modelkelas10_id' => 'required|exists:modelkelas10s,id',
            'nama' => 'required',
            'nisn' => 'required|unique:isikelas10s,nisn',
        ]);
        IsiKelas10::create($request->only(['modelkelas10_id', 'nama', 'nisn']));
        return redirect()->route('modelkelas10.show', $request->modelkelas10_id)->with('success', 'Siswa berhasil ditambahkan');
    }
    public function destroy($id)
    {
        IsiKelas10::findOrFail($id)->delete();
        return back()->with('success', 'Siswa dihapus');
    }
}
