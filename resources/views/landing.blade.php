<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>| BK Sistem Pendukung Keputusan Prestasi Siswa SMA Negeri 1 Pronojiwo</title>
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
                        <span class="ml-2 text-xl font-bold text-indigo-600">BK Sistem Pendukung Keputusan Prestasi Siswa</span>
                    </div>
                </div>

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
                            <span class="block">BK Sistem Pendukung Keputusan</span>
                            <span class="block text-indigo-200">Prestasi Siswa SMA Negeri 1 Pronojiwo</span>
                        </h1>
                        <p class="mt-3 text-base text-indigo-100 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Menggunakan metode AHP dan TOPSIS untuk menentukan perankingan siswa secara objektif berdasarkan kriteria penilaian multidimensi, dengan dukungan Bimbingan dan Konseling untuk pengembangan potensi siswa.
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-md shadow">
                                <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10">
                                    Mulai Analisis
                                </a>
                            </div>

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
                    Solusi Komprehensif Analisis Prestasi Siswa dengan Dukungan BK
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                    Sistem kami menggabungkan dua metode pengambilan keputusan terbaik untuk hasil yang optimal, serta integrasi dengan layanan Bimbingan dan Konseling untuk mendukung perkembangan siswa.
                </p>
            </div>

            <div class="mt-10">
                <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Feature 1 -->
                    
                    <!-- Feature 2 -->
                    <div class="feature-card transition-all duration-300 ease-in-out rounded-lg bg-gray-50 p-6 shadow-md">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <i data-feather="trending-up"></i>
                        </div>
                        <div class="mt-5">
                            <h3 class="text-lg font-medium text-gray-900">Metode TOPSIS</h3>
                            <p class="mt-2 text-base text-gray-500">
                                Technique for Order Preference by Similarity to Ideal Solution untuk perankingan alternatif berdasarkan kedekatan dengan solusi ideal, mendukung rekomendasi BK.
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
                                Dashboard interaktif dengan grafik dan tabel untuk memahami hasil analisis secara komprehensif, memudahkan BK dalam monitoring siswa.
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
                                Generate laporan analisis lengkap dalam format PDF dengan satu klik untuk dokumentasi dan laporan BK.
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
                                Sistem mendukung multi-role (admin, guru BK, wali kelas) dengan hak akses berbeda untuk kolaborasi efektif.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 6 -->
                    <div class="feature-card transition-all duration-300 ease-in-out rounded-lg bg-gray-50 p-6 shadow-md">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <i data-feather="heart"></i>
                        </div>
                        <div class="mt-5">
                            <h3 class="text-lg font-medium text-gray-900">Rekomendasi BK</h3>
                            <p class="mt-2 text-base text-gray-500">
                                Berdasarkan hasil analisis, sistem memberikan rekomendasi konseling personal untuk pengembangan potensi dan dukungan siswa.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Footer -->
    <footer class="relative z-10 bg-gray-800">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">

            <div class="mt-12 border-t border-gray-700 pt-8">
                <p class="text-gray-400 text-sm text-center">
                    &copy; 2025 BK Sistem Pendukung Keputusan Prestasi Siswa. All rights reserved. SMA Negeri 1 Pronojiwo.
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
