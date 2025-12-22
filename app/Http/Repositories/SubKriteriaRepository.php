<?php

namespace App\Http\Repositories;

use App\Models\SubKriteria;

class SubKriteriaRepository
{
    protected $subKriteria;

    public function __construct(SubKriteria $subKriteria)
    {
        $this->subKriteria = $subKriteria;
    }

    public function getAll()
    {
        $data = $this->subKriteria->all();
        return $data;
    }

    public function getWhereKriteria($kriteria_id)
    {
        // Ganti 'nilai' dengan kolom yang sesuai, misalnya 'bobot' atau 'nilai_max' untuk mengurutkan secara descending (tinggi ke rendah)
        $data = $this->subKriteria->where('kriteria_id', $kriteria_id)->orderBy('bobot', 'desc')->get(); // Ganti 'nilai' menjadi 'bobot' atau 'nilai_max'
        return $data;
    }

    public function simpan($data)
    {
        // Pastikan data yang masuk mencakup nilai_min, nilai_max, bobot
        $data = $this->subKriteria->create([
            'kode' => $data['kode'],
            'nama' => $data['nama'],
            'nilai_min' => $data['nilai_min'], // <-- Tambahkan ini
            'nilai_max' => $data['nilai_max'], // <-- Tambahkan ini
            'bobot' => $data['bobot'],         // <-- Tambahkan ini
            'kriteria_id' => $data['kriteria_id'],
        ]);
        return $data;
    }
   public function getDataById($id)
    {
        // Pastikan ini mengembalikan model SubKriteria beserta relasi jika diperlukan
        // Fungsi ini untuk mengambil SATU data
        $data = $this->subKriteria->with('kriteria')->where('id', $id)->firstOrFail();
        return $data; // Kembalikan model, bukan array numerik
    }

   public function perbarui($id, $data)
    {
        // Pastikan data yang di-update mencakup nilai_min, nilai_max, bobot
        $data = $this->subKriteria->where('id', $id)->update([
            // "kode" => $data['kode'],
            "nama" => $data['nama'],
            "nilai_min" => $data['nilai_min'], // <-- Tambahkan ini
            "nilai_max" => $data['nilai_max'], // <-- Tambahkan ini
            "bobot" => $data['bobot'],         // <-- Tambahkan ini
            "kriteria_id" => $data['kriteria_id'],
        ]);
        return $data;
    }

    public function hapus($id)
    {
        $data = $this->subKriteria->where('id', $id)->delete();
        return $data;
    }
}
