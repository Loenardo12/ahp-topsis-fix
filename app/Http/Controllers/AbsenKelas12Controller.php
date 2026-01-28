<?php

namespace App\Http\Controllers;

use App\Models\Absenkelas12;
use App\Models\Isikelas12;
// Ganti use statement
use App\Models\kelas12;
use Illuminate\Http\Request;

class AbsenKelas12Controller extends Controller
{
    public function create(kelas12 $kelas12) // Parameter sesuai dengan route resource
    {
        $kelas12_obj = $kelas12; // Untuk digunakan di view
        $isikelas12 = $kelas12->isikelas12;
        return view('dashboard.kelas.kelas12.isikelas12.create', compact('kelas12_obj', 'isikelas12'));
    }

     public function store(Request $request)
    {
        $request->validate([
            'kelas12_id' => 'required|exists:modelkelas12s,id', // Validasi ID kelas
            'nama' => 'required|string|max:255',
            'nisn' => 'required|string|max:255|unique:isi_kelas12,nisn,NULL,id,modelkelas12s_id,'.$request->kelas12_id, // NISN unik dalam kelas ini
            'semester' => 'required|in:1,2',
            'bulan' => 'required|string',
            'tahun' => 'required|integer',
        ]);

        // Cari apakah siswa sudah ada di kelas ini
        $siswa = Isikelas12::where('modelkelas12s_id', $request->kelas12_id)
                          ->where('nama', $request->nama)
                          ->where('nisn', $request->nisn)
                          ->first();

        // Jika tidak ditemukan, buat siswa baru
        if (!$siswa) {
            $siswa = Isikelas12::create([
                'modelkelas12s_id' => $request->kelas12_id,
                'nama' => $request->nama,
                'nisn' => $request->nisn,
            ]);
        }

        // Sekarang kita memiliki ID siswa (baik yang baru dibuat atau yang sudah ada)
        $data = [
            'isi_kelas12_id' => $siswa->id, // Gunakan ID siswa
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

        Absenkelas12::create($data);

        return redirect()->route('kelas12.show', $request->kelas12_id)->with('success', 'Data absensi berhasil ditambahkan.');
    }

    public function edit(Absenkelas12 $absenkelas12)
    {
        // Ambil kelas dari siswa yang terkait dengan absensi ini
        $kelas12_obj = $absenkelas12->isikelas12->kelas12;
        // $isikelas12 mungkin tidak lagi diperlukan di view edit ini
        return view('dashboard.kelas.kelas12.isikelas12.edit', compact('absenkelas12', 'kelas12_obj'));
    }

    public function update(Request $request, Absenkelas12 $absenkelas12)

    {



        $request->validate([
            'kelas12_id' => 'required|exists:modelkelas12s,id',
            'nama' => 'required|string|max:255',
            'nisn' => 'required|string|max:255|unique:isi_kelas12,nisn,'.$absenkelas12->isikelas12->id.',id,modelkelas12s_id,'.$request->kelas12_id,
            'semester' => 'required|in:1,2',
            'bulan' => 'required|string',
            'tahun' => 'required|integer',
            // Validasi opsional untuk tanggal, misalnya hanya menerima S, I, A, H, atau null/empty
            // for ($i = 1; $i <= 31; $i++) {
            //     $rules["tanggal_$i"] = 'nullable|in:S,I,A,H';
            // }
            // $request->validate($rules);
        ]);

        $siswaLama = $absenkelas12->isikelas12;
        $namaBerubah = $siswaLama->nama !== $request->nama;
        $nisnBerubah = $siswaLama->nisn !== $request->nisn;

        $isi_kelas12_id = $siswaLama->id;

        if ($namaBerubah || $nisnBerubah) {
            $siswaBaru = Isikelas12::where('modelkelas12s_id', $request->kelas12_id)
                                  ->where('nama', $request->nama)
                                  ->where('nisn', $request->nisn)
                                  ->first();

            if ($siswaBaru) {
                $isi_kelas12_id = $siswaBaru->id;
            } else {
                $siswaLama->update([
                    'nama' => $request->nama,
                    'nisn' => $request->nisn,
                ]);
                $isi_kelas12_id = $siswaLama->id;
            }
        }

        $data = [
            'isi_kelas12_id' => $isi_kelas12_id,
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
        $absenkelas12->update($data);

        return redirect()->route('kelas12.show', $request->kelas12_id)->with('success', 'Data absensi berhasil diperbarui.');
    }


    public function destroy(Absenkelas12 $absenkelas12)
    {
        // Ambil ID kelas dari siswa yang terkait dengan absensi ini
        $kelasId = $absenkelas12->isikelas12->kelas12->id; // Gunakan relasi yang telah diperbarui
        $absenkelas12->delete();
        return redirect()->route('kelas12.show', $kelasId)->with('success', 'Data absensi berhasil dihapus.');
    }
}
