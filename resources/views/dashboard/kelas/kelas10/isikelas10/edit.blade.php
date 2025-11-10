@extends('dashboard.layouts.app')

@section('container')
<div class="container-fluid py-4" style="background-color: #f6ffdf; min-height: 100vh;">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Edit Absensi Siswa di Kelas {{ $kelas10_obj->title }}</h3>
    <a href="{{ route('kelas10.show', $kelas10_obj->id) }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
  </div>

  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card">
        <div class="card-body">
          <form action="{{ route('absenkelas10.update', $absenkelas10->id) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="kelas10_id" value="{{ $kelas10_obj->id }}">

            <div class="mb-3">
              <label for="nama" class="form-label">Nama Siswa</label>
              <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $absenkelas10->isikelas10->nama) }}" required>
              @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="nisn" class="form-label">NISN Siswa</label>
              <input type="text" class="form-control @error('nisn') is-invalid @enderror" id="nisn" name="nisn" value="{{ old('nisn', $absenkelas10->isikelas10->nisn) }}" required>
              @error('nisn')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="semester" class="form-label">Semester</label>
              <select class="form-control @error('semester') is-invalid @enderror" id="semester" name="semester" required>
                <option value="1" {{ old('semester', $absenkelas10->semester) == '1' ? 'selected' : '' }}>Semester 1</option>
                <option value="2" {{ old('semester', $absenkelas10->semester) == '2' ? 'selected' : '' }}>Semester 2</option>
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
                  <option value="{{ $b }}" {{ old('bulan', $absenkelas10->bulan) == $b ? 'selected' : '' }}>{{ $b }}</option>
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
                  <option value="{{ $i }}" {{ old('tahun', $absenkelas10->tahun) == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
              </select>
              @error('tahun')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <h5 class="mt-4">Absensi Harian</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Absensi (S/I/A/H)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 1; $i <= 31; $i++)
                            @php
                                $field = "tanggal_$i";
                                // Ambil nilai dari model $absenkelas10
                                $nilai = old($field, $absenkelas10->$field);
                            @endphp
                                                        <tr>
                                <td style="vertical-align: middle;">{{ $i }}</td>
                                <td>
                                    <select class="form-control" name="{{ $field }}">
                                        <option value="" {{ old($field, $absenkelas10->$field) == null || old($field, $absenkelas10->$field) === '' ? 'selected' : '' }}>-</option>
                                        <option value="S" {{ old($field, $absenkelas10->$field) == 'S' ? 'selected' : '' }}>S (Sakit)</option>
                                        <option value="I" {{ old($field, $absenkelas10->$field) == 'I' ? 'selected' : '' }}>I (Izin)</option>
                                        <option value="A" {{ old($field, $absenkelas10->$field) == 'A' ? 'selected' : '' }}>A (Alpha)</option>
                                        <option value="H" {{ old($field, $absenkelas10->$field) == 'H' ? 'selected' : '' }}>H (Hadir)</option>
                                    </select>
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn btn-primary">Update Absensi</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
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
