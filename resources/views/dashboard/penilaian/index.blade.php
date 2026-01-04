@extends('dashboard.layouts.app')

@section('container')
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex flex-row items-center justify-between p-6 pb-0 mb-4 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <h6>Tabel {{ $judul }}<p>Masukan Bobot Penilaian Siswa </p></h6>
                    <div>
                        <!-- Tombol Tambah Tetap Ada -->
                        <a href="{{ route('penilaian.simpan') }}" class="cursor-pointer inline-block px-3 py-2 font-bold text-center text-white rounded-lg text-sm ease-soft-in shadow-soft-md bg-gradient-to-br from-greenPrimary to-greenPrimary/80 shadow-soft-md hover:shadow-soft-xs active:opacity-85 hover:scale-102 transition-all mr-2"> <!-- Tambahkan margin untuk jarak -->
                            <i class="ri-add-fill"></i>
                            Tambah {{ $judul }}
                        </a>
                        <!-- Tombol Import Baru -->
                        <a href="{{ route('penilaian.import.form') }}" class="cursor-pointer inline-block px-3 py-2 font-bold text-center text-white rounded-lg text-sm ease-soft-in shadow-soft-md bg-gradient-to-br from-blue-500 to-blue-700 shadow-soft-md hover:shadow-soft-xs active:opacity-85 hover:scale-102 transition-all"> <!-- Gaya berbeda untuk import -->
                            <i class="ri-upload-line"></i> <!-- Ikon upload -->
                            Import {{ $judul }}
                        </a>
                    </div>
                </div>
                <div id='recipients' class="p-8 mt-6 lg:mt-0 rounded shadow bg-white">
                    <table id="tabel_data" class="stripe hover" style="width:100%; padding-top: 1em; padding-bottom: 1em;">
                        <thead>
                            <tr>
                                <th>Nama Siswa</th>
                                @foreach ($subKriteria->unique('kriteria_id') as $item)
                                    <th>{{ $item->kriteria->nama }}</th>
                                @endforeach
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data->groupBy('alternatif_id') as $alternatifId => $penilaianGroup)
                                <tr>
                                    <td>{{ $penilaianGroup->first()->alternatif->objek->nama }}</td>
                                    @foreach ($subKriteria->unique('kriteria_id') as $kriteriaItem) {{-- Loop berdasarkan kriteria unik --}}
                                        @php
                                            // Cari nilai penilaian untuk pasangan alternatif_id dan kriteria_id ini
                                            $penilaianItem = $penilaianGroup->firstWhere('kriteria_id', $kriteriaItem->kriteria_id);
                                            // Gunakan nilai_asli jika ada, jika tidak, fallback ke subKriteria->nilai
                                            $nilaiTampil = $penilaianItem ? ($penilaianItem->nilai_asli ?? ($penilaianItem->subKriteria ? $penilaianItem->subKriteria->nilai : 'N/A')) : 'N/A';
                                        @endphp
                                        <td>{{ $nilaiTampil }}</td>
                                    @endforeach
                                    <td class="flex gap-x-3">
                                        <a href="{{ route('penilaian.ubah', $alternatifId) }}" class="cursor-pointer">
                                            <i class="ri-pencil-line text-xl"></i>
                                        </a>
                                        <!-- Tambahkan tombol hapus jika diperlukan -->
                                        {{--<button onclick="return delete_button('{{ $alternatifId }}', '{{ $penilaianGroup->first()->alternatif->objek->nama }}');">
                                            <i class="ri-delete-bin-line text-xl"></i>
                                        </button>--}}
                                    </td>
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
            $('#tabel_data').DataTable({
                responsive: true,
                order: [],
                lengthChange: false,
                paging: false,
            })
            .columns.adjust()
            .responsive.recalc();
        });

        @if (session()->has('berhasil'))
            Swal.fire({
                title: 'Berhasil',
                text: '{{ session('berhasil') }}',
                icon: 'success',
                confirmButtonColor: '#6419E6',
                confirmButtonText: 'OK',
            });
        @endif

        @if (session()->has('gagal'))
            Swal.fire({
                title: 'Gagal',
                text: '{{ session('gagal') }}',
                icon: 'error',
                confirmButtonColor: '#6419E6',
                confirmButtonText: 'OK',
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                title: 'Gagal',
                text: @foreach ($errors->all() as $error) '{{ $error }}' @endforeach,
                icon: 'error',
                confirmButtonColor: '#6419E6',
                confirmButtonText: 'OK',
            })
        @endif
    </script>
@endsection
