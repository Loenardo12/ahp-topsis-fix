<?php

namespace App\Http\Controllers;

use App\Models\AbsenKelas10;
use App\Models\IsiKelas10;
use App\Models\Kelas10;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;

class ImportAbsenController extends Controller
{
    // Menampilkan form import
    public function showForm()
    {

        // return view('dashboard.kelas.kelas10.isikelas10.import', compact('kelas10'));
        return view('dashboard.kelas.kelas10.isikelas10.import');
    }

    // Mendapatkan daftar sheet dari file yang diupload
     public function getSheets(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls', // Validasi file
        ]);

        try {
            $file = $request->file('file_excel');
            $path = $file->getRealPath();
            $reader = IOFactory::createReader('Xlsx'); // Gunakan 'Xls' jika file .xls
            $reader->setReadDataOnly(true); // Hanya baca data, abaikan format
            $spreadsheet = $reader->load($path);

            $sheetNames = $spreadsheet->getSheetNames();
            \Log::info('Daftar Sheet yang diambil: ', $sheetNames); // Log untuk debugging
            return response()->json(['success' => true, 'sheets' => $sheetNames]);

        } catch (\Exception $e) {
            \Log::error('Error saat membaca sheet: ' . $e->getMessage()); // Log error
            return response()->json(['success' => false, 'message' => 'Gagal membaca file: ' . $e->getMessage()]);
        }
    }

    // Memproses import data dari sheet yang dipilih
public function processImport(Request $request)
{
    $request->validate([
        'file_excel' => 'required|file|mimes:xlsx,xls',
        'selected_sheet' => 'required|string',
        'kelas10_id' => 'required|exists:modelkelas10s,id',
        'semester' => 'required|in:1,2',
        'bulan' => 'required|string',
        'tahun' => 'required|integer',
    ]);

    $file = $request->file('file_excel');
    $selectedSheetName = $request->selected_sheet;
    $kelasId = $request->kelas10_id;
    $semester = $request->semester;
    $bulan = $request->bulan;
    $tahun = $request->tahun;

    try {
        $path = $file->getRealPath();
        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($path);

        if (!$spreadsheet->sheetNameExists($selectedSheetName)) {
            return back()->withErrors(['selected_sheet' => 'Sheet yang dipilih tidak ditemukan dalam file.']);
        }

        $worksheet = $spreadsheet->getSheetByName($selectedSheetName);
        $rows = $worksheet->toArray();

        Log::info("Memproses sheet: $selectedSheetName, Jumlah baris: " . count($rows));

        // --- Cari Baris Header Utama DATA SISWA ---
        // Format yang dicari: URUT di A (indeks 0), INDUK di B (indeks 1), Tanggal 1 di E (indeks 4) dst.
        // atau bisa juga Induk di A (indeks 0), Nama di B (indeks 1), Tanggal 1 di D (indeks 3) dst.
        // Kita akan mencari header dengan toleransi spasi dan kapitalisasi.

        $headerRowIndex = -1;
        $nisnColIndex = -1;
        $namaColIndex = -1;
        $tanggalStartColIndex = -1;

        for ($i = 0; $i < count($rows); $i++) {
            $row = $rows[$i];
            if (empty($row)) continue; // Lewati baris kosong

            // Normalisasi nilai header: trim, lowercase
            $normalizedRow = array_map(function($value) {
                return strtolower(trim($value));
            }, $row);

            // Coba Format 1: URUT (A), INDUK (B), Tanggal 1 (E)
            if (isset($normalizedRow[0], $normalizedRow[1], $normalizedRow[4]) &&
                $normalizedRow[0] === 'urut' &&
                $normalizedRow[1] === 'induk' &&
                $normalizedRow[4] === '1') {
                $headerRowIndex = $i;
                $nisnColIndex = 1; // Kolom B
                $namaColIndex = 2; // Kolom C (asumsi nama setelah induk)
                $tanggalStartColIndex = 4; // Kolom E
                Log::info("Format URUT-INDUK-Nama-Tanggal ditemukan di baris $headerRowIndex (indeks array PHP).");
                break;
            }

            // Coba Format 2: INDUK (A), Nama (B), Tanggal 1 (D)
            if (isset($normalizedRow[0], $normalizedRow[1], $normalizedRow[3]) &&
                $normalizedRow[0] === 'induk' &&
                $normalizedRow[3] === '1') {
                $headerRowIndex = $i;
                $nisnColIndex = 0; // Kolom A
                $namaColIndex = 1; // Kolom B
                $tanggalStartColIndex = 3; // Kolom D
                Log::info("Format INDUK-Nama-Tanggal ditemukan di baris $headerRowIndex (indeks array PHP).");
                break;
            }

            // Coba Format 3: URUT (A), INDUK (B), (Kolom kosong), (Kolom kosong), Tanggal 1 (E)
            // Jika nama berada di kolom C, maka:
            if (isset($normalizedRow[0], $normalizedRow[1], $normalizedRow[4]) &&
                $normalizedRow[0] === 'urut' &&
                $normalizedRow[1] === 'induk' &&
                $normalizedRow[4] === '1') {
                // Ini sama dengan Format 1, tapi kita pastikan nama di C
                $headerRowIndex = $i;
                $nisnColIndex = 1; // Kolom B
                $namaColIndex = 2; // Kolom C
                $tanggalStartColIndex = 4; // Kolom E
                Log::info("Format URUT-INDUK-Nama-Tanggal (Nama di C) ditemukan di baris $headerRowIndex (indeks array PHP).");
                break;
            }

             // Coba Format 4: (Kolom kosong), INDUK (B), Nama (C), (Kolom kosong), Tanggal 1 (E)
             // Ini terlihat di file, header bisa berada di baris 9 (indeks 8) seperti:
             // |  | INDUK | NAMA SISWA | L/P | 1 | 2 | ... |
             // $row[1] = 'INDUK', $row[2] = 'NAMA SISWA', $row[4] = '1'
             // Tapi kita tidak mencari 'NAMA SISWA' secara eksplisit, kita asumsikan kolom setelah 'INDUK' adalah nama.
             // Jadi kita cari 'INDUK' dan '1' dengan jarak yang benar.
             // Jika 'INDUK' di indeks X, maka '1' harus di indeks X+3 (karena ada Nama dan L/P di antaranya).
             // Misalnya, 'INDUK' di indeks 1, maka '1' harus di indeks 4.
             if (isset($normalizedRow[1], $normalizedRow[4]) &&
                 $normalizedRow[1] === 'induk' &&
                 $normalizedRow[4] === '1') {
                 $headerRowIndex = $i;
                 $nisnColIndex = 1; // Kolom B
                 $namaColIndex = 2; // Kolom C (asumsi setelah INDUK)
                 $tanggalStartColIndex = 4; // Kolom E
                 Log::info("Format INDUK-Nama-L/P-Tanggal ditemukan di baris $headerRowIndex (indeks array PHP).");
                 break;
             }

             // Coba Format 5: (Kolom kosong), (Kolom kosong), INDUK (C), Nama (D), (Kolom kosong), Tanggal 1 (G)
             // Jika 'INDUK' di indeks 2, maka '1' harus di indeks 5.
             if (isset($normalizedRow[2], $normalizedRow[5]) &&
                 $normalizedRow[2] === 'induk' &&
                 $normalizedRow[5] === '1') {
                 $headerRowIndex = $i;
                 $nisnColIndex = 2; // Kolom C
                 $namaColIndex = 3; // Kolom D
                 $tanggalStartColIndex = 5; // Kolom G
                 Log::info("Format (Kosong)-(Kosong)-INDUK-Nama-Tanggal ditemukan di baris $headerRowIndex (indeks array PHP).");
                 break;
             }

             // Coba Format 6: (Kolom kosong), (Kolom kosong), (Kolom kosong), (Kolom kosong), Tanggal 1 (E), dst
             // Dan di baris sebelumnya atau beberapa baris sebelumnya ada 'INDUK' dan 'NAMA SISWA'
             // Kita cari baris yang mengandung 'INDUK' dan 'NAMA SISWA' secara umum, lalu cari header tanggal di baris berikutnya
             // Kita fokus ke Format yang paling umum dilihat di file: INDUK di B, Nama di C, Tanggal 1 di E
             // Jadi, cari 'INDUK' di B dan '1' di E, atau 'URUT' di A, 'INDUK' di B, '1' di E
             // Kita sudah coba 1-6, mari coba kombinasi lain yang lebih umum berdasarkan file.
             // Format dari file: Baris 9 (indeks 8) sering berisi:
             // | URUT | INDUK | (Kosong) | (Kosong) | 1 | 2 | ... | 31 | S | I | A |
             // Atau
             // | (Kosong) | INDUK | NAMA SISWA | L/P | 1 | 2 | ... | 31 | S | I | A |
             // Mari coba kombinasi umum lagi.
             // Format: (Kosong/isi), INDUK, Nama, L/P, 1, 2, ...
             // Jadi, cari 'INDUK' di indeks X, '1' di indeks X+3
             // Contoh: 'INDUK' di indeks 1, '1' di indeks 4 (sudah dicoba di atas)
             // Contoh: 'INDUK' di indeks 2, '1' di indeks 5 (sudah dicoba di atas)
             // Contoh: 'INDUK' di indeks 0, '1' di indeks 3 (sudah dicoba di Format 2: INDUK-Nama-Tanggal)
             // Format: URUT, INDUK, (Kosong), (Kosong), 1, ...
             // Jadi, 'URUT' di 0, 'INDUK' di 1, '1' di 4 (sudah dicoba di Format 1)
             // Mari coba cari 'NAMA SISWA' dan '1' secara eksplisit sebagai alternatif, meskipun tidak di kolom yang sama.
             // Tidak, kita fokus ke INDUK dan Tanggal 1.
             // Mari coba cari kolom 'INDUK' dan kolom '1' secara dinamis dalam baris yang sama.
             // Loop melalui kolom dalam baris ini untuk mencari 'induk' dan '1'
             $indukIndex = array_search('induk', $normalizedRow);
             $tanggalSatuIndex = array_search('1', $normalizedRow);

             if ($indukIndex !== false && $tanggalSatuIndex !== false) {
                 // Cek apakah jarak antara 'induk' dan '1' masuk akal (misalnya 2 atau 3 kolom)
                 // Format umum: INDUK, Nama, L/P, 1 -> jarak 2 (1 kolom nama, 1 kolom L/P)
                 // Format umum: URUT, INDUK, Nama, L/P, 1 -> jarak 3 (1 kolom URUT, 1 kolom nama, 1 kolom L/P)
                 $jarak = $tanggalSatuIndex - $indukIndex;
                 if ($jarak === 2) {
                     // Format: INDUK, Nama, L/P, 1
                     $headerRowIndex = $i;
                     $nisnColIndex = $indukIndex;
                     $namaColIndex = $indukIndex + 1;
                     $tanggalStartColIndex = $tanggalSatuIndex;
                     Log::info("Format Dinamis (INDUK-Nama-L/P-1) ditemukan di baris $headerRowIndex (indeks array PHP), jarak=$jarak.");
                     break;
                 } elseif ($jarak === 3) {
                     // Format: URUT, INDUK, Nama, L/P, 1
                     $headerRowIndex = $i;
                     $nisnColIndex = $indukIndex;
                     $namaColIndex = $indukIndex + 1;
                     $tanggalStartColIndex = $tanggalSatuIndex;
                     Log::info("Format Dinamis (URUT-INDUK-Nama-L/P-1) ditemukan di baris $headerRowIndex (indeks array PHP), jarak=$jarak.");
                     break;
                 } elseif ($jarak === 1) {
                     // Format: INDUK, 1 (jarak 1, mungkin tidak umum, tapi coba)
                     $headerRowIndex = $i;
                     $nisnColIndex = $indukIndex;
                     $namaColIndex = $indukIndex + 1; // Asumsi nama setelah induk
                     $tanggalStartColIndex = $tanggalSatuIndex;
                     Log::info("Format Dinamis (INDUK-1) ditemukan di baris $headerRowIndex (indeks array PHP), jarak=$jarak.");
                     break;
                 }
                 // Tambahkan kombinasi lain jika diperlukan
             }

        }

        if ($headerRowIndex === -1) {
            Log::warning("Pencarian header selesai, tidak ditemukan. Menampilkan 10 baris pertama untuk debugging:");
            for ($i = 0; $i < min(10, count($rows)); $i++) {
                Log::debug("Baris $i: " . json_encode($rows[$i]));
            }
            return back()->withErrors(['file_excel' => 'Header utama (URUT/INDUK, 1) tidak ditemukan dalam sheet yang dipilih. Pastikan struktur file benar.']);
        }

        $processedCount = 0;
        $errorMessages = [];

        // Loop melalui data siswa (mulai dari baris setelah header)
        for ($i = $headerRowIndex + 1; $i < count($rows); $i++) {
            $rowData = $rows[$i];

            // Ambil NISN dari kolom yang sudah diidentifikasi
            $nisn = trim($rowData[$nisnColIndex] ?? '');

            // Ambil Nama dari kolom yang sudah diidentifikasi
            $nama = trim($rowData[$namaColIndex] ?? '');

            // --- KONDISI UNTUK BERHENTI ---
            // Jika nama kosong, cek apakah baris ini adalah informasi akhir seperti 'JUMLAH TIDAK MASUK PER HARI' atau 'Mengetahui'
            if (empty($nama)) {
                $firstCell = trim($rowData[0] ?? '');
                if (strpos(strtoupper($firstCell), 'JUMLAH') !== false || strpos(strtoupper($firstCell), 'MENGETAHUI') !== false || strpos(strtoupper($firstCell), 'KEPALA SMA') !== false) {
                     Log::info("Mencapai baris akhir: '$firstCell'. Menghentikan pemrosesan di baris $i.");
                     break;
                }
                // Lewati baris kosong atau baris tanpa nama
                continue;
            }

            Log::debug("Memproses siswa: '$nama', NISN: '$nisn' (Baris " . ($i + 1) . ")");

            // Cari siswa di database berdasarkan nama dan kelas
            $siswa = IsiKelas10::where('modelkelas10s_id', $kelasId)
                             ->where('nama', $nama)
                             ->first();

            if (!$siswa) {
                Log::warning("Siswa '$nama' tidak ditemukan di kelas $kelasId. Membuat entri baru.");
                $siswa = IsiKelas10::create([
                    'modelkelas10s_id' => $kelasId,
                    'nama' => $nama,
                    'nisn' => $nisn ?: null, // Isi dengan null jika kosong
                ]);
            } else {
                // Update NISN jika berbeda
                if ($siswa->nisn !== $nisn) {
                    Log::info("Memperbarui NISN siswa '$nama' dari '{$siswa->nisn}' menjadi '$nisn'.");
                    $siswa->update(['nisn' => $nisn ?: null]);
                }
            }

            // Ambil data absensi harian (tanggal 1 - 31) dari kolom yang sudah diidentifikasi
            // Ambil data absensi harian (tanggal 1 - 31) dari kolom yang sudah diidentifikasi
$absensiData = [];
$total_s = 0;
$total_i = 0;
$total_a = 0;
$total_h = 0;

for ($day = 1; $day <= 31; $day++) {
    $colIndex = $tanggalStartColIndex + ($day - 1);
    $nilai = isset($rowData[$colIndex]) ? trim($rowData[$colIndex]) : null;

    $nilaiValid = null;
    if (in_array(strtoupper($nilai), ['S', 'I', 'A', 'H'])) {
        $nilaiValid = strtoupper($nilai);
    } elseif (empty($nilai)) {
        $nilaiValid = 'H'; // <-- Isi dengan 'H' jika kosong
    } else {
        $errorMessages[] = "Baris " . ($i + 1) . " (Siswa: '$nama'): Tanggal $day, Nilai '$nilai' tidak valid. Harus S, I, A, H, atau kosong.";
        continue; // Lewati tanggal ini
    }

    $absensiData["tanggal_$day"] = $nilaiValid; // Nilai sekarang bisa 'S', 'I', 'A', 'H', atau 'H' default

    if ($nilaiValid === 'S') $total_s++;
    if ($nilaiValid === 'I') $total_i++;
    if ($nilaiValid === 'A') $total_a++;
    if ($nilaiValid === 'H') $total_h++; // Termasuk default 'H'
}

            // Cek apakah data absensi bulan ini sudah ada untuk siswa ini
            $existingAbsen = AbsenKelas10::where('isi_kelas10_id', $siswa->id)
                                          ->where('bulan', $bulan)
                                          ->where('tahun', $tahun)
                                          ->first();

            if ($existingAbsen) {
                Log::info("Memperbarui data absensi untuk siswa '$nama', bulan $bulan, tahun $tahun.");
                $existingAbsen->update(array_merge([
                    'semester' => $semester,
                    'total_s' => $total_s,
                    'total_i' => $total_i,
                    'total_a' => $total_a,
                    'total_h' => $total_h,
                ], $absensiData));
            } else {
                Log::info("Membuat data absensi baru untuk siswa '$nama', bulan $bulan, tahun $tahun.");
                AbsenKelas10::create(array_merge([
                    'isi_kelas10_id' => $siswa->id,
                    'semester' => $semester,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'total_s' => $total_s,
                    'total_i' => $total_i,
                    'total_a' => $total_a,
                    'total_h' => $total_h,
                ], $absensiData));
            }

            $processedCount++;
        }

        if (!empty($errorMessages)) {
            Log::warning("Error saat import: " . implode("\n", $errorMessages));
            return back()->withErrors(['file_excel' => $errorMessages])->withInput();
        }

        Log::info("Import selesai. $processedCount data siswa diproses untuk bulan $bulan tahun $tahun.");
        return redirect()->route('kelas10.show', $kelasId)->with('success', "Import berhasil! $processedCount data siswa diproses untuk bulan $bulan tahun $tahun.");

    } catch (\Exception $e) {
        Log::error('Import Error: ' . $e->getMessage());
        return back()->withErrors(['file_excel' => 'Terjadi kesalahan saat memproses file: ' . $e->getMessage()]);
    }
}
}
