<?php

namespace App\Http\Repositories;

use App\Models\Penilaian;
use App\Models\Alternatif;
use App\Models\IsiKelas10; //
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AlternatifRepository
{
    protected $alternatif, $penilaian;

    public function __construct(Alternatif $alternatif, Penilaian $penilaian)
    {
        $this->alternatif = $alternatif;
        $this->penilaian = $penilaian;
    }

    public function getAll()
    {
        // Ambil semua data alternatif dengan relasi objek
        $alternatifData = $this->alternatif->with('objek')->orderBy('created_at', 'asc')->get();

        // Ambil semua data isi_kelas10 beserta kelasnya
        $isiKelasData = IsiKelas10::with('kelas10')->get();

        // Buat map dari nama siswa ke informasi kelas
        $kelasMap = [];
        foreach ($isiKelasData as $isi) {
            // Gunakan nama siswa sebagai key
            // Simpan informasi kelas (misalnya title dari kelas10)
            $kelasMap[$isi->nama] = $isi->kelas10 ? $isi->kelas10->title : 'Kelas Tidak Ditemukan';
        }

        // Tambahkan informasi kelas ke setiap objek alternatif
        foreach ($alternatifData as $alt) {
            $alt->kelas_nama = $kelasMap[$alt->objek->nama] ?? 'Tidak Ada di Kelas'; // Gunakan nama dari objek terkait
        }

        return $alternatifData;
    }

    public function simpan($data)
    {
        $input = [];
        foreach ($data as $item) {
            $input[] = $this->alternatif->create([
                'objek_id' => $item,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
        return $input;
    }

    public function getDataById($id)
    {
        $data = $this->alternatif->where('id', $id)->firstOrFail();
        return $data;
    }

    public function hapus($id)
    {
        $data = [
            DB::table('hasil_solusi_topsis')->where('alternatif_id', $id)->delete(),
            DB::table('solusi_ideal_positif')->where('alternatif_id', $id)->delete(),
            DB::table('solusi_ideal_negatif')->where('alternatif_id', $id)->delete(),
            DB::table('ideal_positif')->where('alternatif_id', $id)->delete(),
            DB::table('ideal_negatif')->where('alternatif_id', $id)->delete(),
            DB::table('matriks_normalisasi_bobot_keputusan')->where('alternatif_id', $id)->delete(),
            DB::table('matriks_normalisasi_keputusan')->where('alternatif_id', $id)->delete(),
            $this->penilaian->where('alternatif_id', $id)->delete(),
            $this->alternatif->where('id', $id)->delete(),
        ];
        return $data;
    }
}
