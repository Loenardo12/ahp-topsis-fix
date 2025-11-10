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
            'kelas10_id' => 'required|exists:modelkelas10s,id', // Validasi ini mungkin perlu disesuaikan jika import bisa untuk kelas selain 10
        ]);

        $file = $request->file('file_excel');
        $selectedSheetName = $request->selected_sheet;
        $kelasId = $request->kelas10_id; // Gunakan $kelasId untuk konsistensi

        try {
            $path = $file->getRealPath();
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true); // Hanya baca nilai, abaikan format
            $spreadsheet = $reader->load($path);

            if (!$spreadsheet->sheetNameExists($selectedSheetName)) {
                return back()->withErrors(['selected_sheet' => 'Sheet yang dipilih tidak ditemukan dalam file.']);
            }

            $worksheet = $spreadsheet->getSheetByName($selectedSheetName);
            $rows = $worksheet->toArray();

            Log::info("Memproses sheet: $selectedSheetName, Jumlah baris: " . count($rows));

            // Cari baris header utama (NAMA SISWA di kolom D - indeks 3, baris 8 -> indeks 7)
            $headerUtamaRowIndex = -1;
            $namaColIndex = 3; // Kolom D (indeks 3) adalah NAMA SISWA

            foreach ($rows as $index => $row) {
                Log::debug("Baris $index: " . json_encode($row)); // Log setiap baris untuk debugging

                if (isset($row[$namaColIndex])) {
                    $cellValue = trim(strtoupper($row[$namaColIndex]));
                    Log::debug("Memeriksa kolom D (indeks $namaColIndex) di baris $index: '$cellValue'");

                    if ($cellValue === 'NAMA SISWA') {
                        $headerUtamaRowIndex = $index;
                        Log::info("Header 'NAMA SISWA' ditemukan di baris $index (indeks array PHP).");
                        break;
                    }
                }
            }

            if ($headerUtamaRowIndex === -1) {
                Log::warning("Header 'NAMA SISWA' tidak ditemukan di sheet '$selectedSheetName'. Dicari di kolom D.");
                return back()->withErrors(['file_excel' => 'Header "NAMA SISWA" tidak ditemukan dalam sheet yang dipilih. Pastikan struktur file benar.']);
            }

            // Cari baris header tanggal dan NISN (tanggal 1, 2, ..., 31 dan INDUK di baris 9 -> indeks 8)
            $headerTanggalRowIndex = -1;
            $nisnColIndex = 2; // Kolom C (indeks 2) adalah INDUK/NISN
            $tanggalStartColIndex = 5; // Kolom F (indeks 5) adalah tanggal 1

            // Karena kita tahu header utama di baris 8 (indeks 7), header tanggal biasanya di baris 9 (indeks 8)
            $tanggalRowIndex = $headerUtamaRowIndex + 1;

            if (isset($rows[$tanggalRowIndex]) && isset($rows[$tanggalRowIndex][$tanggalStartColIndex])) {
                 $cellValue = trim($rows[$tanggalRowIndex][$tanggalStartColIndex]);
                 if ($cellValue === '1') { // Cek apakah kolom F berisi '1'
                     $headerTanggalRowIndex = $tanggalRowIndex;
                     Log::info("Header tanggal '1' ditemukan di baris $headerTanggalRowIndex (indeks array PHP).");
                 }
            }

            if ($headerTanggalRowIndex === -1) {
                Log::warning("Header tanggal '1' tidak ditemukan di sheet '$selectedSheetName'. Dicari setelah baris header 'NAMA SISWA'.");
                return back()->withErrors(['file_excel' => 'Header tanggal (1, 2, ..., 31) tidak ditemukan dalam sheet yang dipilih. Pastikan struktur file benar.']);
            }

            // Ambil bulan dan tahun dari baris 6 (indeks 5)
            $bulanRow = 5; // Baris 6 dalam array PHP adalah indeks 5
            $bulan = null;
            $tahun = null;

            if (isset($rows[$bulanRow])) {
                $bulanCell = trim($rows[$bulanRow][2] ?? ''); // Kolom F (indeks 5) adalah bulan
                $tahunCell = trim($rows[$bulanRow][6] ?? ''); // Kolom G (indeks 6) adalah tahun

                if ($bulanCell && $tahunCell) {
                    $bulan = $bulanCell;
                    $tahun = (int)$tahunCell;
                    Log::info("Bulan dan Tahun ditemukan dari Baris 6: $bulan $tahun");
                } else {
                    Log::warning("Bulan atau Tahun tidak ditemukan di Baris 6. Bulan: '$bulanCell', Tahun: '$tahunCell'");
                }
            } else {
                Log::warning("Baris 6 tidak ditemukan di sheet '$selectedSheetName'.");
            }

            // Validasi apakah bulan dan tahun ditemukan
            if (!$bulan || !$tahun) {
                Log::warning("Bulan atau Tahun tidak ditemukan di sheet '$selectedSheetName' (Baris 6).");
                return back()->withErrors(['file_excel' => 'Informasi Bulan atau Tahun (Baris 6) tidak ditemukan dalam sheet yang dipilih. Pastikan struktur file benar.']);
            }

            $headerRowUtama = $rows[$headerUtamaRowIndex];
            $tanggalHeaderRow = $rows[$headerTanggalRowIndex];
            Log::info("Header row utama: " . json_encode($headerRowUtama));
            Log::info("Header row tanggal: " . json_encode($tanggalHeaderRow));

            // Ambil semester (dari header info kelas - Baris 3, indeks 2)
            $semester = 1; // Default ganjil
            $semesterRow = 5; // Baris 3 dalam array PHP adalah indeks 2
            if (isset($rows[$semesterRow][4])) {
                $cellValue = trim(strtoupper($rows[$semesterRow][0]));
                if (strpos($cellValue, 'GENAP') !== false) {
                    $semester = 2;
                    Log::info("Semester ditemukan dari Baris 6: $semester");
                } elseif (strpos($cellValue, 'GANJIL') !== false) {
                    Log::info("Semester ditemukan dari Baris 6: $semester");
                }
            }


            $processedCount = 0;
            $errorMessages = [];

            // Loop melalui data siswa (mulai dari baris setelah header tanggal)
            for ($i = $headerTanggalRowIndex + 1; $i < count($rows); $i++) {
                $rowData = $rows[$i];

                // Ambil nama siswa (kolom D, indeks 3) dari baris data siswa
                $nama = trim($rowData[$namaColIndex] ?? '');

                // Abaikan baris kosong atau baris yang tidak memiliki nama
                if (empty($nama)) {
                    $firstCell = trim($rowData[0] ?? '');
                    if (strpos(strtoupper($firstCell), 'JUMLAH') !== false || strpos(strtoupper($firstCell), 'ABSENSI') !== false || strpos(strtoupper($firstCell), 'MENGETAHUI') !== false) {
                         Log::info("Mencapai baris akhir: '$firstCell'. Menghentikan pemrosesan.");
                         break; // Hentikan loop jika menemukan baris informasi akhir
                    }
                    continue; // Lewati baris kosong atau tanpa nama
                }

                Log::debug("Memproses siswa: $nama");

                // Ambil NISN dari kolom C (indeks 2) dari baris data siswa
                $nisn = trim($rowData[$nisnColIndex] ?? 'NISN_IMPORT_' . time() . '_' . rand(1000, 9999));

                // Cari siswa di database berdasarkan nama dan kelas
                $siswa = IsiKelas10::where('modelkelas10s_id', $kelasId)
                                 ->where('nama', $nama)
                                 ->first();

                if (!$siswa) {
                    Log::warning("Siswa '$nama' tidak ditemukan di kelas $kelasId. Membuat entri baru dengan NISN: $nisn.");
                    $siswa = IsiKelas10::create([
                        'modelkelas10s_id' => $kelasId,
                        'nama' => $nama,
                        'nisn' => $nisn,
                    ]);
                } else {
                    if ($siswa->nisn !== $nisn) {
                        Log::info("Memperbarui NISN siswa '$nama' dari '{$siswa->nisn}' menjadi '$nisn'.");
                        $siswa->update(['nisn' => $nisn]);
                    }
                }

                // Ambil data absensi harian (tanggal 1 - 31)
                $absensiData = [];
                $total_s = 0;
                $total_i = 0;
                $total_a = 0;
                $total_h = 0; // Hitung kehadiran jika diperlukan

                for ($day = 1; $day <= 31; $day++) {
                    $colIndex = $tanggalStartColIndex + ($day - 1);
                    $nilai = isset($rowData[$colIndex]) ? trim($rowData[$colIndex]) : null;

                    $nilaiValid = null;
                    if (in_array(strtoupper($nilai), ['S', 'I', 'A', 'H'])) {
                        $nilaiValid = strtoupper($nilai);
                    } elseif (empty($nilai)) {
                        $nilaiValid = null; // atau ''
                    } else {
                        $errorMessages[] = "Baris " . ($i + 1) . " (Siswa: '$nama'): Tanggal $day, Nilai '$nilai' tidak valid. Harus S, I, A, H, atau kosong.";
                        continue; // Lewati tanggal ini
                    }

                    $absensiData["tanggal_$day"] = $nilaiValid;

                    if ($nilaiValid === 'S') $total_s++;
                    if ($nilaiValid === 'I') $total_i++;
                    if ($nilaiValid === 'A') $total_a++;
                    if ($nilaiValid === 'H') $total_h++;
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
                        'bulan' => $bulan, // Gunakan $bulan yang diambil dari Baris 6
                        'tahun' => $tahun, // Gunakan $tahun yang diambil dari Baris 6
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

            Log::info("Import selesai. $processedCount data siswa diproses.");
            return redirect()->route('kelas10.show', $kelasId)->with('success', "Import berhasil! $processedCount data siswa diproses untuk bulan $bulan tahun $tahun.");

        } catch (\Exception $e) {
            Log::error('Import Error: ' . $e->getMessage());
            return back()->withErrors(['file_excel' => 'Terjadi kesalahan saat memproses file: ' . $e->getMessage()]);
        }
    }
}
