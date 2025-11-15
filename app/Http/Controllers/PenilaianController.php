<?php

namespace App\Http\Controllers;

use App\Http\Requests\PenilaianRequest;
use App\Http\Services\PenilaianService;
use App\Http\Services\SubKriteriaService;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    protected $penilaianService, $subKriteriaService;

    public function __construct(PenilaianService $penilaianService, SubKriteriaService $subKriteriaService)
    {
        $this->penilaianService = $penilaianService;
        $this->subKriteriaService = $subKriteriaService;
    }

    public function index()
    {
        $judul = "Penilaian";

        $data = $this->penilaianService->getAll();
        $subKriteria = $this->subKriteriaService->getAll();

        return view('dashboard.penilaian.index', [
            "judul" => $judul,
            "data" => $data,
            "subKriteria" => $subKriteria,
        ]);
    }

    public function ubah($alternatif_id)
{
    $judul = "Penilaian";

    // Ambil data penilaian untuk alternatif yang dipilih
    $data = $this->penilaianService->ubahGetData($alternatif_id)->first();
    $data2 = $this->penilaianService->ubahGetData($alternatif_id);
    $subKriteria = $this->subKriteriaService->getAll();

    // Buat array untuk mencocokkan sub_kriteria_id berdasarkan kriteria_id
    $nilai_per_kriteria = [];
    foreach ($data2 as $penilaian) {
        $nilai_per_kriteria[$penilaian->kriteria_id] = $penilaian->sub_kriteria_id;
    }

    return view('dashboard.penilaian.edit', [
        "judul" => $judul,
        "data" => $data,
        "data2" => $data2, // Anda masih bisa mengakses data2 jika diperlukan di view lain
        "subKriteria" => $subKriteria,
        "nilai_per_kriteria" => $nilai_per_kriteria, // Kirim array ke view
    ]);
}



    public function perbarui(Request $request)
    {
        $data = $this->penilaianService->perbaruiPostData($request);
        return redirect('dashboard/penilaian')->with('berhasil', "Data berhasil diperbarui!");
    }
}
