@extends('dashboard.layouts.app')

@section('container')
<div class="container-fluid py-4" style="background-color: #f6ffdf; min-height: 100vh;">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Tambah Absensi Siswa ke Kelas {{ $modelkelas10->title }}</h3>
    <a href="{{ route('modelkelas10.show', $modelkelas10->id) }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
  </div>

  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-body">
          <form action="{{ route('absenkelas10.store') }}" method="POST">
            @csrf
            <input type="hidden" name="kelas10_id" value="{{ $modelkelas10->id }}">  <!-- Tambahkan hidden input untuk kelas ID -->

            <div class="mb-3">
              <label for="isi_kelas10_id" class="form-label">Pilih Siswa (Nama - NISN)</label>
              <select class="form-control @error('isi_kelas10_id') is-invalid @enderror" id="isi_kelas10_id" name="isi_kelas10_id" required>
                <option value="">-- Pilih Siswa --</option>
                @foreach($isiKelas10 as $siswa)
                  <option value="{{ $siswa->id }}" {{ old('isi_kelas10_id') == $siswa->id ? 'selected' : '' }}>
                    {{ $siswa->nama }} - {{ $siswa->nisn }}
                  </option>
                @endforeach
              </select>
              @error('isi_kelas10_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="semester" class="form-label">Semester</label>
              <select class="form-control @error('semester') is-invalid @enderror" id="semester" name="semester" required>
                <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>Semester 1</option>
                <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>Semester 2</option>
              </select>
              @error('semester')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="bulan" class="form-label">Bulan</label>
              <select class="form-control @error('bulan') is-invalid @enderror" id="bulan" name="bulan" required>
                @php
                  $bulanList = [
                    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                  ];
                @endphp
                @foreach($bulanList as $b)
                  <option value="{{ $b }}" {{ old('bulan') == $b ? 'selected' : '' }}>{{ $b }}</option>
                @endforeach
              </select>
              @error('bulan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="tahun" class="form-label">Tahun</label>
              <select class="form-control @error('tahun') is-invalid @enderror" id="tahun" name="tahun" required>
                @for ($i = 2020; $i <= date('Y'); $i++)
                  <option value="{{ $i }}" {{ old('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
              </select>
              @error('tahun')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <button type="submit" class="btn btn-success">Tambah Absensi</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Message with sweetalert
  @if(session('success'))
    Swal.fire({
      icon: "success",
      title: "BERHASIL",
      text: "{{ session('success') }}",
      showConfirmButton: false,
      timer: 2000
    });
  @elseif(session('error'))
    Swal.fire({
      icon: "error",
      title: "GAGAL!",
      text: "{{ session('error') }}",
      showConfirmButton: false,
      timer: 2000
    });
  @endif
</script>

@endsection
