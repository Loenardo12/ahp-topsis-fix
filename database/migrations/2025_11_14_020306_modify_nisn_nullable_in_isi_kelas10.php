<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('isi_kelas10', function (Blueprint $table) {
            // Pastikan kolom nisn bisa null
            $table->string('nisn')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('isi_kelas10', function (Blueprint $table) {
            // Kembalikan ke state sebelumnya jika dibutuhkan, misalnya tidak nullable
            $table->string('nisn')->nullable(false)->change(); // atau sesuaikan default sebelumnya
        });
    }
};
