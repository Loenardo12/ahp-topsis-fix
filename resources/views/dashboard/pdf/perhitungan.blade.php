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
            <div class="mb-5 judul-laporan">
                <h2>{{ $judul }}</h2>
            </div>

            {{-- Tabel Bobot Kriteria --}}
            <div class="shadow-soft-xl relative mb-5 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border">
                <div class="border-b-solid flex flex-row items-center justify-between rounded-t-2xl border-b-0 border-b-transparent bg-white p-6 pb-0">
                    <h3>Bobot Kriteria <span class="text-greenPrimary">(W)</span></h3>
                </div>
                <div id='recipients' class="rounded bg-white p-8 shadow">
                    <table border="0" cellpadding="0" cellspacing="0" style="width:100%; padding-top: 1em; padding-bottom: 1em;">
                        <thead>
                            <tr>
                                @foreach ($kriteria as $item)
                                    <th>{{ $item->nama }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @foreach ($kriteria as $item)
                                    <td>{{ $item->bobot }}</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

                        {{-- Tabel Penilaian --}}
            <div class="shadow-soft-xl relative mb-5 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border">
                <div class="border-b-solid flex flex-row items-center justify-between rounded-t-2xl border-b-0 border-b-transparent bg-white p-6 pb-0">
                    <h3>Penilaian</h3>
                </div>
                <div id='recipients' class="rounded bg-white p-8 shadow">
                    <table border="0" cellpadding="0" cellspacing="0" style="width:100%; padding-top: 1em; padding-bottom: 1em;">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                @foreach ($penilaian->unique("kriteria_id") as $item)
                                    <th>{{ $item->kriteria->nama }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($penilaian->unique("alternatif_id") as $item)
                                <tr>
                                    <td>{{ $item->alternatif->objek->nama }}</td>
                                    @foreach ($penilaian->where("alternatif_id", $item->alternatif_id) as $value)
                                        <td>
                                            {{-- Tampilkan nilai_asli jika ada, jika tidak, tampilkan subKriteria->nilai --}}
                                            {{ $value->nilai_asli ?? ($value->subKriteria ? $value->subKriteria->nilai : 'N/A') }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tabel Matriks Keputusan --}}
            <div class="shadow-soft-xl relative mb-5 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border">
                <div class="border-b-solid flex flex-row items-center justify-between rounded-t-2xl border-b-0 border-b-transparent bg-white p-6 pb-0">
                    <h3>Matriks Keputusan <span class="text-greenPrimary">(X)</span></h3>
                </div>
                <div id='recipients' class="rounded bg-white p-8 shadow">
                    <table border="0" cellpadding="0" cellspacing="0" style="width:100%; padding-top: 1em; padding-bottom: 1em;">
                        <thead>
                            <tr>
                                @foreach ($matriksKeputusan as $item)
                                    <th>{{ $item->nama_kriteria }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @foreach ($matriksKeputusan as $item)
                                    <td>{{ round($item->nilai, 2) }}</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tabel Matriks Normalisasi --}}
            <div class="shadow-soft-xl relative mb-5 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border">
                <div class="border-b-solid flex flex-row items-center justify-between rounded-t-2xl border-b-0 border-b-transparent bg-white p-6 pb-0">
                    <h3>Matriks Normalisasi <span class="text-greenPrimary">(R)</span></h3>
                </div>
                <div id='recipients' class="rounded bg-white p-8 shadow">
                    <table border="0" cellpadding="0" cellspacing="0" style="width:100%; padding-top: 1em; padding-bottom: 1em;">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                @foreach ($matriksNormalisasi->unique("kriteria_id") as $item)
                                    <th>{{ $item->nama_kriteria }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($matriksNormalisasi->unique("alternatif_id") as $item)
                                <tr>
                                    <td>{{ $item->nama_objek }}</td>
                                    @foreach ($matriksNormalisasi->where("alternatif_id", $item->alternatif_id) as $value)
                                        <td>
                                            {{ round($value->nilai, 2) }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tabel Matriks Y --}}
            <div class="shadow-soft-xl relative mb-5 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border">
                <div class="border-b-solid flex flex-row items-center justify-between rounded-t-2xl border-b-0 border-b-transparent bg-white p-6 pb-0">
                    <h3>Matriks Y</h3>
                </div>
                <div id='recipients' class="rounded bg-white p-8 shadow">
                    <table border="0" cellpadding="0" cellspacing="0" style="width:100%; padding-top: 1em; padding-bottom: 1em;">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                @foreach ($matriksY->unique("kriteria_id") as $item)
                                    <th>{{ $item->nama_kriteria }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($matriksY->unique("alternatif_id") as $item)
                                <tr>
                                    <td>{{ $item->nama_objek }}</td>
                                    @foreach ($matriksY->where("alternatif_id", $item->alternatif_id) as $value)
                                        <td>
                                            {{ round($value->nilai, 3) }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tabel Ideal Positif --}}
            <div class="shadow-soft-xl relative mb-5 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border">
                <div class="border-b-solid flex flex-row items-center justify-between rounded-t-2xl border-b-0 border-b-transparent bg-white p-6 pb-0">
                    <h3>Ideal Positif <span class="text-greenPrimary">(A<sup>+</sup>)</span></h3>
                </div>
                <div id='recipients' class="rounded bg-white p-8 shadow">
                    <table border="0" cellpadding="0" cellspacing="0" style="width:100%; padding-top: 1em; padding-bottom: 1em;">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                @foreach ($idealPositif->unique("kriteria_id") as $item)
                                    <th>{{ $item->nama_kriteria }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($idealPositif->unique("alternatif_id") as $item)
                                <tr>
                                    <td>{{ $item->nama_objek }}</td>
                                    @foreach ($idealPositif->where("alternatif_id", $item->alternatif_id) as $value)
                                        <td>
                                            {{ number_format($value->nilai, 6) }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tabel Ideal Negatif --}}
            <div class="shadow-soft-xl relative mb-5 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border">
                <div class="border-b-solid flex flex-row items-center justify-between rounded-t-2xl border-b-0 border-b-transparent bg-white p-6 pb-0">
                    <h3>Ideal Negatif <span class="text-greenPrimary">(A<sup>-</sup>)</span></h3>
                </div>
                <div id='recipients' class="rounded bg-white p-8 shadow">
                    <table border="0" cellpadding="0" cellspacing="0" style="width:100%; padding-top: 1em; padding-bottom: 1em;">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                @foreach ($idealNegatif->unique("kriteria_id") as $item)
                                    <th>{{ $item->nama_kriteria }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($idealNegatif->unique("alternatif_id") as $item)
                                <tr>
                                    <td>{{ $item->nama_objek }}</td>
                                    @foreach ($idealNegatif->where("alternatif_id", $item->alternatif_id) as $value)
                                        <td>
                                            {{ number_format($value->nilai, 6) }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tabel Solusi Ideal Positif --}}
            <div class="shadow-soft-xl relative mb-5 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border">
                <div class="border-b-solid flex flex-row items-center justify-between rounded-t-2xl border-b-0 border-b-transparent bg-white p-6 pb-0">
                    <h3>Solusi Ideal Positif <span class="text-greenPrimary">(Si<sup>+</sup>)</span></h3>
                </div>
                <div id='recipients' class="rounded bg-white p-8 shadow">
                    <table border="0" cellpadding="0" cellspacing="0" style="width:100%; padding-top: 1em; padding-bottom: 1em;">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($solusiIdealPositif as $item)
                                <tr>
                                    <td>{{ $item->nama_objek }}</td>
                                    <td>{{ round($item->nilai, 3) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tabel Solusi Ideal Negatif --}}
            <div class="shadow-soft-xl relative mb-5 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border">
                <div class="border-b-solid flex flex-row items-center justify-between rounded-t-2xl border-b-0 border-b-transparent bg-white p-6 pb-0">
                    <h3>Solusi Ideal Negatif <span class="text-greenPrimary">(Si<sup>-</sup>)</span></h3>
                </div>
                <div id='recipients' class="rounded bg-white p-8 shadow">
                    <table border="0" cellpadding="0" cellspacing="0" style="width:100%; padding-top: 1em; padding-bottom: 1em;">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($solusiIdealNegatif as $item)
                                <tr>
                                    <td>{{ $item->nama_objek }}</td>
                                    <td>{{ round($item->nilai, 3) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tabel Kedekatan Relatif terhadap Solusi Ideal --}}
            <div class="shadow-soft-xl relative mb-5 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border">
                <div class="border-b-solid flex flex-row items-center justify-between rounded-t-2xl border-b-0 border-b-transparent bg-white p-6 pb-0">
                    <h3>Kedekatan Relatif terhadap Solusi Ideal <span class="text-greenPrimary">(Ci)</span></h3>
                </div>
                <div id='recipients' class="rounded bg-white p-8 shadow">
                    <table border="0" cellpadding="0" cellspacing="0" style="width:100%; padding-top: 1em; padding-bottom: 1em;">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hasilTopsis as $item)
                                <tr>
                                    <td>{{ $item->nama_objek }}</td>
                                    <td>{{ round($item->nilai, 3) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
