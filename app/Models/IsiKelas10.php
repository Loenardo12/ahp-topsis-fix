<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IsiKelas10 extends Model
{
    use HasFactory;

    protected $table = 'isi_kelas10';

    protected $fillable = ['modelkelas10s_id', 'nama', 'nisn'];

    // Ganti use statement
    public function kelas10()
    {
        return $this->belongsTo(Kelas10::class, 'modelkelas10s_id'); // Nama class model berubah
    }

    public function absenkelas10()
    {
        return $this->hasMany(AbsenKelas10::class, 'isi_kelas10_id');
    }

}
