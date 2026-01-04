{{-- resources/views/dashboard/penilaian/import.blade.php --}}

@extends('dashboard.layouts.app')

@section('container')
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="card shadow-lg p-8 bg-white rounded-2xl">
                <h3 class="font-bold text-lg mb-4">Import {{ $judul }}</h3>

                <form id="import-form" action="{{ route('penilaian.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-control w-full max-w-xs mb-4">
                        <label class="label">
                            <span class="label-text">Pilih File Excel (.xlsx, .xls, .csv)</span>
                        </label>
                        <input type="file" name="file" id="excel-file" accept=".xlsx,.xls,.csv" class="file-input file-input-bordered w-full max-w-xs text-dark" required />
                        <label class="label">
                            @error('file')
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>

                    <!-- Dropdown Kriteria Tujuan (dihidupkan setelah file dipilih) -->
                    <div id="kriteria-selection" class="form-control w-full max-w-xs mb-4" style="display: none;">
                        <label class="label">
                            <span class="label-text">Pilih Kriteria Tujuan</span>
                        </label>
                        <select name="target_kriteria_id" id="kriteria-select" class="select select-bordered w-full max-w-xs text-dark" required>
                            <option value="" disabled selected>Pilih kriteria...</option>
                            @foreach ($kriterias as $kriteria)
                                <option value="{{ $kriteria->id }}">{{ $kriteria->nama }} (Bobot: {{ $kriteria->bobot }})</option>
                            @endforeach
                        </select>
                        <label class="label">
                            @error('target_kriteria_id')
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>


                    <!-- Dropdown Sheet (SELALU MUNCUL, diisi setelah file dipilih) -->
                    <div id="sheet-selection" class="form-control w-full max-w-xs mb-4">
                        <label class="label">
                            <span class="label-text">Pilih Sheet</span>
                        </label>
                        <select name="sheet_name" id="sheet-select" class="select select-bordered w-full max-w-xs text-dark">
                            <option value="" disabled selected>Pilih sheet...</option>
                            <!-- Opsi sheet akan diisi oleh JavaScript -->
                        </select>
                        <label class="label">
                            @error('sheet_name')
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>


                    <div class="mb-4 text-sm text-gray-500">
                        <p><strong>Catatan:</strong></p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>File harus memiliki kolom <code>Nama</code> (huruf besar atau kecil) yang berisi nama siswa yang sesuai dengan data di sistem.</li>
                            <li>File juga harus memiliki kolom <code>Rata-rata Nilai</code> (atau bentuk lain seperti <code>Rata rata Nilai</code>, <code>Ratarata Nilai</code>) yang berisi nilai numerik antara 0 dan 100.</li>
                            <li>Nilai dari kolom <code>Rata-rata Nilai</code> akan diimpor ke kriteria yang dipilih di bawah.</li>
                        </ul>
                    </div>

                    <div class="modal-action">
                        <button type="submit" class="btn btn-success" id="import-button" disabled>Import</button>
                        <a href="{{ route('penilaian') }}" class="btn">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fileInput = document.getElementById('excel-file');
            const kriteriaSelectionDiv = document.getElementById('kriteria-selection');
            const kriteriaSelect = document.getElementById('kriteria-select');
            const sheetSelectionDiv = document.getElementById('sheet-selection');
            const sheetSelect = document.getElementById('sheet-select');
            const importButton = document.getElementById('import-button');
            const form = document.getElementById('import-form');

            // Ambil CSRF token dari form utama
            const csrfTokenInput = form.querySelector('input[name="_token"]');
            if (!csrfTokenInput) {
                console.error("CSRF token tidak ditemukan di form.");
                return;
            }
            const csrfToken = csrfTokenInput.value;

            fileInput.addEventListener('change', function (e) {
                const file = e.target.files[0];

                if (file) {
                    // Reset dropdown kriteria dan sheet
                    kriteriaSelect.selectedIndex = 0;
                    sheetSelect.innerHTML = '<option value="" disabled selected>Pilih sheet...</option>';
                    kriteriaSelectionDiv.style.display = 'block';
                    importButton.disabled = true;

                    // Kirim file dan token ke route untuk membaca sheet
                    const formData = new FormData();
                    formData.append('file', file);
                    formData.append('_token', csrfToken);

                    fetch('{{ route("penilaian.getSheets") }}', {
                            method: 'POST',
                            body: formData,
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(errorData => {
                                    throw new Error(`HTTP error! status: ${response.status}, message: ${errorData.message || 'Unknown error'}`);
                                }).catch(jsonError => {
                                    throw new Error(`HTTP error! status: ${response.status}, statusText: ${response.statusText}`);
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success && data.sheets && data.sheets.length > 0) {
                                sheetSelect.innerHTML = '<option value="" disabled selected>Pilih sheet...</option>';
                                data.sheets.forEach(function (sheetName) {
                                    const option = document.createElement('option');
                                    option.value = sheetName;
                                    option.textContent = sheetName;
                                    sheetSelect.appendChild(option);
                                });
                                sheetSelect.disabled = false;
                            } else {
                                console.warn('Gagal membaca sheet dari file atau file tidak memiliki sheet: ', data.message || 'Data tidak valid.');
                                sheetSelect.innerHTML = '<option value="" disabled selected>Tidak ada sheet ditemukan</option>';
                                sheetSelect.disabled = true;
                            }
                            importButton.disabled = true;
                        })
                        .catch(error => {
                            console.error('Error fetching sheets:', error);
                            sheetSelect.innerHTML = '<option value="" disabled selected>Error membaca sheet</option>';
                            sheetSelect.disabled = true;
                        });

                } else {
                    kriteriaSelectionDiv.style.display = 'none';
                    sheetSelect.innerHTML = '<option value="" disabled selected>Pilih sheet...</option>';
                    sheetSelect.disabled = false;
                    importButton.disabled = true;
                }
            });

            // Event listener untuk dropdown kriteria dan sheet (untuk mengaktifkan tombol submit)
            kriteriaSelect.addEventListener('change', function() {
                const kriteriaDipilih = !!kriteriaSelect.value;
                const sheetDipilih = !!sheetSelect.value;
                const sheetAktif = !sheetSelect.disabled;

                if (kriteriaDipilih && sheetAktif && sheetDipilih) {
                    importButton.disabled = false;
                } else {
                    importButton.disabled = true;
                }
            });

            sheetSelect.addEventListener('change', function() {
                const kriteriaDipilih = !!kriteriaSelect.value;
                const sheetDipilih = !!sheetSelect.value;
                const sheetAktif = !sheetSelect.disabled;

                if (kriteriaDipilih && sheetAktif && sheetDipilih) {
                    importButton.disabled = false;
                } else {
                    importButton.disabled = true;
                }
            });

            // Validasi tambahan: pastikan kriteria dan sheet dipilih sebelum submit
            form.addEventListener('submit', function(e) {
                if (kriteriaSelectionDiv.style.display === 'block' && !kriteriaSelect.value) {
                    e.preventDefault();
                    alert('Silakan pilih kriteria terlebih dahulu.');
                    return false;
                }

                if (sheetSelect.disabled) {
                     e.preventDefault();
                     alert('Tidak dapat membaca sheet dari file. Silakan pilih file yang valid.');
                     return false;
                }

                if (!sheetSelect.value) {
                    e.preventDefault();
                    alert('Silakan pilih sheet terlebih dahulu.');
                    return false;
                }
            });

        });
    </script>
@endsection
