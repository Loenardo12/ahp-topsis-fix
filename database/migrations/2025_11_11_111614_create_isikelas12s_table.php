<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('isi_kelas12', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modelkelas12s_id')->constrained('modelkelas12s')->onDelete('cascade');
            $table->string('nama');
            $table->string('nisn');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('isi_kelas12', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['modelkelas12s_id']);
            // Lalu hapus kolomnya
            $table->dropColumn('modelkelas12s_id');
        });
    }
};
