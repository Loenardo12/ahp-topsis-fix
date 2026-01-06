<?php

use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\isikelas10controller;
use App\Http\Controllers\AbsenKelas10Controller;
use App\Http\Controllers\AbsenKelas11Controller;
use App\Http\Controllers\AbsenKelas12Controller;
use App\Http\Controllers\kelas10controller;
use App\Http\Controllers\kelas11controller;
use App\Http\Controllers\kelas12controller;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\ObjekController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubKriteriaController;
use App\Http\Controllers\TopsisController;
use App\Http\Controllers\usercontroller;
use App\Models\Kelas10;
use App\Http\Controllers\ImportAbsenController;
use App\Http\Controllers\ImportAbsenkelas11Controller;
use App\Http\Controllers\ImportAbsenkelas12Controller;

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
// Route::get('/user', function () {
//     return view('dashboard.user.index');
// });
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


// import kelas 10
// Route untuk memproses import
Route::post('/import/process', [ImportAbsenController::class, 'processImport'])->name('import.absen.process');
// Route untuk form import
Route::get('/kelas10/import', [ImportAbsenController::class, 'showForm'])->name('import.absen.form');
// Route untuk mendapatkan daftar sheet
Route::post('/import/get-sheets', [ImportAbsenController::class, 'getSheets'])->name('import.absen.getSheets');

// // import kelas 11
// // Route untuk memproses import
// Route::post('/import/process', [ImportAbsenkelas11Controller::class, 'processImport'])->name('import.absen.process');
// // Route untuk form import
// Route::get('/kelas11/import', [ImportAbsenkelas11Controller::class, 'showForm'])->name('import.absen.form');
// // Route untuk mendapatkan daftar sheet
// Route::post('/import/get-sheets', [ImportAbsenkelas11Controller::class, 'getSheets'])->name('import.absen.getSheets');

// // import kelas 12
// // Route untuk memproses import
// Route::post('/import/process', [ImportAbsenkelas12Controller::class, 'processImport'])->name('import.absen.process');
// // Route untuk form import
// Route::get('/kelas12/import', [ImportAbsenkelas12Controller::class, 'showForm'])->name('import.absen.form');
// // Route untuk mendapatkan daftar sheet
// Route::post('/import/get-sheets', [ImportAbsenkelas12Controller::class, 'getSheets'])->name('import.absen.getSheets');


/// Route untuk Kelas10
Route::resource('kelas10', Kelas10Controller::class);

// Route untuk AbsenKelas10
Route::get('/kelas10/{kelas10}/absen/create', [AbsenKelas10Controller::class, 'create'])->name('absenkelas10.create');
// Ganti URI untuk store: hapus {kelas10}
Route::post('/kelas10/absen', [AbsenKelas10Controller::class, 'store'])->name('absenkelas10.store');
Route::get('/absen/{absenkelas10}/edit', [AbsenKelas10Controller::class, 'edit'])->name('absenkelas10.edit');
Route::put('/absen/{absenkelas10}', [AbsenKelas10Controller::class, 'update'])->name('absenkelas10.update');
Route::delete('/absen/{absenkelas10}', [AbsenKelas10Controller::class, 'destroy'])->name('absenkelas10.destroy');


/// Route untuk Kelas11
Route::resource('kelas11', Kelas11Controller::class);

// Route untuk AbsenKelas11
Route::get('/kelas11/{kelas11}/absen/create', [AbsenKelas11Controller::class, 'create'])->name('absenkelas11.create');
// Ganti URI untuk store: hapus {kelas11}
Route::post('/kelas11/absen', [AbsenKelas11Controller::class, 'store'])->name('absenkelas11.store');
Route::get('/absen/{absenkelas11}/edit', [AbsenKelas11Controller::class, 'edit'])->name('absenkelas11.edit');
Route::put('/absen/{absenkelas11}', [AbsenKelas11Controller::class, 'update'])->name('absenkelas11.update');
Route::delete('/absen/{absenkelas11}', [AbsenKelas11Controller::class, 'destroy'])->name('absenkelas11.destroy');

/// Route untuk Kelas12
Route::resource('kelas12', Kelas12Controller::class);

// Route untuk AbsenKelas12
Route::get('/kelas12/{kelas12}/absen/create', [AbsenKelas12Controller::class, 'create'])->name('absenkelas12.create');
// Ganti URI untuk store: hapus {kelas12}
Route::post('/kelas12/absen', [AbsenKelas12Controller::class, 'store'])->name('absenkelas12.store');
Route::get('/absen/{absenkelas12}/edit', [AbsenKelas12Controller::class, 'edit'])->name('absenkelas12.edit');
Route::put('/absen/{absenkelas12}', [AbsenKelas12Controller::class, 'update'])->name('absenkelas12.update');
Route::delete('/absen/{absenkelas12}', [AbsenKelas12Controller::class, 'destroy'])->name('absenkelas12.destroy');






// Route untuk halaman pilih kelas
// Route::resource('objek', ObjekController::class);
Route::get('/objek', [ObjekController::class, 'index'])->name('objek.index');
Route::get('/objek/pilih-kelas', [ObjekController::class, 'pilihKelas'])->name('objek.pilihKelas');
// Route untuk proses pengambilan data siswa dari kelas tertentu
Route::post('/objek/ambil-siswa', [ObjekController::class, 'ambilSiswa'])->name('objek.ambilSiswa');
// API untuk mengambil kelas berdasarkan tingkat
Route::get('/api/kelas10', function () {
    return Kelas10::select('id', 'title', 'description')->get();
});
Route::post('/objek/hapus-multiple', [ObjekController::class, 'hapusMultiple'])->name('objek.hapus.multiple');
    Route::get('/pilih-kelas', [ObjekController::class, 'pilihKelas'])->name('objek.pilih.kelas'); // <-- Tambahkan ini jika belum ada
    Route::post('/ambil-siswa', [ObjekController::class, 'ambilSiswa'])->name('objek.ambil.siswa');


//route user
route::resource('users', userController::class)->middleware('isSuperadmin');
route::post('user-update-role', [usercontroller::class, 'updateRole'])->name('users.update-Role');
// Route::post('/users', [usercontroller::class, 'store'])->name('users.store');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');







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
        Route::post('/hapus-multiple', [AlternatifController::class, 'hapusMultiple'])->name('alternatif.hapus.multiple');
        Route::post('/alternatif/hapus-multiple', [AlternatifController::class, 'hapusMultiple'])->name('alternatif.hapus.multiple');

    });

    Route::group([
        'prefix' => 'penilaian'
    ], function () {
        Route::get('/', [PenilaianController::class, 'index'])->name('penilaian');
        Route::post('/simpan', [PenilaianController::class, 'simpan'])->name('penilaian.simpan');
        Route::get('/ubah/{alternatif_id}', [PenilaianController::class, 'ubah'])->name('penilaian.ubah');
        Route::post('/ubah/{alternatif_id}', [PenilaianController::class, 'perbarui'])->name('penilaian.perbarui');
        Route::post('/hapus', [PenilaianController::class, 'hapus'])->name('penilaian.hapus');
        // Route untuk form import
    Route::get('/import', [PenilaianController::class, 'showImportForm'])->name('penilaian.import.form');
    // Route untuk proses import
    Route::post('/import', [PenilaianController::class, 'import'])->name('penilaian.import');
    // Route untuk mendapatkan sheet dari file
    Route::post('/get-sheets', [PenilaianController::class, 'getSheets'])->name('penilaian.getSheets');
    });

    Route::get('/perhitungan', [TopsisController::class, 'index'])->name('perhitungan');
    Route::post('/pdf_topsis', [TopsisController::class, 'pdf_topsis'])->name('pdf_topsis');
    Route::post('/pdf_hasil', [TopsisController::class, 'pdf_hasil'])->name('pdf_hasil');
    Route::post('/hitung_topsis', [TopsisController::class, 'hitungTopsis'])->name('hitung_topsis');
    Route::get('/hasil_akhir', [TopsisController::class, 'hasilAkhir'])->name('hasil_akhir');
    Route::post('/alternatif/simpan-semua-objek', [AlternatifController::class, 'simpanSemuaObjek'])->name('alternatif.simpan.semua.objek');
});

require __DIR__.'/auth.php';
