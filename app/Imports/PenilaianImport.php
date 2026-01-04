<?php

namespace App\Imports;

use App\Models\Objek; // Model untuk nama siswa
use App\Models\Kriteria; // Model untuk nama kriteria
use App\Models\Penilaian; // Model untuk menyimpan nilai
use App\Models\Alternatif; // Model untuk mendapatkan alternatif_id
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection; // Ganti WithStartRow
use Maatwebsite\Excel\Concerns\WithMultipleSheets; // Tetap gunakan ini
use Illuminate\Support\Facades\DB; // Untuk transaksi database
use Maatwebsite\Excel\Concerns\Importable; // Tambahkan trait ini
use Illuminate\Support\Facades\Log; // Untuk logging

class PenilaianImport implements ToCollection, WithMultipleSheets // Ganti interface
{
    use Importable; // Gunakan trait

    private $targetKriteriaId;
    private $targetSheetName;

    public function __construct($targetKriteriaId, $sheetName = null) // Konstruktor menerima sheetName opsional
    {
        $this->targetKriteriaId = $targetKriteriaId;
        $this->targetSheetName = $sheetName;
        Log::info("PenilaianImport: Konstruktor dipanggil, targetKriteriaId: $targetKriteriaId, targetSheetName: " . ($this->targetSheetName ?? 'NULL'));
    }

    // Implementasi WithMultipleSheets
    public function sheets(): array
    {
        Log::info("PenilaianImport: sheets() dipanggil. Mencari sheet: " . ($this->targetSheetName ?? 'ALL'));
        if ($this->targetSheetName) {
            Log::info("PenilaianImport: Mengembalikan handler untuk sheet: " . $this->targetSheetName);
            return [
                $this->targetSheetName => $this, // Gunakan instance ini untuk sheet yang dipilih
            ];
        }
        Log::warning("PenilaianImport: Tidak ada sheet spesifik ditentukan di sheets().");
        return [];
    }

    // Gunakan ToCollection
    public function collection(Collection $rows)
    {
        // Ini akan dipanggil untuk sheet yang cocok dengan kunci di sheets()
        Log::info("PenilaianImport: collection dipanggil untuk sheet '" . $this->targetSheetName . "', jumlah baris: " . $rows->count());

        // Ambil baris mulai dari indeks 1 (lewati baris header pertama X.1| No|Kode|Nama|Rata-rata Nilai|)
        $dataRows = $rows->slice(1);

        Log::info("PenilaianImport: Jumlah data rows (setelah melewati baris header 1): " . $dataRows->count());

        // Filter baris yang benar-benar kosong atau hanya berisi header
        $filteredRows = $dataRows->filter(function ($row) {
            // Konversi row ke array jika belum
            $rowArray = $row->toArray();

            // Cek apakah baris hanya berisi null, string kosong, atau header
            $isEmpty = empty(array_filter($rowArray, function ($cell) {
                return $cell !== null && $cell !== '' && $cell !== ' ';
            }));

            $isHeader = isset($rowArray[2]) && $rowArray[2] === 'Nama' && isset($rowArray[3]) && $rowArray[3] === 'Rata-rata Nilai';

            // Return true jika baris TIDAK kosong DAN TIDAK berisi header
            return !$isEmpty && !$isHeader;
        });

        Log::info("PenilaianImport: Jumlah baris setelah filtering: " . $filteredRows->count());

        // Ambil kriteria tujuan sekali saja untuk efisiensi
        $kriteria = Kriteria::find($this->targetKriteriaId);

        if (!$kriteria) {
            $errorMessage = "Kriteria dengan ID {$this->targetKriteriaId} tidak ditemukan di database.";
            Log::error("PenilaianImport: " . $errorMessage);
            throw new \Exception($errorMessage);
        }

        // Gunakan transaksi untuk memastikan semua data masuk atau tidak ada yang masuk jika terjadi error
        DB::transaction(function () use ($filteredRows, $kriteria) {
            $processedCount = 0; // Counter untuk baris yang diproses
            $skippedCount = 0; // Counter untuk baris yang dilewati
            foreach ($filteredRows as $index => $rowArray) {
                Log::debug("PenilaianImport: Memproses filtered row ke-" . ($index + 1) . ", data: " . json_encode($rowArray));

                // $rowArray adalah array numerik [0 => val, 1 => val, 2 => val, 3 => val, ...]
                // Kolom C (Nama) adalah indeks 2
                // Kolom D (Rata-rata Nilai) adalah indeks 3
                // Pastikan array memiliki indeks yang cukup dan bukan header lagi
                if (!isset($rowArray[2]) || !isset($rowArray[3])) {
                    Log::warning("PenilaianImport: Baris ke-" . ($index + 1) . " (dari array) tidak memiliki kolom C (Nama) atau D (Rata-rata Nilai), dilewati: " . json_encode($rowArray));
                    $skippedCount++;
                    continue;
                }

                $namaSiswa = trim($rowArray[2]); // Ambil dari kolom C (indeks 2) dan trim
                $nilaiAsli = $rowArray[3];       // Ambil dari kolom D (indeks 3)

                Log::debug("PenilaianImport: Nama ditemukan: '$namaSiswa', Nilai: '$nilaiAsli'");

                if (empty($namaSiswa)) {
                    Log::warning("PenilaianImport: Nama siswa kosong pada row ke-" . ($index + 1) . " (dari array): " . json_encode($rowArray) . ". Baris dilewati.");
                    $skippedCount++;
                    continue;
                }

                // Cari objek (siswa) berdasarkan nama
                $objek = Objek::where('nama', $namaSiswa)->first();
                Log::debug("PenilaianImport: Query untuk nama '$namaSiswa', hasil: " . ($objek ? 'DITEMUKAN' : 'TIDAK DITEMUKAN'));

                if (!$objek) {
                    Log::warning("PenilaianImport: Siswa dengan nama '$namaSiswa' tidak ditemukan di database. Baris dilewati.");
                    $skippedCount++;
                    continue;
                }

                // Ambil alternatif_id dari objek
                $alternatif = $objek->alternatif()->first();
                Log::debug("PenilaianImport: Alternatif untuk '$namaSiswa', hasil: " . ($alternatif ? 'DITEMUKAN' : 'TIDAK DITEMUKAN'));

                if (!$alternatif) {
                     Log::warning("PenilaianImport: Alternatif untuk siswa '$namaSiswa' tidak ditemukan di database. Baris dilewati.");
                     $skippedCount++;
                     continue;
                }

                // Pastikan nilai adalah angka antara 0 dan 100
                $nilaiAsliInt = filter_var($nilaiAsli, FILTER_VALIDATE_INT, ["options" => ["min_range" => 0, "max_range" => 100]]);
                Log::debug("PenilaianImport: Nilai asli '$nilaiAsli' difilter, hasil: " . var_export($nilaiAsliInt, true));

                if ($nilaiAsliInt === false || $nilaiAsliInt === null) {
                    Log::warning("PenilaianImport: Nilai '$nilaiAsli' untuk siswa '$namaSiswa' bukan angka valid (0-100). Baris dilewati.");
                    $skippedCount++;
                    continue;
                }

                // Simpan atau update nilai penilaian untuk kriteria yang ditentukan
                $existingRecord = Penilaian::where('alternatif_id', $alternatif->id)->where('kriteria_id', $kriteria->id)->first();
                $operation = $existingRecord ? 'UPDATE' : 'INSERT';

                $penilaian = Penilaian::updateOrCreate(
                    [
                        'alternatif_id' => $alternatif->id,
                        'kriteria_id' => $kriteria->id,
                    ],
                    [
                        'nilai_asli' => $nilaiAsliInt,
                    ]
                );

                Log::info("PenilaianImport: [$operation] nilai: Siswa='$namaSiswa', Kriteria='{$kriteria->nama}', Nilai=$nilaiAsliInt, Alternatif ID={$alternatif->id}");
                $processedCount++;
            }
            Log::info("PenilaianImport: Proses selesai. Baris diproses: $processedCount, Baris dilewati: $skippedCount");
        });
    }
}
