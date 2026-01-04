<?php

namespace App\Http\Controllers;

use App\Http\Requests\PenilaianRequest;
use App\Http\Services\PenilaianService;
use App\Http\Services\SubKriteriaService;
use App\Models\Kriteria; // Import model Kriteria
use App\Models\Penilaian;
use App\Models\Alternatif;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PenilaianImport;
use Illuminate\Support\Facades\DB;
use App\Imports\DebugImport; // Import DebugImport


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

        // --- TAMBAHKAN INI ---
        // Ambil semua sub-kriteria dan kelompokkan berdasarkan kriteria_id
        $subKriteriaGrouped = SubKriteria::with('kriteria') // Opsional, jika ingin nama kriteria juga
            ->get()
            ->groupBy('kriteria_id')
            ->map(function ($subKriterias) {
                // Urutkan sub-kriteria dalam setiap grup berdasarkan nilai_max (atau nilai_min) descending
                // Agar pencarian rentang lebih efisien nanti (ambil yang pertama yang cocok)
                return $subKriterias->sortByDesc('nilai_max')->values();
            });
        // -------------------

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
            // --- TAMBAHKAN INI ---
            "subKriteriaGrouped" => $subKriteriaGrouped,
            // -------------------
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

        DB::transaction(function () use ($alternatifId, $nilaiAsliArray) {
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

   // Method untuk menampilkan form import
    public function showImportForm()
    {
        $judul = "Import Penilaian";
        // Ambil semua kriteria untuk ditampilkan di dropdown
        $kriterias = Kriteria::all(); // <-- Ambil semua kriteria
        return view('dashboard.penilaian.import', compact('judul', 'kriterias'));
    }

    // Method untuk memproses file import
   

public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
            'target_kriteria_id' => 'required|exists:kriteria,id',
            'sheet_name' => 'required|string',
        ]);

        try {
            $targetKriteriaId = $request->input('target_kriteria_id');
            $sheetName = $request->input('sheet_name');

            $import = new PenilaianImport($targetKriteriaId, $sheetName); // Gunakan PenilaianImport

            Excel::import($import, $request->file('file'));

            $kriteriaTujuan = Kriteria::find($targetKriteriaId)->nama;
            return redirect()->route('penilaian')->with('berhasil', "Data penilaian berhasil diimport dari sheet '$sheetName' ke kriteria '$kriteriaTujuan'!");

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Baris " . $failure->row() . ": " . implode(", ", $failure->errors());
            }
            return redirect()->back()->withErrors($errorMessages)->withInput();
        } catch (\Exception $e) {
            \Log::error('Import Penilaian Error: ' . $e->getMessage());
            return redirect()->back()->withErrors(['file' => 'Terjadi kesalahan saat mengimport file: ' . $e->getMessage()]);
        }
    }



    // Method untuk membaca sheet dari file Excel
    public function getSheets(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
            $path = $request->file('file')->getRealPath();

            if (!$path) {
                 \Log::error("Gagal mendapatkan path sementara file.");
                 return response()->json([
                     'success' => false,
                     'message' => 'Gagal mendapatkan path file.'
                 ], 500);
            }

            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($path);
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($path);

            $sheetNames = $spreadsheet->getSheetNames();

            return response()->json([
                'success' => true,
                'sheets' => $sheetNames,
            ]);

        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            \Log::error('Gagal membaca sheet (Spreadsheet Reader): ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'File Excel tidak valid atau rusak: ' . $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            \Log::error('Gagal membaca sheet (Umum): ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan internal saat membaca file: ' . $e->getMessage(),
            ], 500);
        }
    }


}
