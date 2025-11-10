<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas10 extends Model
{
    use HasFactory;

    // Nama tabel harus sesuai dengan migrasi Anda: modelkelas10s
    protected $table = 'modelkelas10s';

    protected $fillable = ['title', 'description'];

    // Relasi: Kelas10 memiliki banyak IsiKelas10
    public function isikelas10()
    {
        return $this->hasMany(IsiKelas10::class, 'modelkelas10s_id');
    }
}
