<?php

namespace App\Http\Controllers;

use App\Http\Requests\ObjekRequest;
use App\Http\Services\ObjekService;
use App\Models\Kelas10; // Model kelas 10
use App\Models\Kelas11; // Model kelas 11
use App\Models\Kelas12; // Model kelas 12
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Jika perlu transaksi

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
        return redirect('dashboard/objek')->with('berhasil', "Data berhasil dihapus!"); // Fix typo di pesan
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

    // Method untuk menampilkan form pilih kelas dari semua tingkatan
    public function pilihKelas()
    {
        // Ambil semua kelas dari masing-masing model
        $kelas10 = Kelas10::all();
        $kelas11 = Kelas11::all(); // Ambil dari model kelas 11
        $kelas12 = Kelas12::all(); // Ambil dari model kelas 12

        return view('dashboard.objek.pilih_kelas', compact('kelas10', 'kelas11', 'kelas12')); // Kirim semua ke view
    }

    // Method untuk mengambil siswa dari kelas yang dipilih (10, 11, atau 12)
    public function ambilSiswa(Request $request)
    {
        // Validasi input: kelas_id dan kelas_tingkat
        $request->validate([
            'kelas_id' => 'required|integer|min:1', // ID kelas bisa dari tabel mana saja, jadi integer positif
            'kelas_tingkat' => 'required|in:10,11,12', // Tingkat kelas harus 10, 11, atau 12
        ]);

        $kelasId = $request->kelas_id;
        $kelasTingkat = $request->kelas_tingkat;

        // Tentukan model dan relasi berdasarkan tingkat kelas
        switch ($kelasTingkat) {
            case '10':
                $kelasModel = Kelas10::class;
                $isiKelasRelation = 'isikelas10'; // Nama relasi di model Kelas10
                $kelasTitle = 'Kelas 10'; // Label untuk pesan
                break;
            case '11':
                $kelasModel = Kelas11::class;
                $isiKelasRelation = 'isikelas11'; // Nama relasi di model Kelas11
                $kelasTitle = 'Kelas 11'; // Label untuk pesan
                break;
            case '12':
                $kelasModel = Kelas12::class;
                $isiKelasRelation = 'isikelas12'; // Nama relasi di model Kelas12
                $kelasTitle = 'Kelas 12'; // Label untuk pesan
                break;
            default:
                // Ini seharusnya tidak terjadi karena sudah divalidasi
                abort(400, 'Tingkat kelas tidak valid.');
        }

        // Cari kelas berdasarkan ID dan model yang ditentukan
        $kelas = call_user_func([$kelasModel, 'findOrFail'], $kelasId);

        // Ambil semua siswa dari kelas tersebut melalui relasi yang benar
        $siswaList = $kelas->$isiKelasRelation()->pluck('nama')->toArray();

        if (empty($siswaList)) {
             return redirect()->back()->withErrors(['kelas_id' => 'Tidak ada siswa ditemukan di kelas ini.'])->withInput();
        }

        // Simpan ke tabel objek dalam transaksi untuk memastikan konsistensi
        DB::transaction(function () use ($siswaList) {
            foreach ($siswaList as $nama) {
                \App\Models\Objek::firstOrCreate(['nama' => $nama]); // Gunakan firstOrCreate untuk mencegah duplikat
            }
        });

        return redirect()->route('objek.index')->with('berhasil', "Data siswa dari $kelasTitle ({$kelas->title}) berhasil ditambahkan ke Objek.");
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
