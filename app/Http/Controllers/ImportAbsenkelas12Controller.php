<?php

namespace App\Http\Controllers;

use App\Models\Absenkelas12;
use App\Models\Isikelas12;
use App\Models\kelas12;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;

class ImportAbsenkelas12Controller extends Controller
{
    // Menampilkan form import — terima kelas12 via Route Model Binding
public function showForm(Request $request)
{
    $kelas12_id = $request->query('kelas12_id');

    if (!$kelas12_id || !is_numeric($kelas12_id)) {
        return redirect()->route('dashboard')->withErrors('ID kelas tidak valid.');
    }

    $kelas12 = kelas12::where('id', $kelas12_id)->first();

    if (!$kelas12) {
        return redirect()->back()->withErrors('Kelas tidak ditemukan.');
    }

    return view('dashboard.kelas.kelas12.isikelas12.import', compact('kelas12'));
}

    // Mendapatkan daftar sheet dari file yang diupload
    public function getSheets(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls',
        ]);

        try {
            $file = $request->file('file_excel');
            $path = $file->getRealPath();

            // Deteksi tipe file secara otomatis
            $extension = strtolower($file->getClientOriginalExtension());
            if ($extension === 'xls') {
                $reader = IOFactory::createReader('Xls');
            } else {
                $reader = IOFactory::createReader('Xlsx');
            }

            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($path);

            $sheetNames = $spreadsheet->getSheetNames();
            Log::info('Daftar Sheet yang diambil: ', $sheetNames);
            return response()->json(['success' => true, 'sheets' => $sheetNames]);

        } catch (\Exception $e) {
            Log::error('Error saat membaca sheet: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membaca file Excel. Pastikan format file benar dan tidak rusak.'
            ]);
        }
    }

    // Memproses import data dari sheet yang dipilih
    public function processImport(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls',
            'selected_sheet' => 'required|string',
            'kelas12_id' => 'required|exists:modelkelas12s,id',
            'semester' => 'required|in:1,2',
            'bulan' => 'required|string',
            'tahun' => 'required|integer|min:2000|max:2030',
        ]);

        $file = $request->file('file_excel');
        $selectedSheetName = $request->selected_sheet;
        $kelasId = $request->kelas12_id;
        $semester = $request->semester;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        try {
            $path = $file->getRealPath();

            // Deteksi reader berdasarkan ekstensi
            $extension = strtolower($file->getClientOriginalExtension());
            if ($extension === 'xls') {
                $reader = IOFactory::createReader('Xls');
            } else {
                $reader = IOFactory::createReader('Xlsx');
            }

            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($path);

            if (!$spreadsheet->sheetNameExists($selectedSheetName)) {
                return back()->withErrors(['selected_sheet' => 'Sheet yang dipilih tidak ditemukan dalam file.']);
            }

            $worksheet = $spreadsheet->getSheetByName($selectedSheetName);
            $rows = $worksheet->toArray();

            Log::info("Memproses sheet: $selectedSheetName, Jumlah baris: " . count($rows));

            // === DETEKSI HEADER (sama seperti sebelumnya, tidak diubah) ===
            $headerRowIndex = -1;
            $nisnColIndex = -1;
            $namaColIndex = -1;
            $tanggalStartColIndex = -1;

            for ($i = 0; $i < count($rows); $i++) {
                $row = $rows[$i];
                if (empty($row)) continue;

                $normalizedRow = array_map(function($value) {
                    return strtolower(trim($value));
                }, $row);

                // Format 1: URUT, INDUK, ..., 1
                if (isset($normalizedRow[0], $normalizedRow[1], $normalizedRow[4]) &&
                    $normalizedRow[0] === 'urut' &&
                    $normalizedRow[1] === 'induk' &&
                    $normalizedRow[4] === '1') {
                    $headerRowIndex = $i;
                    $nisnColIndex = 1;
                    $namaColIndex = 2;
                    $tanggalStartColIndex = 4;
                    Log::info("Format URUT-INDUK ditemukan di baris $headerRowIndex.");
                    break;
                }

                // Format 2: INDUK, Nama, ..., 1
                if (isset($normalizedRow[0], $normalizedRow[1], $normalizedRow[3]) &&
                    $normalizedRow[0] === 'induk' &&
                    $normalizedRow[3] === '1') {
                    $headerRowIndex = $i;
                    $nisnColIndex = 0;
                    $namaColIndex = 1;
                    $tanggalStartColIndex = 3;
                    Log::info("Format INDUK-Nama ditemukan di baris $headerRowIndex.");
                    break;
                }

                // Format 4: (kosong), INDUK, Nama, L/P, 1
                if (isset($normalizedRow[1], $normalizedRow[4]) &&
                    $normalizedRow[1] === 'induk' &&
                    $normalizedRow[4] === '1') {
                    $headerRowIndex = $i;
                    $nisnColIndex = 1;
                    $namaColIndex = 2;
                    $tanggalStartColIndex = 4;
                    Log::info("Format INDUK di B, Tanggal di E ditemukan di baris $headerRowIndex.");
                    break;
                }

                // Format 5: (kosong)x2, INDUK, Nama, ..., 1
                if (isset($normalizedRow[2], $normalizedRow[5]) &&
                    $normalizedRow[2] === 'induk' &&
                    $normalizedRow[5] === '1') {
                    $headerRowIndex = $i;
                    $nisnColIndex = 2;
                    $namaColIndex = 3;
                    $tanggalStartColIndex = 5;
                    Log::info("Format INDUK di C ditemukan di baris $headerRowIndex.");
                    break;
                }

                // Deteksi dinamis
                $indukIndex = array_search('induk', $normalizedRow);
                $tanggalSatuIndex = array_search('1', $normalizedRow);

                if ($indukIndex !== false && $tanggalSatuIndex !== false) {
                    $jarak = $tanggalSatuIndex - $indukIndex;
                    if (in_array($jarak, [1, 2, 3])) {
                        $headerRowIndex = $i;
                        $nisnColIndex = $indukIndex;
                        $namaColIndex = $indukIndex + 1;
                        $tanggalStartColIndex = $tanggalSatuIndex;
                        Log::info("Format dinamis ditemukan di baris $headerRowIndex, jarak=$jarak.");
                        break;
                    }
                }
            }

            if ($headerRowIndex === -1) {
                Log::warning("Header tidak ditemukan. Menampilkan 5 baris pertama:");
                for ($i = 0; $i < min(5, count($rows)); $i++) {
                    Log::debug("Baris $i: " . json_encode($rows[$i]));
                }
                return back()->withErrors([
                    'file_excel' => 'Header absensi tidak ditemukan. Pastikan file memiliki kolom "INDUK" dan tanggal "1" pada baris yang sama.'
                ]);
            }

            // === PROSES DATA SISWA ===
            $processedCount = 0;
            $errorMessages = [];

            for ($i = $headerRowIndex + 1; $i < count($rows); $i++) {
                $rowData = $rows[$i];
                $nisn = trim($rowData[$nisnColIndex] ?? '');
                $nama = trim($rowData[$namaColIndex] ?? '');

                // Hentikan jika baris akhir
                if (empty($nama)) {
                    $firstCell = trim($rowData[0] ?? '');
                    if (preg_match('/(jumlah|mengetahui|kepala)/i', $firstCell)) {
                        Log::info("Menghentikan di baris akhir: '$firstCell'");
                        break;
                    }
                    continue;
                }

                // Cari atau buat siswa
                $siswa = Isikelas12::firstOrCreate(
                    ['modelkelas12s_id' => $kelasId, 'nama' => $nama],
                    ['nisn' => $nisn ?: null]
                );

                // Update NISN jika berbeda
                if ($siswa->nisn !== $nisn) {
                    $siswa->update(['nisn' => $nisn ?: null]);
                }

                // Proses absensi harian
                $absensiData = [];
                $total_s = $total_i = $total_a = $total_h = 0;

                for ($day = 1; $day <= 31; $day++) {
                    $colIndex = $tanggalStartColIndex + ($day - 1);
                    $nilai = isset($rowData[$colIndex]) ? trim($rowData[$colIndex]) : null;
                    $nilaiValid = null;

                    if (in_array(strtoupper($nilai), ['S', 'I', 'A', 'H'])) {
                        $nilaiValid = strtoupper($nilai);
                    } elseif (empty($nilai)) {
                        $nilaiValid = 'H';
                    } else {
                        $errorMessages[] = "Baris " . ($i + 1) . " (Siswa: '$nama'): Nilai tanggal $day ('$nilai') tidak valid.";
                        continue;
                    }

                    $absensiData["tanggal_$day"] = $nilaiValid;
                    switch ($nilaiValid) {
                        case 'S': $total_s++; break;
                        case 'I': $total_i++; break;
                        case 'A': $total_a++; break;
                        case 'H': $total_h++; break;
                    }
                }

                // Simpan atau update absensi
                Absenkelas12::updateOrCreate(
                    [
                        'isi_kelas12_id' => $siswa->id,
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                    ],
                    array_merge($absensiData, [
                        'semester' => $semester,
                        'total_s' => $total_s,
                        'total_i' => $total_i,
                        'total_a' => $total_a,
                        'total_h' => $total_h,
                    ])
                );

                $processedCount++;
            }

            if (!empty($errorMessages)) {
                Log::warning("Error validasi data: " . implode("\n", $errorMessages));
                return back()->withErrors(['file_excel' => $errorMessages])->withInput();
            }

            Log::info("Import berhasil: $processedCount siswa, bulan $bulan $tahun.");
            return redirect()
                ->route('kelas12.show', $kelasId)
                ->with('success', "Import berhasil! $processedCount data siswa diproses untuk bulan $bulan tahun $tahun.");

        } catch (\Exception $e) {
            Log::error('Import gagal: ' . $e->getMessage() . ' di ' . $e->getFile() . ':' . $e->getLine());
            return back()->withErrors([
                'file_excel' => 'Terjadi kesalahan sistem saat memproses file. Silakan coba lagi atau hubungi admin.'
            ]);
        }
    }
}
