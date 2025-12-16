@extends("dashboard.pdf.layouts.app")

@section("container")
    <div class="-mx-3 flex flex-wrap">
        <div class="w-full max-w-full flex-none px-3 table-pdf">
            <div class="mb-5 judul-laporan">
                <h1>{{ $judul }}</h1>
            </div>

            <div class="shadow-soft-xl relative mb-5 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border">
                <div class="border-b-solid flex flex-row items-center justify-between rounded-t-2xl border-b-0 border-b-transparent bg-white p-6 pb-0">
                    <h2>Hasil Perangkingan Menggunakan Perhitungan TOPSIS</h2>
                </div>
                <div id='recipients' class="rounded bg-white p-8 shadow">
                    <table border="0" cellpadding="0" cellspacing="0" style="width:100%; padding-top: 1em; padding-bottom: 1em;">
                        <thead>
                            <tr>
                                <th>Peringkat</th> <!-- Kolom baru -->
                                <th>Nama</th>
                                <th>Kelas</th> <!-- Kolom baru -->
                                <th>Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $rank = 1; // Inisialisasi peringkat
                            @endphp
                            @foreach ($hasilTopsis as $item)
                                <tr>
                                    <td>{{ $rank++ }}</td> <!-- Tampilkan dan naikkan peringkat -->
                                    <td>{{ $item->nama_objek }}</td>
                                    <td>{{ $item->nama_kelas ?? 'Kelas Tidak Ditemukan' }}</td> <!-- Tampilkan kelas -->
                                    <td>{{ round($item->nilai, 3) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <h2>Simpulan</h2>
                @if($hasilTopsis->isNotEmpty())
                    @php
                        $bestResult = $hasilTopsis->first();
                    @endphp
                    <p>Berdasarkan tabel dari penilaian perhitungan TOPSIS yang dapat dijadikan rekomendasi alternatif, maka didapatkan alternatif dengan nilai tertinggi yaitu: <span style="font-weight: bold;">{{ $bestResult->nama_objek }}</span> dari kelas <span style="font-weight: bold;">{{ $bestResult->nama_kelas ?? 'N/A' }}</span> dengan nilai <span style="font-weight: bold;">{{ round($bestResult->nilai, 3) }}</span>.</p>
                @else
                    <p>Tidak ada data hasil TOPSIS yang tersedia.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
