<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection; // Ganti WithStartRow
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet; // Tambahkan ini
use Illuminate\Support\Facades\Log;

class DebugImport implements ToCollection, WithMultipleSheets // Ganti interface
{
    private $targetSheetName;

    public function __construct($sheetName = null)
    {
        $this->targetSheetName = $sheetName;
        Log::info("DebugImport: Konstruktor dipanggil, targetSheetName: " . ($this->targetSheetName ?? 'NULL'));
    }

    // Tidak perlu WithStartRow atau startRow()

    public function sheets(): array
    {
        Log::info("DebugImport: sheets() dipanggil. Mencari sheet: " . ($this->targetSheetName ?? 'ALL'));
        if ($this->targetSheetName) {
            Log::info("DebugImport: Mengembalikan handler untuk sheet: " . $this->targetSheetName);
            return [
                $this->targetSheetName => $this, // Gunakan instance ini untuk sheet yang dipilih
            ];
        }
        Log::warning("DebugImport: Tidak ada sheet spesifik ditentukan di sheets().");
        return [];
    }

    // Gunakan ToCollection
    public function collection(Collection $rows)
    {
        // $rows sekarang berisi semua baris dari sheet, termasuk header
        Log::info("DebugImport: collection dipanggil, JUMLAH TOTAL BARIS DI SHEET: " . $rows->count());

        // Ambil baris mulai dari indeks 1 (karena indeks 0 adalah baris 1)
        $dataRows = $rows->slice(1); // Lewati baris 1 (header aneh)

        Log::info("DebugImport: Jumlah data rows (setelah melewati baris 1): " . $dataRows->count());

        Log::info("DebugImport: Isi data rows (dari baris 2 ke bawah):");
        foreach ($dataRows as $index => $row) {
            // $index sekarang dimulai dari 0, merepresentasikan baris 2, 3, 4, ... di Excel
            Log::info("DebugImport: Baris Excel " . ($index + 2) . " (Array index " . $index . "): " . json_encode($row->toArray()));
        }
    }
}
