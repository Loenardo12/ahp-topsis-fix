<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sistem Pendukung Keputusan | TOPSIS</title>

    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/logo.jpg') }}" />
    <link rel="icon" type="image/png" href="{{ asset('img/logo.jpg') }}" />
    <!-- Tambahkan ini di bagian <head> file dashboard.layouts.app -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('dashboard.layouts.link')
    @yield('css')

    {{-- <style>
       .modal-backdrop.show {
    z-index: 9999 !important;
    opacity: 0.5 !important; /* Atur gelap terang backdrop */
}

/* Pastikan modal muncul di atas backdrop */
.modal.show {
    z-index: 10000 !important;
    display: flex !important; /* Pastikan modal benar-benar muncul */
    align-items: center;
    justify-content: center;
    /* Jika modal tidak muncul karena overflow, uncomment baris di bawah */
    /* overflow: visible !important; */
}

/* Isi modal juga perlu z-index tinggi */
.modal-content {
    z-index: 10001 !important;
    position: relative;
}

/* Reset z-index elemen layout umum Tailwind/template */
main, .main, .sidebar, .navbar, .card, .card-body, .table-responsive, .container, .container-fluid, .fixed, .absolute {
    z-index: auto !important;
    /* Jika elemen layout menggunakan transform, ini bisa mengganggu z-index */
    /* transform: none !important; */
}

/* Jika modal tetap tidak muncul, coba tambahkan ini untuk memaksa modal muncul */
div.modal[style*="display: block"] {
    z-index: 10000 !important;
}
    </style> --}}
</head>

<body
    class="font-lato antialiased font-normal text-base leading-default bg-backgroundPrimary text-greenPrimary scrollbar-thin scrollbar-thumb-greenPrimary scrollbar-track-greenPrimary/60 scrollbar-thumb-rounded-full hover:scrollbar-thumb-greenPrimary/80 transition-all">
    @include('dashboard.layouts.sidebar')

    <main class="ease-soft-in-out xl:ml-68.5 relative h-screen rounded-xl transition-all duration-200">
        @include('dashboard.layouts.navbar')
        <div class="w-full px-6 py-6 mx-auto bg-backgroundPrimary">
            @yield('container')
            @include('dashboard.layouts.footer')
        </div>
    </main>

    <!-- 1. jQuery (Dibutuhkan oleh DataTable, Select2, dll) -->
    <script src="{{ asset('js/jquery-3.7.0.min.js') }}"></script>



    <!-- 2. Bootstrap JS (Harus setelah jQuery jika plugin jQuery mengandalkan Bootstrap) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- 3. Plugin lain (urutan bisa penting) -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>

    <!-- 4. Script kustom Anda -->
    <script src="{{ asset('js/dropdown.js') }}"></script>
    <script src="{{ asset('js/navbar-sticky.js') }}"></script>
    <script src="{{ asset('js/sidenav-burger.js') }}"></script>
    <script src="{{ asset('js/nav-pills.js') }}"></script>

    <!-- Hapus include script karena kita sudah masukkan satu per satu -->
    {{-- <!-- @include('dashboard.layouts.script') --> --}}

    @yield('js')
</body>

</html>
