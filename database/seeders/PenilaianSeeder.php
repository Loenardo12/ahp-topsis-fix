<?php

namespace Database\Seeders;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\SubKriteria;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log; // Tambahkan ini

class PenilaianSeeder extends Seeder
{
    public function run(): void
    {
        $alternatif = Alternatif::orderBy('id', 'asc')->get();
        $kriteria = Kriteria::orderBy('id', 'asc')->get();
        $penilaian = [
            [7, 9, 9, 8],
            [8, 7, 8, 7],
            [9, 6, 8, 9],
            [6, 7, 8, 6],
            
        ];

        foreach ($alternatif as $a => $item) {
            // Pastikan array $penilaian[$a] ada
            if (!isset($penilaian[$a])) {
                Log::warning("Tidak ada data penilaian untuk alternatif ID {$item->id} (indeks $a). Melewati...");
                continue;
            }

            foreach ($kriteria as $k => $value) {
                // Pastikan array $penilaian[$a][$k] ada
                if (!isset($penilaian[$a][$k])) {
                    Log::warning("Tidak ada nilai penilaian untuk alternatif ID {$item->id}, kriteria ID {$value->id} (indeks $k). Melewati...");
                    continue;
                }

                $nilai = $penilaian[$a][$k];

                // Cari sub_kriteria berdasarkan kriteria_id dan nilai
                $subKriteria = SubKriteria::where('kriteria_id', $value->id)->where('nilai', $nilai)->first();

                if (!$subKriteria) {
                    Log::error("SubKriteria tidak ditemukan untuk kriteria_id {$value->id} dan nilai $nilai. Melewati...");
                    continue;
                }

                Penilaian::create([
                    "alternatif_id" => $item->id,
                    "kriteria_id" => $value->id,
                    "sub_kriteria_id" => $subKriteria->id, // Gunakan $subKriteria->id
                ]);
            }
        }
    }
}
