<!-- SIDEBAR -->
<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-xl transform -translate-x-full xl:translate-x-0 transition-transform duration-300 ease-in-out overflow-y-auto border-r border-gray-200 dark:border-gray-700">
    <!-- Header -->
    <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex flex-col items-center text-center">
            <h2 class="text-lg font-semibold text-green-600 dark:text-green-400">
                SPK Sistem
            </h2>

            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-20 w-20 rounded-full my-3">

            <h4 class="text-gray-500 dark:text-gray-400">
                Prestasi Siswa SMA Negeri 1 Pronojiwo
            </h4>
        </div>

        <button id="toggleSidebar"
            class="xl:hidden text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors">
            <i class="ri-close-line text-2xl"></i>
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="mt-4">
        <ul class="space-y-2 px-4">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-green-50 dark:hover:bg-gray-700 transition-colors {{ Request::is('dashboard') ? 'bg-green-100 dark:bg-gray-700 text-green-700 dark:text-green-400' : 'text-gray-700 dark:text-gray-300' }}">
                    <i class="ri-home-smile-fill mr-3 text-green-600 dark:text-green-400"></i>
                    Dashboard
                </a>
            </li>

            <!-- Master Data Section -->

            <li class="mt-6">
                <h6 class="px-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Master Data
                </h6>
            </li>

            @if(auth()->check() && auth()->user()->role && auth()->user()->role->role_name === 'superadmin')
            <li>
                <a href="{{ route('users.index') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-green-50 dark:hover:bg-gray-700 transition-colors {{ Request::is('dashboard/users*') ? 'bg-green-100 dark:bg-gray-700 text-green-700 dark:text-green-400' : 'text-gray-700 dark:text-gray-300' }}">
                    <i class="ri-user-3-fill mr-3 text-green-600 dark:text-green-400"></i>
                    Data User
                </a>
            </li>
            @endif
            <li class="relative">
                <button id="dropdown-ahp"
                    class="flex items-center justify-between w-full px-4 py-3 text-sm font-medium rounded-lg hover:bg-green-50 dark:hover:bg-gray-700 transition-colors text-gray-700 dark:text-gray-300">
                    <span class="flex items-center">
                        <i class="ri-user-settings-fill mr-3 text-green-600 dark:text-green-400"></i>
                        Input Kelas
                    </span>
                    <i class="ri-arrow-down-s-line transition-transform duration-200" id="arrow-ahp"></i>
                </button>
                <ul id="submenu-ahp" class="hidden ml-6 mt-2 space-y-1">
                    <li>
                        <a href="/kelas10"
                            class="flex items-center px-4 py-2 text-sm rounded-lg hover:bg-green-50 dark:hover:bg-gray-700 transition-colors {{ Request::is('dashboard/alternatif*') ? 'bg-green-100 dark:bg-gray-700 text-green-700 dark:text-green-400 font-semibold' : 'text-gray-600 dark:text-gray-400' }}">
                            <i class="ri-user-settings-fill mr-3 text-green-600 dark:text-green-400"></i>
                            Input Kelas 10
                        </a>
                    </li>
                    <li>
                        <a href="/kelas11"
                            class="flex items-center px-4 py-2 text-sm rounded-lg hover:bg-green-50 dark:hover:bg-gray-700 transition-colors {{ Request::is('dashboard/penilaian*') ? 'bg-green-100 dark:bg-gray-700 text-green-700 dark:text-green-400 font-semibold' : 'text-gray-600 dark:text-gray-400' }}">
                            <i class="ri-user-settings-fill mr-3 text-green-600 dark:text-green-400"></i>
                            Input Kelas 11
                        </a>
                    </li>
                    <li>
                        <a href="/kelas12"
                            class="flex items-center px-4 py-2 text-sm rounded-lg hover:bg-green-50 dark:hover:bg-gray-700 transition-colors {{ Request::is('dashboard/perhitungan*') ? 'bg-green-100 dark:bg-gray-700 text-green-700 dark:text-green-400 font-semibold' : 'text-gray-600 dark:text-gray-400' }}">
                            <i class="ri-user-settings-fill mr-3 text-green-600 dark:text-green-400"></i>
                            Input Kelas 12
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="{{ route('objek') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-green-50 dark:hover:bg-gray-700 transition-colors {{ Request::is('dashboard/objek*') ? 'bg-green-100 dark:bg-gray-700 text-green-700 dark:text-green-400' : 'text-gray-700 dark:text-gray-300' }}">
                    <i class="ri-user-3-fill mr-3 text-green-600 dark:text-green-400"></i>
                    Pilih Kelas / beberapa siswa
                </a>
            </li>
            <li>
                <a href="{{ route('kriteria') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-green-50 dark:hover:bg-gray-700 transition-colors {{ Request::is('dashboard/kriteria*') ? 'bg-green-100 dark:bg-gray-700 text-green-700 dark:text-green-400' : 'text-gray-700 dark:text-gray-300' }}">
                    <i class="ri-table-fill mr-3 text-green-600 dark:text-green-400"></i>
                    Kriteria
                </a>
            </li>
            <li>
                <a href="{{ route('sub_kriteria') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-green-50 dark:hover:bg-gray-700 transition-colors {{ Request::is('dashboard/sub_kriteria*') ? 'bg-green-100 dark:bg-gray-700 text-green-700 dark:text-green-400' : 'text-gray-700 dark:text-gray-300' }}">
                    <i class="ri-collage-fill mr-3 text-green-600 dark:text-green-400"></i>
                    Nilai Kriteria
                </a>
            </li>

            <!-- SPK Method TOPSIS Section -->
            <li class="mt-6">
                <h6 class="px-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Metode
                    TOPSIS</h6>
            </li>
            <li class="relative">
                <button id="dropdown-topsis"
                    class="flex items-center justify-between w-full px-4 py-3 text-sm font-medium rounded-lg hover:bg-green-50 dark:hover:bg-gray-700 transition-colors text-gray-700 dark:text-gray-300">
                    <span class="flex items-center">
                        <i class="ri-function-line mr-3 text-green-600 dark:text-green-400"></i>
                        Perangkingan Nilai Akademis Siswa
                    </span>
                    <i class="ri-arrow-down-s-line transition-transform duration-200" id="arrow-topsis"></i>
                </button>
                <ul id="submenu-topsis" class="hidden ml-6 mt-2 space-y-1">


                    <li>
                        <a href="{{ route('alternatif') }}"
                            class="flex items-center px-4 py-2 text-sm rounded-lg hover:bg-green-50 dark:hover:bg-gray-700 transition-colors {{ Request::is('dashboard/alternatif*') ? 'bg-green-100 dark:bg-gray-700 text-green-700 dark:text-green-400 font-semibold' : 'text-gray-600 dark:text-gray-400' }}">
                            <i class="ri-braces-fill mr-3 text-green-600 dark:text-green-400"></i>
                            pemilihan Siswa
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('penilaian') }}"
                            class="flex items-center px-4 py-2 text-sm rounded-lg hover:bg-green-50 dark:hover:bg-gray-700 transition-colors {{ Request::is('dashboard/penilaian*') ? 'bg-green-100 dark:bg-gray-700 text-green-700 dark:text-green-400 font-semibold' : 'text-gray-600 dark:text-gray-400' }}">
                            <i class="ri-survey-fill mr-3 text-green-600 dark:text-green-400"></i>
                            Penilaian
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('perhitungan') }}"
                            class="flex items-center px-4 py-2 text-sm rounded-lg hover:bg-green-50 dark:hover:bg-gray-700 transition-colors {{ Request::is('dashboard/perhitungan*') ? 'bg-green-100 dark:bg-gray-700 text-green-700 dark:text-green-400 font-semibold' : 'text-gray-600 dark:text-gray-400' }}">
                            <i class="ri-calculator-fill mr-3 text-green-600 dark:text-green-400"></i>
                            Perhitungan
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Hasil Akhir Section -->
            <li class="mt-6">
                <h6 class="px-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Hasil</h6>
            </li>
            <li>
                <a href="{{ route('hasil_akhir') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-green-50 dark:hover:bg-gray-700 transition-colors {{ Request::is('dashboard/hasil_akhir*') ? 'bg-green-100 dark:bg-gray-700 text-green-700 dark:text-green-400' : 'text-gray-700 dark:text-gray-300' }}">
                    <i class="ri-bar-chart-2-fill mr-3 text-green-600 dark:text-green-400"></i>
                    Report Hasil Akhir
                </a>
            </li>
            <li>
                <a href="{{ route('hasilperhitunganakhir') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-green-50 dark:hover:bg-gray-700 transition-colors {{ Request::is('dashboard/hasil_akhir*') ? 'bg-green-100 dark:bg-gray-700 text-green-700 dark:text-green-400' : 'text-gray-700 dark:text-gray-300' }}">
                    <i class="ri-bar-chart-2-fill mr-3 text-green-600 dark:text-green-400"></i>
                    Report Perhitungan
                </a>
            </li>
             <li>
                <a href="/about"
                    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-green-50 dark:hover:bg-gray-700 transition-colors {{ Request::is('dashboard/hasil_akhir*') ? 'bg-green-100 dark:bg-gray-700 text-green-700 dark:text-green-400' : 'text-gray-700 dark:text-gray-300' }}">
                    <i class="ri-bar-chart-2-fill mr-3 text-green-600 dark:text-green-400"></i>
                    RABOUTANGAN
                </a>
            </li>

            <!-- Logout -->
            <li class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full px-4 py-3 text-sm font-medium rounded-lg hover:bg-red-50 dark:hover:bg-red-900 transition-colors text-gray-700 dark:text-gray-300">
                        <i class="ri-logout-box-line mr-3 text-red-600 dark:text-red-400"></i>
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</aside>

<!-- Overlay for Mobile -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 xl:hidden hidden" onclick="closeSidebar()">
</div>

<!-- Script Sidebar -->
<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const toggleBtn = document.getElementById('toggleSidebar');
    const dropdownAHP = document.getElementById('dropdown-ahp');
    const submenuAHP = document.getElementById('submenu-ahp');
    const arrowAHP = document.getElementById('arrow-ahp');
    const dropdownTOPSIS = document.getElementById('dropdown-topsis');
    const submenuTOPSIS = document.getElementById('submenu-topsis');
    const arrowTOPSIS = document.getElementById('arrow-topsis');

    // Toggle sidebar
    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    });

    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    }

    // Dropdown AHP
    dropdownAHP.addEventListener('click', () => {
        submenuAHP.classList.toggle('hidden');
        arrowAHP.classList.toggle('rotate-180');
    });

    // Dropdown TOPSIS
    dropdownTOPSIS.addEventListener('click', () => {
        submenuTOPSIS.classList.toggle('hidden');
        arrowTOPSIS.classList.toggle('rotate-180');
    });

    // Close dropdowns on outside click (optional enhancement)
    document.addEventListener('click', (e) => {
        if (!dropdownAHP.contains(e.target)) {
            submenuAHP.classList.add('hidden');
            arrowAHP.classList.remove('rotate-180');
        }
        if (!dropdownTOPSIS.contains(e.target)) {
            submenuTOPSIS.classList.add('hidden');
            arrowTOPSIS.classList.remove('rotate-180');
        }
    });
</script>
