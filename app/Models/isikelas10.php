<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IsiKelas10 extends Model
{
    use HasFactory;

    // Tentukan nama tabel yang benar
    protected $table = 'isi_kelas10';

    protected $fillable = ['modelkelas10s_id', 'nama', 'nisn'];

    // Relasi: IsiKelas10 milik ModelKelas10
    public function modelkelas10()
    {
        return $this->belongsTo(ModelKelas10::class, 'modelkelas10s_id');
    }

    // Relasi: IsiKelas10 memiliki banyak AbsenKelas10
    public function absenkelas10()
    {
        return $this->hasMany(AbsenKelas10::class, 'isi_kelas10_id');
    }
}
