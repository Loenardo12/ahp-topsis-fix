<?php

namespace App\Http\Repositories;

use App\Models\Objek;
use App\Imports\ObjekImport;
use App\Models\IsiKelas10; //
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

        // Ambil semua data isi_kelas10 beserta kelasnya
        // Kita ambil nama siswa dan title kelas
        $isiKelasData = IsiKelas10::with('kelas10') // Gunakan relasi 'kelas10' di model IsiKelas10
                              ->get(['id', 'nama', 'modelkelas10s_id']); // Ambil kolom yang relevan

        // Buat map dari nama siswa ke informasi kelas
        $kelasMap = [];
        foreach ($isiKelasData as $isi) {
            // Gunakan nama siswa sebagai key
            // Simpan informasi kelas (misalnya title dari kelas10)
            $kelasMap[$isi->nama] = $isi->kelas10 ? $isi->kelas10->title : 'Kelas Tidak Ditemukan';
        }

        // Tambahkan informasi kelas ke setiap objek
        foreach ($objekData as $obj) {
            $obj->kelas_nama = $kelasMap[$obj->nama] ?? 'Tidak Ada di Kelas'; // Gunakan 'kelas_nama' sebagai atribut tambahan
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
