<?php

namespace App\Http\Controllers;
use App\Models\kelas12;
use App\Models\Isikelas12;
use Illuminate\Http\Request;

class IsiKelas12Controller extends Controller
{
    // Jika diperlukan, tambahkan create untuk add siswa
    public function create($kelasId)
    {
        $modelkelas12 = kelas12::findOrFail($kelasId);
        return view('dashboard.kelas.kelas12.isikelas12.create', compact('modelkelas12'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'modelkelas12_id' => 'required|exists:modelkelas12s,id',
            'nama' => 'required',
            'nisn' => 'required|unique:isikelas12s,nisn',
        ]);
        Isikelas12::create($request->only(['modelkelas12_id', 'nama', 'nisn']));
        return redirect()->route('modelkelas12.show', $request->modelkelas12_id)->with('success', 'Siswa berhasil ditambahkan');
    }
    public function destroy($id)
    {
        Isikelas12::findOrFail($id)->delete();
        return back()->with('success', 'Siswa dihapus');
    }
}
