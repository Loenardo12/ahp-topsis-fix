<?php

namespace App\Http\Repositories;

use App\Models\Objek;
use App\Imports\ObjekImport;
use App\Models\IsiKelas10; // Model untuk kelas 10
use App\Models\IsiKelas11; // Model untuk kelas 11
use App\Models\IsiKelas12; // Model untuk kelas 12
// Gunakan model kelas jika diperlukan untuk mengakses kolom selain 'title'
// use App\Models\ModelKelas10;
// use App\Models\ModelKelas11;
// use App\Models\ModelKelas12;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class ObjekRepository
{
    protected $objek;

    public function __construct(Objek $objek)
    {
        $this->objek = $objek;
    }

    public function getAll()
    {
        // Ambil semua data objek
        $objekData = $this->objek->orderBy('created_at', 'asc')->get();

        // Ambil semua data isi_kelas beserta kelasnya untuk masing-masing tingkatan
        $isiKelas10Data = IsiKelas10::with('kelas10')->get(['id', 'nama', 'modelkelas10s_id']); // Asumsi relasi 'kelas10' di IsiKelas10
        $isiKelas11Data = IsiKelas11::with('kelas11')->get(['id', 'nama', 'modelkelas11s_id']); // Asumsi relasi 'kelas11' di IsiKelas11
        $isiKelas12Data = IsiKelas12::with('kelas12')->get(['id', 'nama', 'modelkelas12s_id']); // Asumsi relasi 'kelas12' di IsiKelas12

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

        // Tambahkan informasi kelas ke setiap objek
        foreach ($objekData as $obj) {
            // Cek apakah nama siswa ada di map gabungan
            $kelas_nama = $kelasMap[$obj->nama] ?? 'Tidak Ada di Kelas';
            // Tambahkan properti kelas_nama ke objek
            $obj->kelas_nama = $kelas_nama;
        }

        return $objekData;
    }

    public function simpan($data)
    {
        $data = $this->objek->create($data);
        return $data;
    }

    public function getDataById($id)
    {
        $data = $this->objek->where('id', $id)->firstOrFail();
        return $data;
    }

    public function perbarui($id, $data)
    {
        $data = $this->objek->where('id', $id)->update([
            "nama" => $data['nama'],
        ]);
        return $data;
    }

    public function hapus($id)
    {
        $data = $this->objek->where('id', $id)->delete();
        return $data;
    }

    public function import($data)
    {
        // menangkap file excel
        $file = $data->file('import_data');

        // import data
        $import = Excel::import(new ObjekImport, $file);

        return $import;
    }
}
