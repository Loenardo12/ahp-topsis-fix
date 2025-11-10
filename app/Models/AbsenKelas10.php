<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsenKelas10 extends Model
{
    use HasFactory;

    protected $table = 'absen_kelas10';

    // Penting: Tambahkan semua kolom yang bisa diisi massal ke dalam $fillable
    protected $fillable = [
        'isi_kelas10_id',
        'semester',
        'bulan',
        'tahun',
        // Tambahkan semua kolom tanggal
        'tanggal_1', 'tanggal_2', 'tanggal_3', 'tanggal_4', 'tanggal_5',
        'tanggal_6', 'tanggal_7', 'tanggal_8', 'tanggal_9', 'tanggal_10',
        'tanggal_11', 'tanggal_12', 'tanggal_13', 'tanggal_14', 'tanggal_15',
        'tanggal_16', 'tanggal_17', 'tanggal_18', 'tanggal_19', 'tanggal_20',
        'tanggal_21', 'tanggal_22', 'tanggal_23', 'tanggal_24', 'tanggal_25',
        'tanggal_26', 'tanggal_27', 'tanggal_28', 'tanggal_29', 'tanggal_30',
        'tanggal_31',
        // Tambahkan kolom total
        'total_s',
        'total_i',
        'total_a',
        'total_h',
    ];


    // Relasi: AbsenKelas10 milik IsiKelas10
    public function isikelas10()
    {
        return $this->belongsTo(IsiKelas10::class, 'isi_kelas10_id');
    }
}
