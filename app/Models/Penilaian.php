<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    protected $table = "penilaian";
    protected $primaryKey = "id";
    public $incrementing = "true";
    public $timestamps = "true";

    // Tambahkan 'nilai_asli' ke fillable
    protected $fillable = [
        "alternatif_id",
        "kriteria_id",
        "sub_kriteria_id",
        "nilai_asli", // <-- Tambahkan ini
    ];

    // Relasi ke Alternatif
    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class, 'alternatif_id');
    }

    // Relasi ke Kriteria
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }

    // Relasi ke SubKriteria
    public function subKriteria()
    {
        return $this->belongsTo(SubKriteria::class, 'sub_kriteria_id');
    }
}
