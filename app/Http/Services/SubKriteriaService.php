<?php

namespace App\Http\Services;

use App\Http\Repositories\SubKriteriaRepository;

class SubKriteriaService
{
    protected $subKriteriaRepository;

    public function __construct(SubKriteriaRepository $subKriteriaRepository)
    {
        $this->subKriteriaRepository = $subKriteriaRepository;
    }

    public function getAll()
    {
        $data = $this->subKriteriaRepository->getAll();
        return $data;
    }

    public function getWhereKriteria($kriteria_id)
    {
        // Ini akan memanggil fungsi dari repository yang sudah kita perbaiki
        $data = $this->subKriteriaRepository->getWhereKriteria($kriteria_id);
        return $data;
    }

    public function simpanPostData($request)
    {
        $data = $request->validated();
        $data = [true, $this->subKriteriaRepository->simpan($data)];

        return $data;
    }

    public function ubahGetData($request)
    {
        // Ambil data dari repository
        $data = $this->subKriteriaRepository->getDataById($request->id);

        // Kembalikan data sebagai array/object yang bisa diakses oleh AJAX
        // Kita kembalikan seluruh objek model agar JavaScript bisa mengakses propertinya
        return $data; // Kembalikan model SubKriteria
    }



    public function perbaruiPostData($request)
    {
        $validate = $request->validated();
        $data = [true, $this->subKriteriaRepository->perbarui($request->id, $validate)];
        return $data;
    }

    public function hapusPostData($request)
    {
        $data = $this->subKriteriaRepository->hapus($request);
        return $data;
    }
}
