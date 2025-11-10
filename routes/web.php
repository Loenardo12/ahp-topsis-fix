<?php

use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\isikelas10controller;
use App\Http\Controllers\AbsenKelas10Controller;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\ObjekController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubKriteriaController;
use App\Http\Controllers\TopsisController;
use App\Http\Controllers\kelas10controller;
use App\Http\Controllers\ImportAbsenController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('landing');
});

Route::get('/reg', function () {
    return view('auth.register');
});
Route::get('/user', function () {
    return view('dashboard.user.index');
});
Route::get('/kelas10', function () {
    return view('dashboard.kelas.kelas10.index');
});
Route::get('/kelas11', function () {
    return view('dashboard.user.kelas11');
});
Route::get('/kelas12', function () {
    return view('dashboard.user.kelas12');
});

// Route::get('/dashboard2', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard2');



// Route untuk form import
Route::get('/kelas10/import', [ImportAbsenController::class, 'showForm'])->name('import.absen.form');

// Route untuk mendapatkan daftar sheet
Route::post('/import/get-sheets', [ImportAbsenController::class, 'getSheets'])->name('import.absen.getSheets');

// Route untuk memproses import
Route::post('/import/process', [ImportAbsenController::class, 'processImport'])->name('import.absen.process');

/// Route untuk Kelas10
Route::resource('kelas10', Kelas10Controller::class);

// Route untuk AbsenKelas10
Route::get('/kelas10/{kelas10}/absen/create', [AbsenKelas10Controller::class, 'create'])->name('absenkelas10.create');
// Ganti URI untuk store: hapus {kelas10}
Route::post('/kelas10/absen', [AbsenKelas10Controller::class, 'store'])->name('absenkelas10.store');
Route::get('/absen/{absenkelas10}/edit', [AbsenKelas10Controller::class, 'edit'])->name('absenkelas10.edit');
Route::put('/absen/{absenkelas10}', [AbsenKelas10Controller::class, 'update'])->name('absenkelas10.update');
Route::delete('/absen/{absenkelas10}', [AbsenKelas10Controller::class, 'destroy'])->name('absenkelas10.destroy');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group([
    "middleware" => ['auth'],
    "prefix" => "dashboard"

], function() {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::group([
        'prefix' => 'kriteria'
    ], function () {
        Route::get('/', [KriteriaController::class, 'index'])->name('kriteria');
        Route::post('/simpan', [KriteriaController::class, 'simpan'])->name('kriteria.simpan');
        Route::get('/ubah', [KriteriaController::class, 'ubah'])->name('kriteria.ubah');
        Route::post('/ubah', [KriteriaController::class, 'perbarui'])->name('kriteria.perbarui');
        Route::post('/hapus', [KriteriaController::class, 'hapus'])->name('kriteria.hapus');
    });

    Route::group([
        'prefix' => 'sub_kriteria'
    ], function () {
        Route::get('/', [SubKriteriaController::class, 'index'])->name('sub_kriteria');
        Route::post('/simpan', [SubKriteriaController::class, 'simpan'])->name('sub_kriteria.simpan');
        Route::get('/ubah', [SubKriteriaController::class, 'ubah'])->name('sub_kriteria.ubah');
        Route::post('/ubah', [SubKriteriaController::class, 'perbarui'])->name('sub_kriteria.perbarui');
        Route::post('/hapus', [SubKriteriaController::class, 'hapus'])->name('sub_kriteria.hapus');
    });

    Route::group([
        'prefix' => 'objek'
    ], function () {
        Route::get('/', [ObjekController::class, 'index'])->name('objek');
        Route::post('/simpan', [ObjekController::class, 'simpan'])->name('objek.simpan');
        Route::get('/ubah', [ObjekController::class, 'ubah'])->name('objek.ubah');
        Route::post('/ubah', [ObjekController::class, 'perbarui'])->name('objek.perbarui');
        Route::post('/hapus', [ObjekController::class, 'hapus'])->name('objek.hapus');
        Route::post('/import', [ObjekController::class, 'import'])->name('objek.import');
    });

    Route::group([
        'prefix' => 'alternatif'
    ], function () {
        Route::get('/', [AlternatifController::class, 'index'])->name('alternatif');
        Route::post('/simpan', [AlternatifController::class, 'simpan'])->name('alternatif.simpan');
        Route::post('/hapus', [AlternatifController::class, 'hapus'])->name('alternatif.hapus');
    });

    Route::group([
        'prefix' => 'penilaian'
    ], function () {
        Route::get('/', [PenilaianController::class, 'index'])->name('penilaian');
        Route::post('/simpan', [PenilaianController::class, 'simpan'])->name('penilaian.simpan');
        Route::get('/ubah/{alternatif_id}', [PenilaianController::class, 'ubah'])->name('penilaian.ubah');
        Route::post('/ubah/{alternatif_id}', [PenilaianController::class, 'perbarui'])->name('penilaian.perbarui');
        Route::post('/hapus', [PenilaianController::class, 'hapus'])->name('penilaian.hapus');

    });

    Route::get('/perhitungan', [TopsisController::class, 'index'])->name('perhitungan');
    Route::post('/pdf_topsis', [TopsisController::class, 'pdf_topsis'])->name('pdf_topsis');
    Route::post('/pdf_hasil', [TopsisController::class, 'pdf_hasil'])->name('pdf_hasil');
    Route::post('/hitung_topsis', [TopsisController::class, 'hitungTopsis'])->name('hitung_topsis');
    Route::get('/hasil_akhir', [TopsisController::class, 'hasilAkhir'])->name('hasil_akhir');
});

require __DIR__.'/auth.php';
