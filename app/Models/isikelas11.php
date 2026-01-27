<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IsiKelas11 extends Model
{
    use HasFactory;

    protected $table = 'isi_kelas11';

    protected $fillable = ['modelkelas11s_id', 'nama', 'nisn'];

    // Ganti use statement
    public function kelas11()
    {
        return $this->belongsTo(kelas11::class, 'modelkelas11s_id'); // Nama class model berubah
    }

    public function absenkelas11()
    {
        return $this->hasMany(Absenkelas11::class, 'isi_kelas11_id');
    }
}
