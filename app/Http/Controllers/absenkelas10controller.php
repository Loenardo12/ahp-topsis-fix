<?php

namespace App\Http\Controllers;

use App\Models\AbsenKelas10;
use App\Models\IsiKelas10;
use App\Models\ModelKelas10;
use Illuminate\Http\Request;

class AbsenKelas10Controller extends Controller
{
    public function create(ModelKelas10 $modelkelas10) // Ganti parameter
    {
        $modelkelas10_obj = $modelkelas10; // Untuk digunakan di view
        $isiKelas10 = $modelkelas10->isikelas10;
        return view('dashboard.kelas.kelas10.isikelas10.create', compact('modelkelas10_obj', 'isiKelas10'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'isi_kelas10_id' => 'required|exists:isi_kelas10,id',
            'semester' => 'required|in:1,2',
            'bulan' => 'required|string',
            'tahun' => 'required|integer',
        ]);

        $data = $request->only(['isi_kelas10_id', 'semester', 'bulan', 'tahun']);

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

        return redirect()->route('modelkelas10.show', $request->modelkelas10s_id)->with('success', 'Data absensi berhasil ditambahkan.'); // Ganti route
    }

    public function edit(AbsenKelas10 $absenkelas10)
    {
        $modelkelas10_obj = $absenkelas10->isikelas10->modelkelas10;
        $isiKelas10 = $modelkelas10_obj->isikelas10;
        return view('dashboard.kelas.kelas10.isikelas10.edit', compact('absenkelas10', 'modelkelas10_obj', 'isiKelas10'));
    }

    public function update(Request $request, AbsenKelas10 $absenkelas10)
    {
        $request->validate([
            'isi_kelas10_id' => 'required|exists:isi_kelas10,id',
            'semester' => 'required|in:1,2',
            'bulan' => 'required|string',
            'tahun' => 'required|integer',
        ]);

        $data = $request->only(['isi_kelas10_id', 'semester', 'bulan', 'tahun']);

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

        $absenkelas10->update($data);

        return redirect()->route('modelkelas10.show', $absenkelas10->isikelas10->modelkelas10->id)->with('success', 'Data absensi berhasil diperbarui.');
    }

    public function destroy(AbsenKelas10 $absenkelas10)
    {
        $kelasId = $absenkelas10->isikelas10->modelkelas10->id;
        $absenkelas10->delete();
        return redirect()->route('modelkelas10.show', $kelasId)->with('success', 'Data absensi berhasil dihapus.');
    }
}
