{{-- resources/views/dashboard/alternatif/index.blade.php --}}

@extends('dashboard.layouts.app')

@section('container')
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex flex-row items-center justify-between p-6 pb-0 mb-4 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <h6>Tabel {{ $judul }}<p>Masukan Siswa yang dipilih untuk dihitung mana yang terbaik </p></h6>
                    <div class="flex items-center space-x-2"> <!-- Wrapper untuk tombol -->
                        <button id="hapus-multiple-btn" class="cursor-pointer inline-block px-3 py-2 font-bold text-center text-white rounded-lg text-sm ease-soft-in shadow-soft-md bg-gradient-to-br from-red-500 to-red-700 shadow-soft-md hover:shadow-soft-xs active:opacity-85 hover:scale-102 transition-all" disabled>
                            <i class="ri-delete-bin-line"></i> <!-- Ikon hapus -->
                            Hapus Terpilih
                        </button>
                        <label for="add_button" class="cursor-pointer inline-block px-3 py-2 font-bold text-center text-white rounded-lg text-sm ease-soft-in shadow-soft-md bg-gradient-to-br from-greenPrimary to-greenPrimary/80 shadow-soft-md hover:shadow-soft-xs active:opacity-85 hover:scale-102 transition-all">
                            <i class="ri-add-fill"></i>
                            Tambah {{ $judul }}
                        </label>
                    </div>
                </div>
                <div id='recipients' class="p-8 mt-6 lg:mt-0 rounded shadow bg-white">
                    <table id="tabel_data" class="stripe hover" style="width:100%; padding-top: 1em; padding-bottom: 1em;">
                        <thead>
                            <tr>
                                <th> <!-- Kolom checkbox header -->
                                    <input type="checkbox" id="check-all" class="form-check-input" />
                                </th>
                                <th>Nama</th>
                                <th>Kelas</th> <!-- Kolom baru untuk kelas -->
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td> <!-- Kolom checkbox baris -->
                                        <input type="checkbox" name="selected_ids[]" value="{{ $item->id }}" class="row-check form-check-input" />
                                    </td>
                                    <td>{{ $item->objek->nama }}</td>
                                    <td>{{ $item->kelas_nama }}</td> <!-- Tampilkan kelas di sini -->
                                    <td class="flex gap-x-3">
                                        <button onclick="return delete_button('{{ $item->id }}', '{{ $item->objek->nama }}');">
                                            <i class="ri-delete-bin-line text-xl"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Form Tambah Data --}}
            <input type="checkbox" id="add_button" class="modal-toggle" />
            <div class="modal">
                <div class="modal-box">
                    <form action="{{ route('alternatif.simpan') }}" method="post" enctype="multipart/form-data">
                        <h3 class="font-bold text-lg">Tambah {{ $judul }}</h3>
                            @csrf
                            <div class="form-control w-full max-w-xs">
                                <label class="label">
                                    <span class="label-text">Pilih Objek</span>
                                </label>
                                <div class="flex justify-between items-center mb-2"> <!-- Wrapper untuk tombol dan label -->
                                    <button type="button" id="select_all_btn" class="btn btn-xs btn-outline btn-info">Pilih Semua</button>
                                    <!-- Optional: Tambahkan tombol Batal Pilih Semua -->
                                    <!-- <button type="button" id="deselect_all_btn" class="btn btn-xs btn-outline btn-warning ml-2">Batal Pilih Semua</button> -->
                                </div>
                                <select class="select select-bordered text-dark" name="objek_id[]" id="objek_id" multiple="multiple">
                                    {{-- <option disabled selected>Pilih Objek!</option> --}}
                                    @foreach ($objek as $item)
                                        @if (old('objek_id') == $item->id)
                                            <option value="{{ $item->id }}" selected>{{ $item->nama }}</option>
                                        @else
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <label class="label">
                                    @error('objek_id')
                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                    @enderror
                                </label>
                            </div>
                        <div class="modal-action">
                            <button type="submit" class="btn btn-success">Simpan</button>
                            <label for="add_button" class="btn">Batal</label>
                        </div>
                    </form>
                </div>
                <label class="modal-backdrop" for="add_button">Close</label>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        // Tabel
        $(document).ready(function() {
            // Inisialisasi DataTables
            const table = $('#tabel_data').DataTable({
                responsive: true,
                order: [],
                 lengthChange: false,
                paging: false,
            });

            // Inisialisasi Select2
            const $select2Element = $("#objek_id").select2({
                placeholder: "Select",
                allowClear: true
            });

            // Fungsi untuk tombol Pilih Semua (Select2)
            $('#select_all_btn').on('click', function() {
                const allValues = $select2Element.find('option').map(function() {
                    return this.value;
                }).get();
                $select2Element.val(allValues);
                $select2Element.trigger('change.select2');
            });

            // --- LOGIKA CHECKBOX MASSAL DENGAN DATATABLES API ---

            const $checkAll = $('#check-all'); // Checkbox header
            const $hapusMultipleBtn = $('#hapus-multiple-btn'); // Tombol hapus massal

            // Fungsi untuk memperbarui status tombol hapus
            function updateHapusMultipleBtnStatus() {
                // Hitung jumlah checkbox baris yang dicentang di semua halaman yang SESUAI PENCARIAN
                const checkedCount = table.rows({ search: 'applied' }).nodes().to$().find('input.row-check:checked').length;
                $hapusMultipleBtn.prop('disabled', checkedCount === 0); // Nonaktifkan jika tidak ada yang dipilih
            }

            // Event listener untuk checkbox header
            $checkAll.on('change', function() {
                const isChecked = this.checked;
                // Temukan semua checkbox baris di semua halaman yang SESUAI PENCARIAN
                const $rowCheckboxes = table.rows({ search: 'applied' }).nodes().to$().find('input.row-check');
                $rowCheckboxes.prop('checked', isChecked); // Centang atau hapus centang semua

                // Update status checkbox header (indeterminate jika sebagian dipilih)
                const allChecked = $rowCheckboxes.length === $rowCheckboxes.filter(':checked').length;
                $checkAll.prop('checked', allChecked);
                $checkAll.prop('indeterminate', !allChecked && $rowCheckboxes.filter(':checked').length > 0);

                updateHapusMultipleBtnStatus(); // Perbarui status tombol
            });

            // Event listener untuk checkbox baris (gunakan event delegation untuk menangani redraw)
            $('#tabel_data tbody').on('change', 'input.row-check', function() {
                // Update status checkbox header
                const $allRowCheckboxes = table.rows({ search: 'applied' }).nodes().to$().find('input.row-check');
                const allChecked = $allRowCheckboxes.length === $allRowCheckboxes.filter(':checked').length;
                const someChecked = $allRowCheckboxes.filter(':checked').length > 0;

                $checkAll.prop('checked', allChecked);
                $checkAll.prop('indeterminate', !allChecked && someChecked);

                updateHapusMultipleBtnStatus(); // Perbarui status tombol
            });

            // Event listener untuk tombol hapus massal
            $hapusMultipleBtn.on('click', function() {
                // Ambil ID dari semua checkbox baris yang dicentang di semua halaman yang SESUAI PENCARIAN
                const selectedIds = table.rows({ search: 'applied' }).nodes().to$().find('input.row-check:checked').map(function() {
                    return this.value;
                }).get(); // Konversi ke array

                if (selectedIds.length === 0) {
                    Swal.fire({
                        title: 'Tidak Ada Pilihan',
                        text: 'Silakan pilih setidaknya satu data untuk dihapus.',
                        icon: 'warning',
                        confirmButtonColor: '#6419E6',
                        confirmButtonText: 'OK',
                    });
                    return; // Hentikan eksekusi jika tidak ada yang dipilih
                }

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Anda akan menghapus ${selectedIds.length} data.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#6419E6',
                    cancelButtonColor: '#F87272',
                    confirmButtonText: 'Hapus!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Kirim permintaan AJAX ke server untuk menghapus data
                        $.ajax({
                            url: "{{ route('alternatif.hapus.multiple') }}", // Gunakan route yang telah Anda buat
                            type: 'POST',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "ids": selectedIds // Kirim array ID
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: response.message,
                                        icon: 'success',
                                        confirmButtonColor: '#6419E6',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        location.reload(); // Muat ulang halaman untuk memperbarui data
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: response.message || 'Terjadi kesalahan.',
                                        icon: 'error',
                                        confirmButtonColor: '#6419E6',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error("Error hapus massal:", error);
                                console.error("Response Text:", xhr.responseText);
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: 'Terjadi kesalahan saat menghapus data.',
                                    icon: 'error',
                                    confirmButtonColor: '#6419E6',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });

            // Event listener untuk redraw DataTables (pagination, search, etc.) untuk memperbarui status
            table.on('draw', function() {
                updateHapusMultipleBtnStatus();

                // Perbarui status checkbox header saat tabel dirender ulang
                const $allRowCheckboxes = table.rows({ search: 'applied' }).nodes().to$().find('input.row-check');
                const allChecked = $allRowCheckboxes.length === $allRowCheckboxes.filter(':checked').length;
                const someChecked = $allRowCheckboxes.filter(':checked').length > 0;

                $checkAll.prop('checked', allChecked);
                $checkAll.prop('indeterminate', !allChecked && someChecked);
            });


        }); // Akhir $(document).ready()


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

        function delete_button(id, nama) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                html:
                    "<p>Data tidak dapat dipulihkan kembali!</p>" +
                    "<div class='divider'></div>" +
                    "<b>Data: " + nama + "</b>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6419E6',
                cancelButtonColor: '#F87272',
                confirmButtonText: 'Hapus Data!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "{{ route('alternatif.hapus') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id
                        },
                        success: function (response) {
                            Swal.fire({
                                title: 'Data berhasil dihapus!',
                                icon: 'success',
                                confirmButtonColor: '#6419E6',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        },
                        error: function (response) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Data gagal dihapus!',
                            });
                        }
                    });
                }
            })
        }
    </script>
@endsection
