<?php

namespace App\Http\Controllers;

use App\Http\Requests\ObjekRequest;
use App\Http\Services\ObjekService;
use App\Models\Kelas10;
use Illuminate\Http\Request;

class ObjekController extends Controller
{
    protected $objekService;

    public function __construct(ObjekService $objekService)
    {
        $this->objekService = $objekService;
    }

    public function index()
    {
        $judul = "Siswa";

        $data = $this->objekService->getAll();

        return view('dashboard.objek.index', [
            "judul" => $judul,
            "data" => $data,
        ]);
    }

    public function simpan(ObjekRequest $request)
    {
        $data = $this->objekService->simpanPostData($request);
        return redirect('dashboard/objek')->with('berhasil', "Data berhasil disimpan!");
    }

    public function ubah(Request $request)
    {
        $data = $this->objekService->ubahGetData($request);
        return $data;
    }

    public function perbarui(ObjekRequest $request)
    {
        $data = $this->objekService->perbaruiPostData($request);
        return redirect('dashboard/objek')->with('berhasil', "Data berhasil diperbarui!");
    }

    public function hapus(Request $request)
    {
        try {
            $this->objekService->hapusPostData($request->id);
        } catch (\Throwable $th) {
            return abort(400);
        }
        return redirect('dashboard/objek')->with('berhasil', "Data berhasil diperbarui!");
    }

    public function import(Request $request)
    {
        // validasi
        $request->validate([
            'import_data' => 'required|mimes:xls,xlsx'
        ]);

        $this->objekService->import($request);

        // alihkan halaman kembali
        return redirect('dashboard/objek')->with('berhasil', "Data berhasil di import!");
    }



    public function pilihKelas()
{
    // Ambil semua kelas dari modelkelas10s
    $kelas10 = Kelas10::all();
    // Jika Anda juga memiliki ModelKelas11 dan ModelKelas12, tambahkan di sini
    // $kelas11 = \App\Models\ModelKelas11::all();
    // $kelas12 = \App\Models\ModelKelas12::all();

    return view('dashboard.objek.pilih_kelas', compact('kelas10')); // Kita buat view ini nanti
}

public function ambilSiswa(Request $request)
{
    $request->validate([
        'kelas_id' => 'required|exists:modelkelas10s,id', // Sesuaikan jika ada kelas 11/12
    ]);

    $kelas =Kelas10::findOrFail($request->kelas_id);

    // Ambil semua siswa dari kelas tersebut
    $siswaList = $kelas->isikelas10()->pluck('nama')->toArray();

    // Simpan ke tabel objek
    foreach ($siswaList as $nama) {
        \App\Models\Objek::firstOrCreate(['nama' => $nama]); // Gunakan firstOrCreate untuk mencegah duplikat
    }

    return redirect()->route('objek.index')->with('berhasil', 'Data siswa dari kelas ' . $kelas->title . ' berhasil ditambahkan ke Objek.');

}


public function hapusMultiple(Request $request)
{
    $request->validate([
        'ids' => 'required|array',
        'ids.*' => 'exists:objek,id', // Pastikan ID ada di tabel objek
    ]);

    $ids = $request->ids;

    \App\Models\Objek::whereIn('id', $ids)->delete();

    return redirect()->route('objek.index')->with('berhasil', count($ids) . ' data berhasil dihapus.');
}

}
