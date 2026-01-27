<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IsiKelas12 extends Model
{
    use HasFactory;

    protected $table = 'isi_kelas12';

    protected $fillable = ['modelkelas12s_id', 'nama', 'nisn'];

    // Ganti use statement
    public function kelas12()
    {
        return $this->belongsTo(kelas12::class, 'modelkelas12s_id'); // Nama class model berubah
    }

    public function absenkelas12()
    {
        return $this->hasMany(Absenkelas12::class, 'isi_kelas12_id');
    }
}
