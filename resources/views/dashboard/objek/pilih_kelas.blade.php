@extends('dashboard.layouts.app')

@section('container')
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex flex-row items-center justify-between p-6 pb-0 mb-4 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <h6>Pilih Kelas</h6>
                </div>
                <div class="p-8 mt-6 lg:mt-0 rounded shadow bg-white">
                    <h3 class="text-xl font-bold mb-4">Pilih Tingkat Kelas</h3>
                    <div class="flex gap-4 mb-8">
                        <button class="kelas-btn btn btn-primary w-32 h-32 flex flex-col items-center justify-center text-xl" data-tingkat="10">
                            <i class="ri-user-3-line text-3xl mb-2"></i>
                            Kelas X
                        </button>
                        <!-- Jika Anda punya model untuk XI dan XII, aktifkan ini -->
                        <!--
                        <button class="kelas-btn btn btn-primary w-32 h-32 flex flex-col items-center justify-center text-xl" data-tingkat="11">
                            <i class="ri-user-3-line text-3xl mb-2"></i>
                            Kelas XI
                        </button>
                        <button class="kelas-btn btn btn-primary w-32 h-32 flex flex-col items-center justify-center text-xl" data-tingkat="12">
                            <i class="ri-user-3-line text-3xl mb-2"></i>
                            Kelas XII
                        </button>
                        -->
                    </div>

                    <div id="kelas-list" class="hidden">
                        <h3 class="text-xl font-bold mb-4">Pilih Sub Kelas</h3>
                        <div id="kelas-items" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            <!-- Kelas-kelas akan dimuat di sini -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="kelas-form" method="POST" action="{{ route('objek.ambilSiswa') }}">
        @csrf
        <input type="hidden" name="kelas_id" id="kelas_id_input" value="">
    </form>
@endsection

@section('js')
    <script>
        document.querySelectorAll('.kelas-btn').forEach(button => {
            button.addEventListener('click', function() {
                const tingkat = this.getAttribute('data-tingkat');
                // Di sini, kita hanya menangani Kelas 10
                if (tingkat === '10') {
                    fetch('/api/kelas10') // Kita buat route API ini nanti
                        .then(response => response.json())
                        .then(data => {
                            const container = document.getElementById('kelas-items');
                            container.innerHTML = '';
                            data.forEach(kelas => {
                                const div = document.createElement('div');
                                div.className = 'kelas-item p-4 bg-gray-100 rounded-lg text-center cursor-pointer hover:bg-greenPrimary hover:text-white transition-colors';
                                div.innerHTML = `<h4 class="font-bold">${kelas.title}</h4><p>${kelas.description}</p>`;
                                div.onclick = () => {
                                    document.getElementById('kelas_id_input').value = kelas.id;
                                    document.getElementById('kelas-form').submit();
                                };
                                container.appendChild(div);
                            });
                            document.getElementById('kelas-list').classList.remove('hidden');
                        });
                }
            });
        });
    </script>
@endsection
