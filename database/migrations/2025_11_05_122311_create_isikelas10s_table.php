<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('isi_kelas10', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modelkelas10s_id')->constrained('modelkelas10s')->onDelete('cascade');
            $table->string('nama');
            $table->string('nisn');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('isi_kelas10');
    }
};
