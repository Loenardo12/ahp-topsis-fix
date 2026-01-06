<?php

namespace App\Http\Repositories;

use App\Models\Penilaian;
use App\Models\Alternatif;
use App\Models\IsiKelas10; // Model untuk kelas 10
use App\Models\IsiKelas11; // Model untuk kelas 11
use App\Models\IsiKelas12; // Model untuk kelas 12
// Gunakan model kelas jika diperlukan untuk mengakses kolom selain 'title'
// use App\Models\Kelas10;
// use App\Models\Kelas11;
// use App\Models\Kelas12;
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

        // Ambil semua data isi_kelas beserta kelasnya untuk masing-masing tingkatan
        $isiKelas10Data = IsiKelas10::with('kelas10')->get(); // Asumsi relasi 'kelas10' di IsiKelas10
        $isiKelas11Data = IsiKelas11::with('kelas11')->get(); // Asumsi relasi 'kelas11' di IsiKelas11
        $isiKelas12Data = IsiKelas12::with('kelas12')->get(); // Asumsi relasi 'kelas12' di IsiKelas12

        // Buat map dari nama siswa ke informasi kelas untuk masing-masing tingkatan
        $kelas10Map = [];
        foreach ($isiKelas10Data as $isi) {
            // Gunakan nama siswa sebagai key
            // Simpan informasi kelas (misalnya title dari kelas10)
            $kelas10Map[$isi->nama] = $isi->kelas10 ? $isi->kelas10->title : 'Kelas Tidak Ditemukan';
        }

        $kelas11Map = [];
        foreach ($isiKelas11Data as $isi) {
            $kelas11Map[$isi->nama] = $isi->kelas11 ? $isi->kelas11->title : 'Kelas Tidak Ditemukan';
        }

        $kelas12Map = [];
        foreach ($isiKelas12Data as $isi) {
            $kelas12Map[$isi->nama] = $isi->kelas12 ? $isi->kelas12->title : 'Kelas Tidak Ditemukan';
        }

        // Gabungkan semua map kelas ke dalam satu map utama
        // Jika nama siswa ada di lebih dari satu tingkatan, entri terakhir akan menimpa yang sebelumnya
        // Jika Anda ingin menyimpan semua kelas (misalnya dalam array), logikanya harus diubah
        // Contoh saat ini hanya menyimpan kelas dari tingkatan tertinggi (12 > 11 > 10) jika nama sama
        $kelasMap = array_merge($kelas10Map, $kelas11Map, $kelas12Map);

        // Tambahkan informasi kelas ke setiap objek alternatif
        foreach ($alternatifData as $alt) {
            // Cek apakah nama siswa ada di map gabungan
            $kelas_nama = $kelasMap[$alt->objek->nama] ?? 'Tidak Ada di Kelas';
            // Tambahkan properti kelas_nama ke objek alternatif
            $alt->kelas_nama = $kelas_nama;
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
