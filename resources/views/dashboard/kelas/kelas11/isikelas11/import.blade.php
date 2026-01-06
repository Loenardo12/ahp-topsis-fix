<!-- resources/views/dashboard/kelas/kelas11/isikelas11/import.blade.php -->

@extends('dashboard.layouts.app')

@section('container')
    <div class="container-fluid py-4" style="background-color: #f6ffdf; min-height: 100vh;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">Import Absensi dari Excel</h3>
            <a href="{{ url()->previous() }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
        </div>

        @if ($errors->any())
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="alert alert-danger">
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form id="import-form" action="{{ route('import.absen.process') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Hidden kelas11_id: pastikan dikirim dari controller -->
                            <input type="hidden" name="kelas11_id" value="{{ $kelas11->id ?? '' }}" required>

                            <div class="mb-3">
                                <label for="file_excel" class="form-label">Pilih File Excel</label>
                                <input type="file" class="form-control @error('file_excel') is-invalid @enderror"
                                    id="file_excel" name="file_excel" accept=".xlsx,.xls" required>
                                @error('file_excel')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Pilih Sheet -->
                            <div class="mb-3" id="sheet-selection" style="display: none;">
                                <label for="selected_sheet" class="form-label">Pilih Sheet</label>
                                <select class="form-control @error('selected_sheet') is-invalid @enderror"
                                    id="selected_sheet" name="selected_sheet">
                                    <option value="">-- Pilih Sheet --</option>
                                </select>
                                @error('selected_sheet')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Input info tambahan -->
                            <div class="mb-3" id="input-info" style="display: none;">
                                <h5>Isi Informasi Absensi</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="semester" class="form-label">Semester</label>
                                        <select class="form-control @error('semester') is-invalid @enderror" id="semester"
                                            name="semester">
                                            <option value="">Pilih</option>
                                            <option value="1">1 (Ganjil)</option>
                                            <option value="2">2 (Genap)</option>
                                        </select>
                                        @error('semester')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="bulan" class="form-label">Bulan</label>
                                        <select class="form-control @error('bulan') is-invalid @enderror" id="bulan"
                                            name="bulan">
                                            <option value="">Pilih</option>
                                            @php
                                                $bulanList = [
                                                    'Januari',
                                                    'Februari',
                                                    'Maret',
                                                    'April',
                                                    'Mei',
                                                    'Juni',
                                                    'Juli',
                                                    'Agustus',
                                                    'September',
                                                    'Oktober',
                                                    'November',
                                                    'Desember',
                                                ];
                                            @endphp
                                            @foreach ($bulanList as $b)
                                                <option value="{{ $b }}">{{ $b }}</option>
                                            @endforeach
                                        </select>
                                        @error('bulan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="tahun" class="form-label">Tahun</label>
                                        <select class="form-control @error('tahun') is-invalid @enderror" id="tahun"
                                            name="tahun">
                                            <option value="">Pilih</option>
                                            @for ($i = 2020; $i <= date('Y') + 1; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                        @error('tahun')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary" id="import-btn" disabled>Import Data</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const fileInput = document.getElementById('file_excel');
            const sheetSelectionDiv = document.getElementById('sheet-selection');
            const inputInfoDiv = document.getElementById('input-info');
            const sheetSelect = document.getElementById('selected_sheet');
            const importBtn = document.getElementById('import-btn');

            // Fungsi untuk mengecek apakah semua field sudah diisi
            function updateSubmitButtonState() {
                const semester = document.getElementById('semester')?.value;
                const bulan = document.getElementById('bulan')?.value;
                const tahun = document.getElementById('tahun')?.value;
                const sheet = sheetSelect?.value;
                const kelasId = document.querySelector('input[name="kelas11_id"]')?.value;

                const allFilled = semester && bulan && tahun && sheet && kelasId;
                importBtn.disabled = !allFilled;
            }

            // Pasang event listener ke semua input yang relevan
            ['semester', 'bulan', 'tahun', 'selected_sheet'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('change', updateSubmitButtonState);
                }
            });

            if (fileInput) {
                fileInput.addEventListener('change', function(event) {
                    const file = event.target.files[0];

                    if (file) {
                        sheetSelect.innerHTML = '<option value="">Memuat sheet...</option>';
                        sheetSelectionDiv.style.display = 'block';
                        inputInfoDiv.style.display = 'none';
                        importBtn.disabled = true;

                        const formData = new FormData();
                        formData.append('file_excel', file);
                        formData.append('_token', csrfToken);

                        fetch('{{ route('import.absen.getSheets') }}', {
                                method: 'POST',
                                body: formData,
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    sheetSelect.innerHTML =
                                        '<option value="">-- Pilih Sheet --</option>';
                                    data.sheets.forEach(sheet => {
                                        const option = document.createElement('option');
                                        option.value = sheet;
                                        option.textContent = sheet;
                                        sheetSelect.appendChild(option);
                                    });
                                    inputInfoDiv.style.display = 'block';
                                    updateSubmitButtonState(); // Periksa ulang setelah info muncul
                                } else {
                                    sheetSelect.innerHTML =
                                        `<option value="">Gagal: ${data.message || 'Tidak bisa baca sheet'}</option>`;
                                    inputInfoDiv.style.display = 'none';
                                    importBtn.disabled = true;
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching sheets:', error);
                                sheetSelect.innerHTML =
                                    '<option value="">Error: gagal memuat sheet</option>';
                                inputInfoDiv.style.display = 'none';
                                importBtn.disabled = true;
                            });
                    } else {
                        sheetSelectionDiv.style.display = 'none';
                        inputInfoDiv.style.display = 'none';
                        importBtn.disabled = true;
                    }
                });
            }
        });
    </script>

@endsection
