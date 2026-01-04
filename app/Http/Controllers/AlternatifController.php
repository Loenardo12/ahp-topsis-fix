<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\ObjekService;
use App\Http\Services\PenilaianService;
use App\Http\Requests\AlternatifRequest;
use App\Http\Services\AlternatifService;
use App\Http\Controllers\TopsisController;
use App\Models\Penilaian;
use App\Models\Alternatif;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\log;


class AlternatifController extends Controller
{
    protected $alternatifService, $objekService, $penilaianService, $topsisService;

    public function __construct(AlternatifService $alternatifService, ObjekService $objekService, PenilaianService $penilaianService, TopsisController $topsisService)
    {
        $this->alternatifService = $alternatifService;
        $this->objekService = $objekService;
        $this->penilaianService = $penilaianService;
        $this->topsisService = $topsisService;
    }

    public function index()
    {
        $judul = "Pemilihan Siswa";

        $data = $this->alternatifService->getAll();
        $objek = $this->objekService->getAll();

        return view('dashboard.alternatif.index', [
            "judul" => $judul,
            "data" => $data,
            "objek" => $objek,
        ]);
    }

    public function simpan(AlternatifRequest $request)
    {
        $data = $this->alternatifService->simpanPostData($request->input('objek_id'));
        if (!$data[0]) {
            return redirect('dashboard/alternatif')->with('gagal', $data[1]);
        }

        $this->penilaianService->simpanFromAlternatif($data);

        return redirect('dashboard/alternatif')->with('berhasil', "Data berhasil disimpan!");
    }

    public function hapus(Request $request)
    {
        $this->alternatifService->hapusPostData($request->id);
        $this->topsisService->hitungTopsisSetelahHapus();
        return redirect('dashboard/alternatif');
    }

   public function hapusMultiple(Request $request)
{
    $request->validate([
        'ids' => 'required|array',
        'ids.*' => 'required|exists:alternatif,id', // Validasi ini tetap perlu
    ]);

    $ids = $request->input('ids');

    $deletedCount = 0;
    try {
        DB::transaction(function () use ($ids, &$deletedCount) { // Gunakan transaksi
            // Hapus data terkait dari tabel lain BERDASARKAN ID yang akan dihapus
            // Ganti 'alternatif_id' dengan nama kolom foreign key yang benar di setiap tabel
            DB::table('hasil_solusi_topsis')->whereIn('alternatif_id', $ids)->delete();
            DB::table('solusi_ideal_positif')->whereIn('alternatif_id', $ids)->delete();
            DB::table('solusi_ideal_negatif')->whereIn('alternatif_id', $ids)->delete();
            DB::table('ideal_positif')->whereIn('alternatif_id', $ids)->delete();
            DB::table('ideal_negatif')->whereIn('alternatif_id', $ids)->delete();
            DB::table('matriks_normalisasi_bobot_keputusan')->whereIn('alternatif_id', $ids)->delete();
            DB::table('matriks_normalisasi_keputusan')->whereIn('alternatif_id', $ids)->delete();

            // Hapus dari model Penilaian BERDASARKAN ID alternatif
            Penilaian::whereIn('alternatif_id', $ids)->delete();

            // Hapus dari model Alternatif itu sendiri
            $deletedCount = Alternatif::whereIn('id', $ids)->delete();
        });

        // Jika berhasil, kembalikan respons sukses
        if ($deletedCount > 0) {
            return response()->json(['success' => true, 'message' => "$deletedCount data berhasil dihapus."]);
        } else {
            // Jika transaksi berhasil tapi tidak ada yang dihapus (mungkin karena data sudah dihapus sebelumnya)
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dihapus.']);
        }

    } catch (\Exception $e) {
        // Jika terjadi error (misalnya constraint violation), rollback otomatis terjadi karena transaksi
        Log::error('Gagal menghapus data alternatif massal: ' . $e->getMessage());
        Log::error('Trace: ' . $e->getTraceAsString()); // Log stack trace untuk debugging lebih lanjut

        return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()]);
    }
}
}
