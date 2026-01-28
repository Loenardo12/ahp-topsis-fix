<?php

namespace App\Http\Controllers;

// Ganti use statement
use App\Models\Kelas10;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class Kelas10Controller extends Controller
{
    public function index()
    {
        // Karena nama route resource adalah 'kelas10', Laravel akan mencari model Kelas10 secara otomatis.
        // Kita tetap gunakan variabel $kelas10s untuk view.
        $kelas10s = Kelas10::latest()->paginate(10);
        return view('dashboard.kelas.kelas10.index', compact('kelas10s')); // Ganti variabel
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

        Kelas10::create($request->only(['title', 'description']));

        // Route tetap 'kelas10' karena nama resource-nya adalah 'kelas10'
        return redirect()->route('kelas10.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    // Parameter $kelas10 sesuai dengan route resource 'kelas10/{kelas10}'
    // Karena nama class model sekarang adalah Kelas10, Laravel bisa menemukannya.
    public function show(Kelas10 $kelas10)
{
    $absenKelas10 = $kelas10->isikelas10()->with('absenkelas10')->get()->flatMap(function ($siswa) {
        return $siswa->absenkelas10;
    })->sortBy('isikelas10.nama');

    // Ganti nama variabel agar sesuai dengan yang digunakan di view kelas10isi
    $kelas10_obj = $kelas10; // <-- Ganti dari $modelkelas10 menjadi $kelas10_obj atau $kelas10

    return view('dashboard.kelas.kelas10.isikelas10.kelas10isi', compact('absenKelas10', 'kelas10_obj')); // <-- Ganti variabel yang di-pass
}

    // Parameter harus sesuai dengan route resource 'kelas10/{kelas10}'
    public function edit(Kelas10 $kelas10)
    {
        return view('dashboard.kelas.kelas10.edit', compact('kelas10'));
    }

    // Parameter harus sesuai dengan route resource 'kelas10/{kelas10}'
    public function update(Request $request, Kelas10 $kelas10)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $kelas10->update($request->only(['title', 'description']));

        return redirect()->route('kelas10.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    // Parameter harus sesuai dengan route resource 'kelas10/{kelas10}'
    public function destroy(Kelas10 $kelas10)
    {
        $kelas10->delete();
        return redirect()->route('kelas10.index')->with('success', 'Kelas dan semua data siswa di dalamnya berhasil dihapus.');
    }
}
