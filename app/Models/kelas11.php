<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas11 extends Model
{
    use HasFactory;

    // Nama tabel harus sesuai dengan migrasi Anda: modelkelas11s
    protected $table = 'modelkelas11s';

    protected $fillable = ['title', 'description'];

    // Relasi: kelas11 memiliki banyak Isikelas11
    public function isikelas11()
    {
        return $this->hasMany(Isikelas11::class, 'modelkelas11s_id');
    }
}
