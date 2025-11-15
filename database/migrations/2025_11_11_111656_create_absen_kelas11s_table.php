<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absen_kelas11', function (Blueprint $table) {
            $table->id();
            $table->foreignId('isi_kelas11_id')->constrained('isi_kelas11')->onDelete('cascade');
            $table->enum('semester', ['1', '2']);
            $table->string('bulan');
            $table->year('tahun');

            for ($i = 1; $i <= 31; $i++) {
                $table->enum("tanggal_$i", ['S', 'I', 'A', 'H'])->nullable();
            }

            $table->integer('total_s')->default(0);
            $table->integer('total_i')->default(0);
            $table->integer('total_a')->default(0);
            $table->integer('total_h')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absen_kelas11');
    }
};
