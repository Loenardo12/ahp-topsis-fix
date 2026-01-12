

@extends('dashboard.layouts.app')

@section('container')
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-5 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex flex-row items-center justify-between p-6 pb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <h6>Hasil Perhitungan Prestasi Siswa</h6>
                    {{-- <!-- Export PDF -->
    <form action="{{ route('pdf_topsis') }}" method="POST" enctype="multipart/form-data" target="_blank">
        @csrf
        <button type="submit"
            class="flex items-center gap-2 rounded-md px-5 py-2.5 text-sm font-semibold text-white shadow-md transition-all duration-200
                   focus:outline-none focus:ring-4 focus:ring-rose-300
                   hover:-translate-y-0.5 hover:shadow-lg active:translate-y-0 active:shadow-inner"
            style="background-color:#dc2626; border:none;">
            <i class="ri-file-pdf-line text-lg"></i>
            Export PERHITUNGAN PDF
        </button>
    </form> --}}
                    <form action="{{ route('pdf_topsis') }}" method="post" enctype="multipart/form-data" target="_blank">
                        @csrf
                        <button type="submit"
                                class="btn btn-sm text-white font-semibold flex items-center gap-2 rounded-md
                                       transition-all duration-200 ease-in-out
                                       focus:outline-none focus:ring-4 focus:ring-rose-300
                                       hover:-translate-y-0.5 hover:shadow-lg active:translate-y-0 active:shadow-inner"
                                style="background-color:#dc2626; border:none;">
                            <i class="ri-file-pdf-line text-base"></i>
                            EXPORT PERHITUNGAN PDF
                        </button>
                    </form>

                </div>
                <div id='recipients' class="p-8 rounded shadow bg-white">
                    <table id="tabel_data_hasil" class="stripe hover" style="width:100%; padding-bottom: 1em;">
                        <thead>
                            <tr>
                                <th>Peringkat</th>
                                <th>Nama</th>
                                <th>Kelas</th> <!-- Kolom baru untuk kelas -->
                                <th>Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hasilTopsis as $index => $item) {{-- $index sekarang mewakili peringkat karena data diurutkan --}}
                                <tr>
                                    <td>{{ $index + 1 }}</td> {{-- Peringkat = indeks + 1 --}}
                                    <td>{{ $item->nama_objek }}</td>
                                    <td>{{ $item->nama_kelas ?? 'Kelas Tidak Ditemukan' }}</td> <!-- Tampilkan nama kelas -->
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

@section('js')
    <script>
        // Tabel
        $(document).ready(function() {
            $('#tabel_data_hasil').DataTable({
                responsive: true,
                // Hapus order di sini karena data sudah diurutkan di backend berdasarkan nilai (descending)
                // order: [[ 1, 'desc' ]], // Urutkan berdasarkan kolom Nilai (indeks 1) descending
                // Urutkan berdasarkan kolom Peringkat (indeks 0) ascending agar tampilan sesuai dengan peringkat 1, 2, 3, ...
                order: [[ 0, 'asc' ]],
            })
            .columns.adjust()
            .responsive.recalc();
        });
    </script>
@endsection
