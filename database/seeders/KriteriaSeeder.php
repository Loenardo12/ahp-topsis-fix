<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Kriteria;
use App\Models\Alternatif;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kode = ["K00001", "K00002", "K00003", "K00004"];
        $namaKriteria = ["Nilai Akademik","kehadiran", "kedisiplinan","Nilai Non Akademik"  ];
        $bobot = [0.4, 0.3, 0.2, 0.1];

        foreach ($kode as $item => $value) {
            Kriteria::create([
                "kode" => $value,
                "nama" => $namaKriteria[$item],
                "bobot" => $bobot[$item],
            ]);
        }
    }
}
