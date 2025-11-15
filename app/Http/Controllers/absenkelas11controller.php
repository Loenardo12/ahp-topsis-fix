<?php

namespace App\Http\Controllers;

use App\Models\Absenkelas11;
use App\Models\Isikelas11;
// Ganti use statement
use App\Models\kelas11;
use Illuminate\Http\Request;

class Absenkelas11Controller extends Controller
{
    public function create(kelas11 $kelas11) // Parameter sesuai dengan route resource
    {
        $kelas11_obj = $kelas11; // Untuk digunakan di view
        $isikelas11 = $kelas11->isikelas11;
        return view('dashboard.kelas.kelas11.isikelas11.create', compact('kelas11_obj', 'isikelas11'));
    }

     public function store(Request $request)
    {
        $request->validate([
            'kelas11_id' => 'required|exists:modelkelas11s,id', // Validasi ID kelas
            'nama' => 'required|string|max:255',
            'nisn' => 'required|string|max:255|unique:isi_kelas11,nisn,NULL,id,modelkelas11s_id,'.$request->kelas11_id, // NISN unik dalam kelas ini
            'semester' => 'required|in:1,2',
            'bulan' => 'required|string',
            'tahun' => 'required|integer',
        ]);

        // Cari apakah siswa sudah ada di kelas ini
        $siswa = Isikelas11::where('modelkelas11s_id', $request->kelas11_id)
                          ->where('nama', $request->nama)
                          ->where('nisn', $request->nisn)
                          ->first();

        // Jika tidak ditemukan, buat siswa baru
        if (!$siswa) {
            $siswa = Isikelas11::create([
                'modelkelas11s_id' => $request->kelas11_id,
                'nama' => $request->nama,
                'nisn' => $request->nisn,
            ]);
        }

        // Sekarang kita memiliki ID siswa (baik yang baru dibuat atau yang sudah ada)
        $data = [
            'isi_kelas11_id' => $siswa->id, // Gunakan ID siswa
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

        Absenkelas11::create($data);

        return redirect()->route('kelas11.show', $request->kelas11_id)->with('success', 'Data absensi berhasil ditambahkan.');
    }

    public function edit(Absenkelas11 $absenkelas11)
    {
        // Ambil kelas dari siswa yang terkait dengan absensi ini
        $kelas11_obj = $absenkelas11->isikelas11->kelas11;
        // $isikelas11 mungkin tidak lagi diperlukan di view edit ini
        return view('dashboard.kelas.kelas11.isikelas11.edit', compact('absenkelas11', 'kelas11_obj'));
    }

    public function update(Request $request, Absenkelas11 $absenkelas11)

    {



        $request->validate([
            'kelas11_id' => 'required|exists:modelkelas11s,id',
            'nama' => 'required|string|max:255',
            'nisn' => 'required|string|max:255|unique:isi_kelas11,nisn,'.$absenkelas11->isikelas11->id.',id,modelkelas11s_id,'.$request->kelas11_id,
            'semester' => 'required|in:1,2',
            'bulan' => 'required|string',
            'tahun' => 'required|integer',
            // Validasi opsional untuk tanggal, misalnya hanya menerima S, I, A, H, atau null/empty
            // for ($i = 1; $i <= 31; $i++) {
            //     $rules["tanggal_$i"] = 'nullable|in:S,I,A,H';
            // }
            // $request->validate($rules);
        ]);

        $siswaLama = $absenkelas11->isikelas11;
        $namaBerubah = $siswaLama->nama !== $request->nama;
        $nisnBerubah = $siswaLama->nisn !== $request->nisn;

        $isi_kelas11_id = $siswaLama->id;

        if ($namaBerubah || $nisnBerubah) {
            $siswaBaru = Isikelas11::where('modelkelas11s_id', $request->kelas11_id)
                                  ->where('nama', $request->nama)
                                  ->where('nisn', $request->nisn)
                                  ->first();

            if ($siswaBaru) {
                $isi_kelas11_id = $siswaBaru->id;
            } else {
                $siswaLama->update([
                    'nama' => $request->nama,
                    'nisn' => $request->nisn,
                ]);
                $isi_kelas11_id = $siswaLama->id;
            }
        }

        $data = [
            'isi_kelas11_id' => $isi_kelas11_id,
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
        $absenkelas11->update($data);

        return redirect()->route('kelas11.show', $request->kelas11_id)->with('success', 'Data absensi berhasil diperbarui.');
    }


    public function destroy(Absenkelas11 $absenkelas11)
    {
        // Ambil ID kelas dari siswa yang terkait dengan absensi ini
        $kelasId = $absenkelas11->isikelas11->kelas11->id; // Gunakan relasi yang telah diperbarui
        $absenkelas11->delete();
        return redirect()->route('kelas11.show', $kelasId)->with('success', 'Data absensi berhasil dihapus.');
    }
}
