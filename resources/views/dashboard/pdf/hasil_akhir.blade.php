@extends("dashboard.pdf.layouts.app")

@section("container")
    <div class="-mx-3 flex flex-wrap">
        <div class="w-full max-w-full flex-none px-3 table-pdf">

   <!-- Kop Surat -->
<div style="position: relative; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px;">
    <!-- Logo (positioned absolutely) -->
    <div style="position: absolute; left: 0; ">
        @php
            $logoPath = public_path('img/logo-provinsi-jawa-timur.png');
            $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';
        @endphp
        @if($logoData)
            <img src="data:image/png;base64,{{ $logoData }}"
                 alt="Logo Provinsi Jawa Timur"
                 style="height: 90px; width: auto; display: block;" />
        @else
            <div style="width: 90px; height: 90px; background: #eee; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #888;">
                Logo Tidak Ditemukan
            </div>
        @endif
    </div>

    <!-- Teks Kop (centered) -->
    <div style="font-family: Arial, sans-serif; text-align: center; padding: 10px 0;">
        <div style="font-size: 12px; font-weight: bold;">PEMERINTAH PROVINSI JAWA TIMUR</div>
        <div style="font-size: 12px; font-weight: bold;">DINAS PENDIDIKAN</div>
        <div style="font-size: 16px; font-weight: bold; margin: 5px 0;">SMA NEGERI 1 PRONOJIWO</div>
        <div style="font-size: 12px;">Jalan Ahmad Yani, Pronojiwo, Lumajang, Jawa Timur 67374</div>
        <div style="font-size: 12px;">Telepon (0334) 590269</div>
    </div>
</div>
            <!-- Judul Laporan -->
            <div class="mb-5 judul-laporan">
                <h1 style="font-family: Arial, sans-serif; font-size: 18px; font-weight: bold; text-align: center; margin: 0 0 20px 0;">{{ $judul }}</h1>
            </div>

            <!-- Tabel Hasil TOPSIS -->
            <div style="box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 20px; border-radius: 8px; overflow: hidden; background: #fff;">
                <div style="padding: 16px; background: #fff;">
                    <table border="0" cellpadding="6" cellspacing="0" style="width:100%; font-family: Arial, sans-serif; font-size: 12px; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th style="text-align: center; padding: 8px; background: #000000; border: 1px solid #ddd; font-weight: bold;">Peringkat</th>
                                <th style="text-align: left; padding: 8px; background: #000000; border: 1px solid #ddd; font-weight: bold;">Nama</th>
                                <th style="text-align: left; padding: 8px; background: #000000; border: 1px solid #ddd; font-weight: bold;">Kelas</th>
                                <th style="text-align: right; padding: 8px; background: #000000; border: 1px solid #ddd; font-weight: bold;">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $rank = 1;
                            @endphp
                            @foreach ($hasilTopsis as $item)
                                <tr>
                                    <td style="text-align: center; padding: 8px; border: 1px solid #ddd;">{{ $rank++ }}</td>
                                    <td style="text-align: left; padding: 8px; border: 1px solid #ddd;">{{ $item->nama_objek }}</td>
                                    <td style="text-align: left; padding: 8px; border: 1px solid #ddd;">{{ $item->nama_kelas ?? 'Kelas Tidak Ditemukan' }}</td>
                                    <td style="text-align: right; padding: 8px; border: 1px solid #ddd;">{{ round($item->nilai, 3) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Simpulan -->
            <div style="margin-top: 20px; font-family: Arial, sans-serif; font-size: 12px;">
                <h3 style="font-weight: bold; margin-bottom: 10px;">Simpulan</h3>
                @if($hasilTopsis->isNotEmpty())
                    @php
                        $bestResult = $hasilTopsis->first();
                    @endphp
                    <p style="margin: 0;">Berdasarkan tabel dari penilaian perhitungan TOPSIS yang dapat dijadikan rekomendasi alternatif, maka didapatkan alternatif dengan nilai tertinggi yaitu: <strong>{{ $bestResult->nama_objek }}</strong> dari kelas <strong>{{ $bestResult->nama_kelas ?? 'N/A' }}</strong> dengan nilai <strong>{{ round($bestResult->nilai, 3) }}</strong>.</p>
                @else
                    <p style="margin: 0;">Tidak ada data hasil TOPSIS yang tersedia.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
