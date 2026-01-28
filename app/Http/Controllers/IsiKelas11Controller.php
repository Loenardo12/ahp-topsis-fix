<?php

namespace App\Http\Controllers;
use App\Models\kelas11;
use App\Models\Isikelas11;
use Illuminate\Http\Request;

class IsiKelas11Controller extends Controller
{
    // Jika diperlukan, tambahkan create untuk add siswa
    public function create($kelasId)
    {
        $modelkelas11 = kelas11::findOrFail($kelasId);
        return view('dashboard.kelas.kelas11.isikelas11.create', compact('modelkelas11'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'modelkelas11_id' => 'required|exists:modelkelas11s,id',
            'nama' => 'required',
            'nisn' => 'required|unique:isikelas11s,nisn',
        ]);
        Isikelas11::create($request->only(['modelkelas11_id', 'nama', 'nisn']));
        return redirect()->route('modelkelas11.show', $request->modelkelas11_id)->with('success', 'Siswa berhasil ditambahkan');
    }
    public function destroy($id)
    {
        Isikelas11::findOrFail($id)->delete();
        return back()->with('success', 'Siswa dihapus');
    }
}
