<!-- resources/views/dashboard/kelas/kelas12/isikelas12/import.blade.php -->

@extends('dashboard.layouts.app')

@section('container')
<div class="container-fluid py-4" style="background-color: #f6ffdf; min-height: 100vh;">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Import Absensi dari Excel</h3>
    <a href="{{ url()->previous() }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
  </div>

  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-body">
          <form action="{{ route('import.absen.process') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
              <label for="file_excel" class="form-label">Pilih File Excel</label>
              <input type="file" class="form-control @error('file_excel') is-invalid @enderror" id="file_excel" name="file_excel" accept=".xlsx, .xls" required>
              @error('file_excel')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Div ini harus HILANG atau TIDAK TERLIHAT sebelum file dipilih -->
            <div class="mb-3" id="sheet-selection" style="display: none;">
              <label for="selected_sheet" class="form-label">Pilih Sheet</label>
              <!-- Select ini harus kosong sebelum diisi oleh JS -->
              <select class="form-control @error('selected_sheet') is-invalid @enderror" id="selected_sheet" name="selected_sheet" required>
                <option value="">-- Pilih Sheet --</option>
                <!-- Opsi sheet akan diisi oleh JavaScript -->
              </select>
              @error('selected_sheet')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Input hidden untuk ID kelas -->
            <input type="hidden" name="kelas12_id" value="1"> <!-- Ganti "1" dengan ID kelas yang sesuai -->

            <button type="submit" class="btn btn-primary" id="import-btn" disabled>Import Data</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Pastikan script ini ada dan tidak ada error sebelumnya
console.log("Script import.blade.php dimuat");

// Ambil token CSRF dari meta tag
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
console.log("CSRF Token ditemukan:", csrfToken); // Debug

// Pastikan elemen file input ditemukan
const fileInput = document.getElementById('file_excel');
console.log("Elemen file input ditemukan:", !!fileInput); // Debug

if (fileInput) {
    fileInput.addEventListener('change', function(event) {
        console.log("Event 'change' pada file input dipicu"); // Debug

        const file = event.target.files[0];
        const sheetSelectionDiv = document.getElementById('sheet-selection');
        const sheetSelect = document.getElementById('selected_sheet');
        const importBtn = document.getElementById('import-btn');

        if (file) {
            console.log("File dipilih:", file.name); // Debug
            sheetSelect.innerHTML = '<option value="">Memuat sheet...</option>';
            sheetSelectionDiv.style.display = 'block'; // Tampilkan div
            importBtn.disabled = true;

            const formData = new FormData();
            formData.append('file_excel', file);
            formData.append('_token', csrfToken); // Sertakan token CSRF

            fetch('{{ route("import.absen.getSheets") }}', {
                method: 'POST',
                body: formData,
            })
            .then(response => {
                console.log("Respons diterima, status:", response.status); // Debug
                return response.json();
            })
            .then(data => {
                console.log("Data JSON diterima:", data); // Debug

                if(data.success) {
                    sheetSelect.innerHTML = '<option value="">-- Pilih Sheet --</option>';
                    data.sheets.forEach(function(sheet) {
                        const option = document.createElement('option');
                        option.value = sheet;
                        option.textContent = sheet;
                        sheetSelect.appendChild(option);
                    });
                    importBtn.disabled = false;
                } else {
                    sheetSelect.innerHTML = '<option value="">Gagal: ' + (data.message || 'Unknown error') + '</option>';
                    importBtn.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error fetching sheets:', error); // Log error
                sheetSelect.innerHTML = '<option value="">Error: ' + error.message + '</option>';
                importBtn.disabled = true;
            });
        } else {
            console.log("File dibatalkan atau dihapus"); // Debug
            sheetSelectionDiv.style.display = 'none';
            importBtn.disabled = true;
        }
    });
} else {
    console.error("Elemen dengan ID 'file_excel' tidak ditemukan!");
}

</script>

@endsection
