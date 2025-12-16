<?php

namespace App\Http\Controllers;

use App\Http\Requests\PenilaianRequest;
use App\Http\Services\PenilaianService;
use App\Http\Services\SubKriteriaService;
use App\Models\Kriteria; // Import model Kriteria
use App\Models\Penilaian;
use App\Models\Alternatif;
use Illuminate\Support\Facades\DB;


use App\Models\SubKriteria;
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

        // Ambil semua alternatif dengan nama
        $allAlternatif = $this->penilaianService->getAlternatifIdsWithNames();

        // Cari indeks alternatif_id saat ini dalam koleksi
        $currentIndex = $allAlternatif->search(function ($item) use ($alternatif_id) {
            return $item->alternatif_id == $alternatif_id;
        });

        $prevId = null;
        $nextId = null;

        if ($currentIndex !== false) {
            $prevId = $allAlternatif->get($currentIndex - 1)?->alternatif_id;
            $nextId = $allAlternatif->get($currentIndex + 1)?->alternatif_id;
        }

        // $subKriteria mungkin tidak digunakan di view ini lagi
        // $subKriteria = $this->subKriteriaService->getAll();

        return view('dashboard.penilaian.edit', [
            "judul" => $judul,
            "data" => $data,
            "data2" => $data2,
            // "subKriteria" => $subKriteria, // Comment out jika tidak digunakan
            "prevId" => $prevId,
            "nextId" => $nextId,
            "currentNama" => $data->alternatif->objek->nama, // Kirim nama siswa saat ini
            "allAlternatif" => $allAlternatif, // Kirim seluruh koleksi jika diperlukan di view untuk info lain
        ]);
    }



   public function perbarui(Request $request)
    {
        $request->validate([
            'alternatif_id' => 'required|exists:alternatif,id',
            'nilai_asli' => 'required|array',
            'nilai_asli.*' => 'required|integer|min:0|max:100',
        ]);

        $alternatifId = $request->alternatif_id;
        $nilaiAsliArray = $request->nilai_asli;

        \DB::transaction(function () use ($alternatifId, $nilaiAsliArray) {
            foreach ($nilaiAsliArray as $kriteriaId => $nilaiAsli) {
                // Temukan atau buat record penilaian
                $penilaian = Penilaian::updateOrCreate(
                    [
                        'alternatif_id' => $alternatifId,
                        'kriteria_id' => $kriteriaId,
                    ],
                    [
                        'nilai_asli' => $nilaiAsli,
                        // Jika Anda ingin tetap menyimpan sub_kriteria_id, Anda bisa hitung di sini
                        // 'sub_kriteria_id' => $this->hitungSubKriteriaId($nilaiAsli, $kriteriaId),
                    ]
                );
            }
        });

        // Periksa tombol mana yang diklik
        $action = $request->input('action');

        if ($action === 'next') {
            // Ambil ID alternatif berikutnya
            $allAlternatif = $this->penilaianService->getAlternatifIdsWithNames();
            $currentIndex = $allAlternatif->search(function ($item) use ($alternatifId) {
                return $item->alternatif_id == $alternatifId;
            });

            $nextId = $allAlternatif->get($currentIndex + 1)?->alternatif_id;

            if ($nextId) {
                return redirect()->route('penilaian.ubah', $nextId)->with('berhasil', "Data untuk {$request->current_nama} berhasil diperbarui. Berpindah ke berikutnya.");
            } else {
                return redirect()->route('penilaian')->with('berhasil', "Data untuk {$request->current_nama} berhasil diperbarui. Tidak ada data berikutnya.");
            }
        } elseif ($action === 'previous') {
            // Ambil ID alternatif sebelumnya
             $allAlternatif = $this->penilaianService->getAlternatifIdsWithNames();
            $currentIndex = $allAlternatif->search(function ($item) use ($alternatifId) {
                return $item->alternatif_id == $alternatifId;
            });

            $prevId = $allAlternatif->get($currentIndex - 1)?->alternatif_id;

            if ($prevId) {
                return redirect()->route('penilaian.ubah', $prevId)->with('berhasil', "Data untuk {$request->current_nama} berhasil diperbarui. Berpindah ke sebelumnya.");
            } else {
                return redirect()->route('penilaian')->with('berhasil', "Data untuk {$request->current_nama} berhasil diperbarui. Tidak ada data sebelumnya.");
            }
        } else { // Asumsikan 'update' atau default
            return redirect()->route('penilaian')->with('berhasil', "Data untuk {$request->current_nama} berhasil diperbarui.");
        }
    }


    // Fungsi bantu untuk menentukan sub_kriteria_id berdasarkan nilai_asli dan kriteria
    private function hitungSubKriteriaId($nilaiAsli, $kriteriaId)
    {
        // Ambil sub-kriteria untuk kriteria ini, diurutkan berdasarkan nilai tertinggi dulu
        $subKriteria = SubKriteria::where('kriteria_id', $kriteriaId)
                                           ->orderBy('nilai', 'desc')
                                           ->get();

        foreach ($subKriteria as $sub) {
            if ($nilaiAsli >= $sub->nilai) {
                return $sub->id;
            }
        }

        // Jika tidak cocok dengan yang lebih tinggi, kembalikan yang terendah (misalnya 'Tidak Baik' dengan nilai 5)
        // Atau bisa return null jika tidak ada sub-kriteria yang sesuai
        // Sesuaikan logika ini dengan kebijakan penilaian Anda.
        // Contoh: jika nilai 0-49 selalu masuk ke sub-kriteria dengan nilai terendah.
        $subKriteriaTerendah = SubKriteria::where('kriteria_id', $kriteriaId)->orderBy('nilai', 'asc')->first();
        return $subKriteriaTerendah ? $subKriteriaTerendah->id : null;
    }
}
