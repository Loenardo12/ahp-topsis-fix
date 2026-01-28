<?php

namespace App\Http\Controllers;

// Ganti use statement
use App\Models\Kelas11;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class Kelas11Controller extends Controller
{
    public function index()
    {
        // Karena nama route resource adalah 'kelas11', Laravel akan mencari model kelas11 secara otomatis.
        // Kita tetap gunakan variabel $kelas11s untuk view.
        $kelas11s = Kelas11::latest()->paginate(10);
        return view('dashboard.kelas.kelas11.index', compact('kelas11s')); // Ganti variabel
    }

    public function create()
    {
        return view('dashboard.kelas.kelas11.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Kelas11::create($request->only(['title', 'description']));

        // Route tetap 'kelas11' karena nama resource-nya adalah 'kelas11'
        return redirect()->route('kelas11.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    // Parameter $kelas11 sesuai dengan route resource 'kelas11/{kelas11}'
    // Karena nama class model sekarang adalah kelas11, Laravel bisa menemukannya.
    public function show(Kelas11 $kelas11)
{
    $absenkelas11 = $kelas11->isikelas11()->with('absenkelas11')->get()->flatMap(function ($siswa) {
        return $siswa->absenkelas11;
    })->sortBy('isikelas11.nama');

    // Ganti nama variabel agar sesuai dengan yang digunakan di view kelas11isi
    $kelas11_obj = $kelas11; // <-- Ganti dari $modelkelas11 menjadi $kelas11_obj atau $kelas11

    return view('dashboard.kelas.kelas11.isikelas11.kelas11isi', compact('absenkelas11', 'kelas11_obj')); // <-- Ganti variabel yang di-pass
}

    // Parameter harus sesuai dengan route resource 'kelas11/{kelas11}'
    public function edit(kelas11 $kelas11)
    {
        return view('dashboard.kelas.kelas11.edit', compact('kelas11'));
    }

    // Parameter harus sesuai dengan route resource 'kelas11/{kelas11}'
    public function update(Request $request, kelas11 $kelas11)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $kelas11->update($request->only(['title', 'description']));

        return redirect()->route('kelas11.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    // Parameter harus sesuai dengan route resource 'kelas11/{kelas11}'
    public function destroy(kelas11 $kelas11)
    {
        $kelas11->delete();
        return redirect()->route('kelas11.index')->with('success', 'Kelas dan semua data siswa di dalamnya berhasil dihapus.');
    }
}
