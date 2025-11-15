<?php

namespace App\Http\Controllers;

// Ganti use statement
use App\Models\kelas12;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class kelas12Controller extends Controller
{
    public function index()
    {
        // Karena nama route resource adalah 'kelas12', Laravel akan mencari model kelas12 secara otomatis.
        // Kita tetap gunakan variabel $kelas12s untuk view.
        $kelas12s = kelas12::latest()->paginate(10);
        return view('dashboard.kelas.kelas12.index', compact('kelas12s')); // Ganti variabel
    }

    public function create()
    {
        return view('dashboard.kelas.kelas12.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        kelas12::create($request->only(['title', 'description']));

        // Route tetap 'kelas12' karena nama resource-nya adalah 'kelas12'
        return redirect()->route('kelas12.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    // Parameter $kelas12 sesuai dengan route resource 'kelas12/{kelas12}'
    // Karena nama class model sekarang adalah kelas12, Laravel bisa menemukannya.
    public function show(kelas12 $kelas12)
{
    $absenkelas12 = $kelas12->isikelas12()->with('absenkelas12')->get()->flatMap(function ($siswa) {
        return $siswa->absenkelas12;
    })->sortBy('isikelas12.nama');

    // Ganti nama variabel agar sesuai dengan yang digunakan di view kelas12isi
    $kelas12_obj = $kelas12; // <-- Ganti dari $modelkelas12 menjadi $kelas12_obj atau $kelas12

    return view('dashboard.kelas.kelas12.isikelas12.kelas12isi', compact('absenkelas12', 'kelas12_obj')); // <-- Ganti variabel yang di-pass
}

    // Parameter harus sesuai dengan route resource 'kelas12/{kelas12}'
    public function edit(kelas12 $kelas12)
    {
        return view('dashboard.kelas.kelas12.edit', compact('kelas12'));
    }

    // Parameter harus sesuai dengan route resource 'kelas12/{kelas12}'
    public function update(Request $request, kelas12 $kelas12)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $kelas12->update($request->only(['title', 'description']));

        return redirect()->route('kelas12.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    // Parameter harus sesuai dengan route resource 'kelas12/{kelas12}'
    public function destroy(kelas12 $kelas12)
    {
        $kelas12->delete();
        return redirect()->route('kelas12.index')->with('success', 'Kelas dan semua data siswa di dalamnya berhasil dihapus.');
    }
}
