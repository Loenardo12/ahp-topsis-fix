<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use App\Models\SubKriteria;
use Illuminate\Database\Seeder;

class SubKriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $kode = ["A", "B", "C", "D"];
        $nama = ["Sangat Baik", "Baik", "Biasa", "Kurang Baik"];
        $nilai = [9, 8, 7, 6];

        // Ambil semua kriteria dari database
        $kriterias = Kriteria::all();

        foreach ($kriterias as $kriteria) {
            foreach ($kode as $index => $item) {
                SubKriteria::create([
                    "kode" => $item . ($index + 1),
                    "nama" => $nama[$index],
                    "nilai" => $nilai[$index],
                    "kriteria_id" => $kriteria->id, // Gunakan ID dari kriteria yang ada
                ]);
            }
        }
    }
}
