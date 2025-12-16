<?php

namespace App\Http\Services;

use App\Http\Repositories\PenilaianRepository;

class PenilaianService
{
    protected $penilaianRepository;

    public function __construct(PenilaianRepository $penilaianRepository)
    {
        $this->penilaianRepository = $penilaianRepository;
    }

    public function getAll()
    {
        $data = $this->penilaianRepository->getAll();
        return $data;
    }

   public function ubahGetData($alternatifId)
    {
        // Gunakan fungsi baru dari repository
        return $this->penilaianRepository->getDataWithRelations($alternatifId);
    }

    public function perbaruiPostData($request)
    {
        // $validate = $request->validated();
        $data = $this->penilaianRepository->perbarui($request);
        return $data;
    }

    public function simpanFromAlternatif($request)
    {
        $data = $this->penilaianRepository->addFromAlternatif($request[1]);
        return $data;
    }

    public function getAllWithRelations()
    {
        return $this->penilaianRepository->getAllWithSubKriteria();
    }

         public function getAlternatifIdsWithNames()
    {
        return $this->penilaianRepository->getAlternatifIdsWithNames();
    }
}
