@extends('dashboard.layouts.app')

@section('container')
<style>
  html {
    font-size: 14px;
  }
  .card-container {
    display: flex;
    flex-wrap: wrap; /* agar card lanjut ke bawah setelah 4 card */
    gap: 1.5rem; /* jarak antar card */
  }
  .card-custom {
    flex: 1 1 calc(25% - 1.5rem); /* 4 card per baris */
    min-width: 250px;
    max-width: 300px;
    min-height: 450px;
    box-shadow: 0 0 15px rgba(10, 10, 10, 0.3);
    border-radius: 10px;
    background-color: #fff;
  }
  .card-custom-img {
    height: 200px;
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
  }
  .card-custom-avatar img {
    border-radius: 50%;
    box-shadow: 0 0 15px rgba(10, 10, 10, 0.3);
    position: absolute;
    top: 100px;
    left: 1.25rem;
    width: 100px;
    height: 100px;
  }

  /* Responsif untuk layar kecil */
  @media (max-width: 992px) {
    .card-custom {
      flex: 1 1 calc(33.33% - 1.5rem); /* 3 per baris */
    }
  }
  @media (max-width: 768px) {
    .card-custom {
      flex: 1 1 calc(50% - 1.5rem); /* 2 per baris */
    }
  }
  @media (max-width: 576px) {
    .card-custom {
      flex: 1 1 100%; /* 1 per baris di HP */
    }
  }
</style>

<div class="container-fluid py-4" style="background-color: #f6ffdf; min-height: 100vh;">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Manajemen Kelas XI</h3>
    <div>
      <a href="{{ route('kelas11.create') }}" class="btn btn-success me-2"><i class="bi bi-plus-circle me-1"></i>Add Class</a>

    </div>
  </div>

  <!-- Grid horizontal cards -->
  <div class="card-container">
    {{-- @php
      $kelas = range('A', 'L'); // menghasilkan array ['A', 'B', ..., 'L']
    @endphp --}}

    @forelse ($kelas11s as $variabelkelas11 )
    <div class="card card-custom position-relative">
        <div class="card-custom-img"
             style="background-image: url('https://res.cloudinary.com/d3/image/upload/c_scale,q_auto:good,w_1110/trianglify-v1-cs85g_cc5d2i.jpg');">
        </div>
        <div class="card-custom-avatar">
          <img class="img-fluid" src="https://res.cloudinary.com/d3/image/upload/c_pad,g_center,h_200,q_auto:eco,w_200/bootstrap-logo_u3c8dx.jpg" alt="Avatar" />
        </div>
        <div class="card-body mt-5" style="overflow-y: auto">
          <h4 class="card-title text-center">Kelas XI {{ $variabelkelas11->title }}</h4>
          <p class="card-text text-center">Jumlah siswa: {{ rand(20, 40) }}</p>
          <p class="card-text text-center"> {{ $variabelkelas11->description }} </p>
        </div>
        <div class="card-footer text-center" style="background: inherit; border-color: inherit;">
                    <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('kelas11.destroy', $variabelkelas11->id) }}" method="POST">
                                                <a href="{{ route('kelas11.show', $variabelkelas11->id) }}" class="btn btn-primary btn-sm">SHOW</a>
                                                                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">HAPUS</button>
                                            </form>
        </div>
      </div>
    @empty
<div class="alert alert-danger">
                                        Data Products belum ada.
                                    </div>
    @endforelse


  </div>
  {{ $kelas11s->links() }}
</div>
<script>
        //message with sweetalert
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
