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
        Schema::table('sub_kriteria', function (Blueprint $table) {
            // Hapus kolom 'nilai' lama
            $table->dropColumn('nilai');

            // Tambahkan kolom 'nilai_min' dan 'nilai_max' untuk rentang
            $table->unsignedTinyInteger('nilai_min')->after('nama');
            $table->unsignedTinyInteger('nilai_max')->after('nilai_min');

            // Tambahkan kolom 'bobot' untuk menyimpan bobot numerik yang digunakan dalam perhitungan
            $table->unsignedTinyInteger('bobot')->after('nilai_max');
        });

        // Kolom 'nilai_asli' di tabel 'penilaian' seharusnya sudah ada dari percakapan sebelumnya
        // Jika belum, tambahkan di sini
        // Schema::table('penilaian', function (Blueprint $table) {
        //     if (!Schema::hasColumn('penilaian', 'nilai_asli')) {
        //         $table->integer('nilai_asli')->nullable()->after('sub_kriteria_id');
        //     }
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_kriteria', function (Blueprint $table) {
            // Kembalikan kolom 'nilai' lama
            $table->double('nilai')->after('nama');

            // Hapus kolom yang baru ditambahkan
            $table->dropColumn(['nilai_min', 'nilai_max', 'bobot']);
        });

        // Kembalikan perubahan di penilaian jika diperlukan
        // Schema::table('penilaian', function (Blueprint $table) {
        //     if (Schema::hasColumn('penilaian', 'nilai_asli')) {
        //         $table->dropColumn('nilai_asli');
        //     }
        // });
    }
};
