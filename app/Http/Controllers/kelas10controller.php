<?php

namespace App\Http\Controllers;
use App\Models\modelkelas10;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
class kelas10controller extends Controller
{



    public function index()
    {
        $kelas10 = modelkelas10::latest()->paginate(10);
        return view('dashboard.kelas.kelas10.index', compact('kelas10'));
    }

    public function create()
    {
        return view('dashboard.kelas.kelas10.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        modelkelas10::create($request->only(['title', 'description']));

        return redirect()->route('kelas10.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function show(modelkelas10 $kelas10)
    {
        // Mengambil semua siswa di kelas ini
        $absenKelas10 = $kelas10->isikelas10()->with('absenkelas10')->get()->flatMap(function ($siswa) {
            // Jika siswa memiliki data absensi, ambil semua
            return $siswa->absenkelas10;
        })->sortBy('isikelas10.nama'); // Urutkan berdasarkan nama siswa

        $modelkelas10 = $kelas10; // Untuk digunakan di view

        return view('dashboard.kelas.kelas10.isikelas10.kelas10isi', compact('absenKelas10', 'modelkelas10'));
    }

    public function edit(modelkelas10 $kelas10)
    {
        return view('dashboard.kelas.kelas10.edit', compact('kelas10'));
    }

    public function update(Request $request, modelkelas10 $kelas10)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $kelas10->update($request->only(['title', 'description']));

        return redirect()->route('kelas10.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(modelkelas10 $kelas10)
    {
        $kelas10->delete(); // Karena di migration foreign key ada onDelete('cascade'), maka semua IsiKelas10 dan AbsenKelas10 terkait juga akan dihapus
        return redirect()->route('kelas10.index')->with('success', 'Kelas dan semua data siswa di dalamnya berhasil dihapus.');
    }
}
