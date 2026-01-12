<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\PDF;
use App\Http\Services\TopsisService;
use App\Http\Services\KriteriaService;
use App\Http\Services\PenilaianService;

class TopsisController extends Controller
{
    protected $topsisServices, $penilaianService, $kriteriaService;

    public function __construct(TopsisService $topsisServices, PenilaianService $penilaianService, KriteriaService $kriteriaService)
    {
        $this->topsisServices = $topsisServices;
        $this->penilaianService = $penilaianService;
        $this->kriteriaService = $kriteriaService;
    }

    public function hasilAkhir()
    {
        $judul = "Hasil Akhir";
        $hasilTopsis = $this->topsisServices->getHasilTopsis();

        return view('dashboard.hasil_akhir.index', [
            'judul' => $judul,
            'hasilTopsis' => $hasilTopsis,
        ]);
    }
    public function hasilperhitunganAkhir()
    {
        $judul = "Hasil Perhitungan Akhir";
        $hasilTopsis = $this->topsisServices->getHasilTopsis();

        return view('dashboard.hasil_akhir.hasilperhitungan', [
            'judul' => $judul,
            'hasilTopsis' => $hasilTopsis,
        ]);
    }
    public function index()
    {
        $judul = "Perhitungan";

        $kriteria = $this->kriteriaService->getAll();
        $penilaian = $this->penilaianService->getAll();
        $matriksKeputusan = $this->topsisServices->getMatriksKeputusan();
        $matriksNormalisasi = $this->topsisServices->getMatriksNormalisasi();
        $matriksY = $this->topsisServices->getMatriksY();
        $solusiIdealPositif = $this->topsisServices->getSolusiIdealPositif();
        $solusiIdealNegatif = $this->topsisServices->getSolusiIdealNegatif();
        $idealPositif = $this->topsisServices->getIdealPositif();
        $idealNegatif = $this->topsisServices->getIdealNegatif();
        $hasilTopsis = $this->topsisServices->getHasilTopsis();

        return view('dashboard.perhitungan.index', [
            'judul' => $judul,
            'kriteria' => $kriteria,
            'penilaian' => $penilaian,
            'matriksKeputusan' => $matriksKeputusan,
            'matriksNormalisasi' => $matriksNormalisasi,
            'matriksY' => $matriksY,
            'idealPositif' => $idealPositif,
            'idealNegatif' => $idealNegatif,
            'solusiIdealPositif' => $solusiIdealPositif,
            'solusiIdealNegatif' => $solusiIdealNegatif,
            'hasilTopsis' => $hasilTopsis,
        ]);
    }

    public function pdf_topsis()
    {
        $judul = 'Laporan Hasil TOPSIS';

        $kriteria = $this->kriteriaService->getAll();
        $penilaian = $this->penilaianService->getAll();
        $matriksKeputusan = $this->topsisServices->getMatriksKeputusan();
        $matriksNormalisasi = $this->topsisServices->getMatriksNormalisasi();
        $matriksY = $this->topsisServices->getMatriksY();
        $solusiIdealPositif = $this->topsisServices->getSolusiIdealPositif();
        $solusiIdealNegatif = $this->topsisServices->getSolusiIdealNegatif();
        $idealPositif = $this->topsisServices->getIdealPositif();
        $idealNegatif = $this->topsisServices->getIdealNegatif();
        $hasilTopsis = $this->topsisServices->getHasilTopsis();

        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadview('dashboard.pdf.perhitungan', [
            'judul' => $judul,
            'kriteria' => $kriteria,
            'penilaian' => $penilaian,
            'matriksKeputusan' => $matriksKeputusan,
            'matriksNormalisasi' => $matriksNormalisasi,
            'matriksY' => $matriksY,
            'idealPositif' => $idealPositif,
            'idealNegatif' => $idealNegatif,
            'solusiIdealPositif' => $solusiIdealPositif,
            'solusiIdealNegatif' => $solusiIdealNegatif,
            'hasilTopsis' => $hasilTopsis,
        ]);
         $pdf->setOption('isRemoteEnabled', true);
    $pdf->setOption('isHtml5ParserEnabled', true);
        // return $pdf->download('laporan-penilaian.pdf');
        return $pdf->stream();
    }

    public function pdf_hasil()
    {
        $judul = "Laporan Hasil Akhir";
        $hasilTopsis = $this->topsisServices->getHasilTopsis();

        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadview('dashboard.pdf.hasil_akhir', [
            'judul' => $judul,
            'hasilTopsis' => $hasilTopsis,
        ]);
         $pdf->setOption('isRemoteEnabled', true);
    $pdf->setOption('isHtml5ParserEnabled', true);
        // return $pdf->download('laporan-penilaian.pdf');
        return $pdf->stream();
    }

    public function hitungTopsis()
    {
        $this->hitungMatriksKeputusan();
        $this->hitungMatriksNormalisasi();
        $this->hitungMatriksY();
        $this->hitungIdeal();
        $this->hitungSolusiIdeal();
        $this->hitungHasil();
        return redirect('dashboard/perhitungan')->with('berhasil', "Perhitungan TOPSIS Selesai!");
    }

    public function hitungTopsisSetelahHapus()
    {
        $this->hitungMatriksKeputusan();
        $this->hitungMatriksNormalisasi();
        $this->hitungMatriksY();
        $this->hitungIdeal();
        $this->hitungSolusiIdeal();
        $this->hitungHasil();
    }

    public function hitungMatriksKeputusan()
    {
        // Ambil penilaian dengan relasi yang diperlukan
        $penilaian = $this->penilaianService->getAllWithRelations(); // Gunakan fungsi baru

        // Group by kriteria_id
        $penilaianPerKriteria = $penilaian->groupBy('kriteria_id');

        foreach ($penilaianPerKriteria as $kriteriaId => $penilaianKriteria) { // Gunakan $kriteriaId dari key loop
            $hitungMatriks = 0;

            foreach ($penilaianKriteria as $value) {
                // Gunakan nilai_asli jika tersedia, jika tidak, gunakan subKriteria->nilai, jika tidak juga, default ke 0
                $nilai = $value->nilai_asli ?? ($value->subKriteria ? $value->subKriteria->nilai : 0);

                // Validasi apakah nilai valid untuk perhitungan (bukan null atau string kosong jika default 0 tidak digunakan)
                if ($nilai === null || $nilai === '') {
                    \Log::warning("Nilai untuk alternatif_id {$value->alternatif_id}, kriteria_id {$kriteriaId} kosong. Diberi nilai default 0.");
                    $nilai = 0; // Atau handle sesuai kebijakan Anda
                }

                $hitungMatriks += pow($nilai, 2); // <-- Perbaikan: Gunakan $nilai, bukan $value->subKriteria->nilai
            }

            $hitungMatriks = sqrt($hitungMatriks);
            $data = [
                'kriteria_id' => $kriteriaId, // <-- Perbaikan: Gunakan $kriteriaId, bukan $value->kriteria_id dari loop dalam
                'nilai' => $hitungMatriks,
            ];

            $this->topsisServices->simpanMatriksKeputusan($data);
        }
    }


    public function hitungMatriksNormalisasi()
    {
        // Ambil penilaian dengan relasi
        $penilaian = $this->penilaianService->getAllWithRelations(); // Gunakan fungsi baru
        $matriksKeputusan = $this->topsisServices->getAllMatriksKeputusan(); // Ambil semua matriks keputusan

        foreach ($penilaian as $value) {
            // Cari nilai matriks keputusan untuk kriteria ini
            $matriksKeputusanItem = $matriksKeputusan->firstWhere('kriteria_id', $value->kriteria_id);

            if (!$matriksKeputusanItem || $matriksKeputusanItem->nilai == 0) {
                // Jika matriks keputusan untuk kriteria ini tidak ditemukan atau nilainya 0, hindari pembagian dengan nol
                \Log::warning("Matriks keputusan untuk kriteria {$value->kriteria_id} tidak ditemukan atau bernilai 0. Nilai normalisasi diatur ke 0.");
                $matriksNormalisasi = 0;
            } else {
                // Gunakan nilai_asli untuk pembilang
                $nilaiAsli = $value->nilai_asli ?? ($value->subKriteria ? $value->subKriteria->nilai : 0);
                if ($nilaiAsli === null || $nilaiAsli === '') {
                     \Log::warning("Nilai asli untuk alternatif_id {$value->alternatif_id}, kriteria_id {$value->kriteria_id} kosong. Diberi nilai default 0 untuk normalisasi.");
                     $nilaiAsli = 0;
                }
                // Perbaikan: Gunakan $matriksKeputusanItem->nilai, bukan $matriksKeputusanItem->nilai (yg salah)
                $matriksNormalisasi = $nilaiAsli / $matriksKeputusanItem->nilai; // <-- Pastikan $matriksKeputusanItem tidak null
            }

            $data = [
                'nilai' => $matriksNormalisasi,
                'kriteria_id' => $value->kriteria_id,
                'alternatif_id' => $value->alternatif_id,
            ];
            $this->topsisServices->simpanMatriksNormalisasi($data);
        }
    }

   public function hitungMatriksY()
    {
        // Ambil matriks normalisasi (R) dari proses sebelumnya, yang sekarang berisi nilai dari nilai_asli
        $matriksNormalisasi = $this->topsisServices->getMatriksNormalisasi();
        foreach ($matriksNormalisasi->unique('kriteria_id') as $item) {
            $matriksNormalisasiKriteria = $matriksNormalisasi->where('kriteria_id', $item->kriteria_id);
            // Ambil bobot kriteria dari model Kriteria
            $bobotKriteria = $this->kriteriaService->getDataById($item->kriteria_id);

            foreach ($matriksNormalisasiKriteria as $value) {
                // Perhitungan Y = R * W (bobot kriteria)
                // $value->nilai sekarang adalah nilai dari normalisasi R, yang berasal dari nilai_asli
                $matriksY = $value->nilai * $bobotKriteria->bobot; // <-- Gunakan bobot dari Kriteria, bukan SubKriteria
                $data = [
                    'nilai' => $matriksY,
                    'kriteria_id' => $value->kriteria_id,
                    'alternatif_id' => $value->alternatif_id,
                ];
                $this->topsisServices->simpanMatriksY($data);
            }
        }
    }

    public function hitungIdeal()
    {
        $solusiIdeal = $this->topsisServices->getMatriksY();
        foreach ($solusiIdeal->unique('kriteria_id') as $item) {
            $solusiIdealKriteria = $solusiIdeal->where('kriteria_id', $item->kriteria_id);

            $solusiIdealA = [];
            foreach ($solusiIdealKriteria as $value) {
                $solusiIdealA[] = $value->nilai;
            }
            $solusiIdealPositif = ['nilai' => max($solusiIdealA), 'kriteria_id' => $item->kriteria_id];
            $solusiIdealNegatif = ['nilai' => min($solusiIdealA), 'kriteria_id' => $item->kriteria_id];

            foreach ($solusiIdealKriteria as $value) {
                $idealPositif = pow($value->nilai - $solusiIdealPositif['nilai'], 2);
                $dataPositif = [
                    'nilai' => $idealPositif,
                    'kriteria_id' => $value->kriteria_id,
                    'alternatif_id' => $value->alternatif_id,
                ];
                $this->topsisServices->simpanIdealPositif($dataPositif);

                $idealNegatif = pow($value->nilai - $solusiIdealNegatif['nilai'], 2);
                $dataNegatif = [
                    'nilai' => $idealNegatif,
                    'kriteria_id' => $value->kriteria_id,
                    'alternatif_id' => $value->alternatif_id,
                ];
                $this->topsisServices->simpanIdealNegatif($dataNegatif);
            }
        }
    }

    public function hitungSolusiIdeal()
    {
        $jarakIdealPositif = $this->topsisServices->getIdealPositif();
        $jarakIdealNegatif = $this->topsisServices->getIdealNegatif();

        foreach ($jarakIdealPositif as $item) {
            $jarakIdealPositifSi = $jarakIdealPositif->where('alternatif_id', $item->alternatif_id);
            $nilaiPositifSi = 0;

            foreach ($jarakIdealPositifSi as $value) {
                $nilaiPositifSi += $value->nilai;
            }
            $data = [
                'nilai' => sqrt($nilaiPositifSi),
                'alternatif_id' => $item->alternatif_id,
            ];
            $this->topsisServices->simpanSolusiIdealPositif($data);
        }

        foreach ($jarakIdealNegatif as $item) {
            $jarakIdealNegatifSi = $jarakIdealNegatif->where('alternatif_id', $item->alternatif_id);
            $nilaiNegatifSi = 0;

            foreach ($jarakIdealNegatifSi as $value) {
                $nilaiNegatifSi += $value->nilai;
            }
            $data = [
                'nilai' => sqrt($nilaiNegatifSi),
                'alternatif_id' => $item->alternatif_id,
            ];
            $this->topsisServices->simpanSolusiIdealNegatif($data);
        }
    }

    public function hitungHasil()
    {
        $solusiIdealPositif = $this->topsisServices->getSolusiIdealPositif();
        $solusiIdealNegatif = $this->topsisServices->getSolusiIdealNegatif();

        $dataPositif = [];
        $dataNegatif = [];
        $hitung = [];

        foreach ($solusiIdealPositif as $item) {
            $dataPositif[] = [
                'alternatif_id' => $item->alternatif_id,
                'nilai' => $item->nilai,
            ];
        }

        foreach ($solusiIdealNegatif as $item) {
            $dataNegatif[] = [
                'alternatif_id' => $item->alternatif_id,
                'nilai' => $item->nilai,
            ];
        }

        foreach ($dataPositif as $item) {
            foreach ($dataNegatif as $value) {
                if ($value['alternatif_id'] == $item['alternatif_id']) {
                    $hitung = [
                        'alternatif_id' => $item['alternatif_id'],
                        'nilai' => $value['nilai'] / ($item['nilai'] + $value['nilai']),
                    ];
                }
            }
            $this->topsisServices->simpanHasilTopsis($hitung);
            $hitung = [];
        }
    }

}
