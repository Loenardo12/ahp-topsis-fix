<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelKelas10 extends Model
{
    use HasFactory;

    // Tentukan nama tabel yang benar
    protected $table = 'modelkelas10s';

    protected $fillable = ['title', 'description'];

    // Relasi: ModelKelas10 memiliki banyak IsiKelas10
    public function isikelas10()
    {
        return $this->hasMany(IsiKelas10::class, 'modelkelas10s_id');
    }
}
