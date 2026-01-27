<?php

namespace App\Http\Controllers;

use App\Models\AbsenKelas10;
use App\Models\IsiKelas10;
// Ganti use statement
use App\Models\Kelas10;
use Illuminate\Http\Request;

class AbsenKelas10Controller extends Controller
{
    public function create(Kelas10 $kelas10) // Parameter sesuai dengan route resource
    {
        $kelas10_obj = $kelas10; // Untuk digunakan di view
        $isiKelas10 = $kelas10->isi;
        return view('dashboard.kelas.kelas10.isikelas10.create', compact('kelas10_obj', 'isiKelas10'));
    }

     public function store(Request $request)
    {
        $request->validate([
            'kelas10_id' => 'required|exists:modelkelas10s,id', // Validasi ID kelas
            'nama' => 'required|string|max:255',
            'nisn' => 'required|string|max:255|unique:isi_kelas10,nisn,NULL,id,modelkelas10s_id,'.$request->kelas10_id, // NISN unik dalam kelas ini
            'semester' => 'required|in:1,2',
            'bulan' => 'required|string',
            'tahun' => 'required|integer',
        ]);

        // Cari apakah siswa sudah ada di kelas ini
        $siswa = IsiKelas10::where('modelkelas10s_id', $request->kelas10_id)
                          ->where('nama', $request->nama)
                          ->where('nisn', $request->nisn)
                          ->first();

        // Jika tidak ditemukan, buat siswa baru
        if (!$siswa) {
            $siswa = IsiKelas10::create([
                'modelkelas10s_id' => $request->kelas10_id,
                'nama' => $request->nama,
                'nisn' => $request->nisn,
            ]);
        }

        // Sekarang kita memiliki ID siswa (baik yang baru dibuat atau yang sudah ada)
        $data = [
            'isi_kelas10_id' => $siswa->id, // Gunakan ID siswa
            'semester' => $request->semester,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
        ];

        $total_s = 0;
        $total_i = 0;
        $total_a = 0;
        $total_h = 0;

        for ($i = 1; $i <= 31; $i++) {
            $field = "tanggal_$i";
            $nilai = $request->input($field, null);
            $data[$field] = $nilai;

            if ($nilai === 'S') $total_s++;
            if ($nilai === 'I') $total_i++;
            if ($nilai === 'A') $total_a++;
            if ($nilai === 'H') $total_h++;
        }

        $data['total_s'] = $total_s;
        $data['total_i'] = $total_i;
        $data['total_a'] = $total_a;
        $data['total_h'] = $total_h;

        AbsenKelas10::create($data);

        return redirect()->route('kelas10.show', $request->kelas10_id)->with('success', 'Data absensi berhasil ditambahkan.');
    }

    public function edit(AbsenKelas10 $absenkelas10)
    {
        // Ambil kelas dari siswa yang terkait dengan absensi ini
        $kelas10_obj = $absenkelas10->isikelas10->kelas10;
        // $isiKelas10 mungkin tidak lagi diperlukan di view edit ini
        return view('dashboard.kelas.kelas10.isikelas10.edit', compact('absenkelas10', 'kelas10_obj'));
    }

    public function update(Request $request, AbsenKelas10 $absenkelas10)

    {



        $request->validate([
            'kelas10_id' => 'required|exists:modelkelas10s,id',
            'nama' => 'required|string|max:255',
            'nisn' => 'required|string|max:255|unique:isi_kelas10,nisn,'.$absenkelas10->isikelas10->id.',id,modelkelas10s_id,'.$request->kelas10_id,
            'semester' => 'required|in:1,2',
            'bulan' => 'required|string',
            'tahun' => 'required|integer',
            // Validasi opsional untuk tanggal, misalnya hanya menerima S, I, A, H, atau null/empty
            // for ($i = 1; $i <= 31; $i++) {
            //     $rules["tanggal_$i"] = 'nullable|in:S,I,A,H';
            // }
            // $request->validate($rules);
        ]);

        $siswaLama = $absenkelas10->isikelas10;
        $namaBerubah = $siswaLama->nama !== $request->nama;
        $nisnBerubah = $siswaLama->nisn !== $request->nisn;

        $isi_kelas10_id = $siswaLama->id;

        if ($namaBerubah || $nisnBerubah) {
            $siswaBaru = IsiKelas10::where('modelkelas10s_id', $request->kelas10_id)
                                  ->where('nama', $request->nama)
                                  ->where('nisn', $request->nisn)
                                  ->first();

            if ($siswaBaru) {
                $isi_kelas10_id = $siswaBaru->id;
            } else {
                $siswaLama->update([
                    'nama' => $request->nama,
                    'nisn' => $request->nisn,
                ]);
                $isi_kelas10_id = $siswaLama->id;
            }
        }

        $data = [
            'isi_kelas10_id' => $isi_kelas10_id,
            'semester' => $request->semester,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
        ];

        $total_s = 0;
        $total_i = 0;
        $total_a = 0;
        $total_h = 0;

        // Loop untuk mengambil data dari request dan menghitung total
        for ($i = 1; $i <= 31; $i++) {
            $field = "tanggal_$i";
            $nilai = $request->input($field, null); // Ambil nilai dari request
            $data[$field] = $nilai; // Masukkan ke array data yang akan diupdate

            // Debug: Tampilkan nilai yang diterima dari request (hapus setelah yakin)
            // \Log::info("Tanggal $i: " . ($nilai ?? 'NULL'));

            if ($nilai === 'S') $total_s++;
            if ($nilai === 'I') $total_i++;
            if ($nilai === 'A') $total_a++;
            if ($nilai === 'H') $total_h++;
        }

        $data['total_s'] = $total_s;
        $data['total_i'] = $total_i;
        $data['total_a'] = $total_a;
        $data['total_h'] = $total_h;


        // Update data absensi
        $absenkelas10->update($data);

        return redirect()->route('kelas10.show', $request->kelas10_id)->with('success', 'Data absensi berhasil diperbarui.');
    }


    public function destroy(AbsenKelas10 $absenkelas10)
    {
        // Ambil ID kelas dari siswa yang terkait dengan absensi ini
        $kelasId = $absenkelas10->isikelas10->kelas10->id; // Gunakan relasi yang telah diperbarui
        $absenkelas10->delete();
        return redirect()->route('kelas10.show', $kelasId)->with('success', 'Data absensi berhasil dihapus.');
    }
}
