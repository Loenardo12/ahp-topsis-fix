<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas12 extends Model
{
    use HasFactory;

    // Nama tabel harus sesuai dengan migrasi Anda: modelkelas12s
    protected $table = 'modelkelas12s';

    protected $fillable = ['title', 'description'];

    // Relasi: kelas12 memiliki banyak Isikelas12
    public function isikelas12()
    {
        return $this->hasMany(Isikelas12::class, 'modelkelas12s_id');
    }
}
