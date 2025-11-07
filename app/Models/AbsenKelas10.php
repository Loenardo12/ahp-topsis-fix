<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsenKelas10 extends Model
{
    use HasFactory;

    // Tentukan nama tabel yang benar
    protected $table = 'absen_kelas10';

    protected $fillable = [
        'isi_kelas10_id',
        'semester',
        'bulan',
        'tahun',
        // Kolom tanggal dan total diisi di controller
    ];

    // Relasi: AbsenKelas10 milik IsiKelas10
    public function isikelas10()
    {
        return $this->belongsTo(IsiKelas10::class, 'isi_kelas10_id');
    }
}
