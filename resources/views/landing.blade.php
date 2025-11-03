<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>| Sistem Pendukung Keputusan Prestasi Siswa</title>
    <link rel="icon" type="image/x-icon" href="/static/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.globe.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
        }
        .hero-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div id="vanta-globe" class="fixed top-0 left-0 w-full h-full z-0"></div>

    <!-- Navigation -->
    <nav class="relative z-10 bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <i data-feather="award" class="text-indigo-600 h-8 w-8"></i>
                        <span class="ml-2 text-xl font-bold text-indigo-600">Sistem Pendukung Keputusan Prestasi Siswa</span>
                    </div>
                </div>
                {{-- <div class="hidden md:ml-6 md:flex md:items-center md:space-x-8">
                    <a href="#" class="text-indigo-600 font-medium hover:text-indigo-500">Beranda</a>
                    <a href="#" class="text-gray-500 hover:text-gray-700 font-medium">Tentang</a>
                    <a href="#" class="text-gray-500 hover:text-gray-700 font-medium">Metodologi</a>
                    <a href="#" class="text-gray-500 hover:text-gray-700 font-medium">Hasil Analisis</a>
                    <a href="#" class="px-4 py-2 border border-transparent rounded-md text-white bg-indigo-600 hover:bg-indigo-700 font-medium">
                        Masuk
                    </a>
                </div> --}}
                <div class="-mr-2 flex items-center md:hidden">
                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                        <i data-feather="menu"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative z-10 hero-gradient overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 lg:mt-16 lg:px-8 xl:mt-20">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl">
                            <span class="block">Sistem Pendukung Keputusan</span>
                            <span class="block text-indigo-200">Prestasi Siswa SMA Negeri 1 Pronojiwo</span>
                        </h1>
                        <p class="mt-3 text-base text-indigo-100 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Menggunakan metode AHP dan TOPSIS untuk menentukan perankingan siswa secara objektif berdasarkan kriteria penilaian multidimensi.
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-md shadow">
                                <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10">
                                    Mulai Analisis
                                </a>
                            </div>
                            {{-- <div class="mt-3 sm:mt-0 sm:ml-3">
                                <a href="#" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-500 bg-opacity-60 hover:bg-opacity-70 md:py-4 md:text-lg md:px-10">
                                    Pelajari Metode
                                </a>
                            </div> --}}
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
            <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="http://static.photos/education/1200x630/42" alt="Students learning">
        </div>
    </div>

    <!-- Features Section -->
    <div class="relative z-10 py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Fitur Unggulan</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Solusi Komprehensif Analisis Prestasi Siswa
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                    Sistem kami menggabungkan dua metode pengambilan keputusan terbaik untuk hasil yang optimal
                </p>
            </div>

            <div class="mt-10">
                <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Feature 1 -->
                    <div class="feature-card transition-all duration-300 ease-in-out rounded-lg bg-gray-50 p-6 shadow-md">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <i data-feather="layers"></i>
                        </div>
                        <div class="mt-5">
                            <h3 class="text-lg font-medium text-gray-900">Metode AHP</h3>
                            <p class="mt-2 text-base text-gray-500">
                                Analytical Hierarchy Process untuk menentukan bobot kriteria secara konsisten dengan pairwise comparison matrix.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="feature-card transition-all duration-300 ease-in-out rounded-lg bg-gray-50 p-6 shadow-md">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <i data-feather="trending-up"></i>
                        </div>
                        <div class="mt-5">
                            <h3 class="text-lg font-medium text-gray-900">Metode TOPSIS</h3>
                            <p class="mt-2 text-base text-gray-500">
                                Technique for Order Preference by Similarity to Ideal Solution untuk perankingan alternatif berdasarkan kedekatan dengan solusi ideal.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="feature-card transition-all duration-300 ease-in-out rounded-lg bg-gray-50 p-6 shadow-md">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <i data-feather="bar-chart-2"></i>
                        </div>
                        <div class="mt-5">
                            <h3 class="text-lg font-medium text-gray-900">Visualisasi Data</h3>
                            <p class="mt-2 text-base text-gray-500">
                                Dashboard interaktif dengan grafik dan tabel untuk memahami hasil analisis secara komprehensif.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 4 -->
                    <div class="feature-card transition-all duration-300 ease-in-out rounded-lg bg-gray-50 p-6 shadow-md">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <i data-feather="file-text"></i>
                        </div>
                        <div class="mt-5">
                            <h3 class="text-lg font-medium text-gray-900">Laporan Otomatis</h3>
                            <p class="mt-2 text-base text-gray-500">
                                Generate laporan analisis lengkap dalam format PDF dengan satu klik untuk dokumentasi.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 5 -->
                    <div class="feature-card transition-all duration-300 ease-in-out rounded-lg bg-gray-50 p-6 shadow-md">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <i data-feather="users"></i>
                        </div>
                        <div class="mt-5">
                            <h3 class="text-lg font-medium text-gray-900">Multi-User</h3>
                            <p class="mt-2 text-base text-gray-500">
                                Sistem mendukung multi-role (admin, guru, wali kelas) dengan hak akses berbeda.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 6 -->
                    <div class="feature-card transition-all duration-300 ease-in-out rounded-lg bg-gray-50 p-6 shadow-md">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <i data-feather="cloud"></i>
                        </div>
                        <div class="mt-5">
                            <h3 class="text-lg font-medium text-gray-900">Cloud-Based</h3>
                            <p class="mt-2 text-base text-gray-500">
                                Akses dari mana saja melalui browser tanpa perlu instalasi software tambahan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    {{-- <div class="relative z-10 bg-indigo-700">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
            <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                <span class="block">Siap mengoptimalkan penilaian prestasi siswa?</span>
                <span class="block text-indigo-200">Mulai gunakan sistem kami hari ini.</span>
            </h2>
            <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
                <div class="inline-flex rounded-md shadow">
                    <a href="#" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50">
                        Daftar Sekarang
                    </a>
                </div>
                <div class="ml-3 inline-flex rounded-md shadow">
                    <a href="#" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 bg-opacity-60 hover:bg-opacity-70">
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Footer -->
    <footer class="relative z-10 bg-gray-800">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
            {{-- <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <div>
                    <h3 class="text-white text-lg font-semibold">EduRank Pro</h3>
                    <p class="mt-4 text-gray-300">
                        Sistem Pendukung Keputusan untuk analisis prestasi siswa SMA Negeri 1 Pronojiwo dengan metode AHP dan TOPSIS.
                    </p>
                    <div class="mt-4 flex space-x-6">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i data-feather="facebook"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i data-feather="twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i data-feather="instagram"></i>
                        </a>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-8 md:col-span-2">
                    <div>
                        <h3 class="text-white text-sm font-semibold tracking-wider uppercase">Navigasi</h3>
                        <ul class="mt-4 space-y-4">
                            <li><a href="#" class="text-gray-300 hover:text-white">Beranda</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white">Tentang</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white">Metodologi</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white">Hasil Analisis</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-white text-sm font-semibold tracking-wider uppercase">Legal</h3>
                        <ul class="mt-4 space-y-4">
                            <li><a href="#" class="text-gray-300 hover:text-white">Kebijakan Privasi</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white">Syarat & Ketentuan</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white">FAQ</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white">Kontak</a></li>
                        </ul>
                    </div>
                </div>
            </div> --}}
            <div class="mt-12 border-t border-gray-700 pt-8">
                <p class="text-gray-400 text-sm text-center">
                    &copy; 2025 Sistem Pendukung Keputusan Prestasi Siswa. All rights reserved. SMA Negeri 1 Pronojiwo.
                </p>
            </div>
        </div>
    </footer>

    <script>
        // Initialize Vanta.js globe
        VANTA.GLOBE({
            el: "#vanta-globe",
            mouseControls: true,
            touchControls: true,
            gyroControls: false,
            minHeight: 200.00,
            minWidth: 200.00,
            scale: 1.00,
            scaleMobile: 1.00,
            color: 0x4f46e5,
            backgroundColor: 0xf8fafc,
            size: 0.8
        });

        // Initialize feather icons
        feather.replace();
    </script>
</body>
</html>
