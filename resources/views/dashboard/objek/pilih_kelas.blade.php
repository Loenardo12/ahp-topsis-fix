@extends('dashboard.layouts.app')

@section('container')
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex flex-row items-center justify-between p-6 pb-0 mb-4 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <h6>Pilih Kelas Siswa</h6>
                </div>
                <div id='recipients' class="p-8 mt-6 lg:mt-0 rounded shadow bg-white">
                    <form action="{{ route('objek.ambil.siswa') }}" method="post">
                        @csrf
                        <div class="form-control w-full max-w-xs mb-4">
                            <label class="label">
                                <span class="label-text">Pilih Tingkat Kelas</span>
                            </label>
                            <select name="kelas_tingkat" id="kelas-tingkat-select" class="select select-bordered w-full max-w-xs text-dark" required>
                                <option value="" disabled selected>Pilih tingkat...</option>
                                <option value="10">Kelas 10</option>
                                <option value="11">Kelas 11</option>
                                <option value="12">Kelas 12</option>
                            </select>
                        </div>

                        <!-- Dropdown Kelas 10 (default hidden) -->
                        <div id="kelas-10-section" class="kelas-section form-control w-full max-w-xs mb-4" style="display: none;">
                            <label class="label">
                                <span class="label-text">Pilih Kelas 10</span>
                            </label>
                            <select name="kelas_id" id="kelas-10-select" class="select select-bordered w-full max-w-xs text-dark">
                                <option value="" disabled selected>Pilih kelas...</option>
                                @foreach ($kelas10 as $kelas)
                                    <option value="{{ $kelas->id }}">{{ $kelas->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Dropdown Kelas 11 (default hidden) -->
                        <div id="kelas-11-section" class="kelas-section form-control w-full max-w-xs mb-4" style="display: none;">
                            <label class="label">
                                <span class="label-text">Pilih Kelas 11</span>
                            </label>
                            <select name="kelas_id" id="kelas-11-select" class="select select-bordered w-full max-w-xs text-dark">
                                <option value="" disabled selected>Pilih kelas...</option>
                                @foreach ($kelas11 as $kelas)
                                    <option value="{{ $kelas->id }}">{{ $kelas->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Dropdown Kelas 12 (default hidden) -->
                        <div id="kelas-12-section" class="kelas-section form-control w-full max-w-xs mb-4" style="display: none;">
                            <label class="label">
                                <span class="label-text">Pilih Kelas 12</span>
                            </label>
                            <select name="kelas_id" id="kelas-12-select" class="select select-bordered w-full max-w-xs text-dark">
                                <option value="" disabled selected>Pilih kelas...</option>
                                @foreach ($kelas12 as $kelas)
                                    <option value="{{ $kelas->id }}">{{ $kelas->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="modal-action">
                            <button type="submit" class="btn btn-success" id="ambil-siswa-btn" disabled>Proses</button>
                            <a href="{{ route('objek.index') }}" class="btn">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const kelasTingkatSelect = document.getElementById('kelas-tingkat-select');
            const kelasSections = document.querySelectorAll('.kelas-section');
            const submitButton = document.getElementById('ambil-siswa-btn');

            kelasTingkatSelect.addEventListener('change', function () {
                const selectedTingkat = this.value;

                // Sembunyikan semua section kelas
                kelasSections.forEach(section => {
                    section.style.display = 'none';
                    // Reset select dan disable button
                    const select = section.querySelector('select[name="kelas_id"]');
                    select.selectedIndex = 0;
                });

                // Tampilkan section kelas yang sesuai
                if (selectedTingkat) {
                    const targetSection = document.getElementById(`kelas-${selectedTingkat}-section`);
                    if (targetSection) {
                        targetSection.style.display = 'block';
                        // Enable button jika kelas dipilih
                        const targetSelect = targetSection.querySelector('select[name="kelas_id"]');
                        targetSelect.disabled = false;
                    }
                } else {
                    submitButton.disabled = true;
                }
            });

            // Event listener untuk setiap select kelas
            kelasSections.forEach(section => {
                const select = section.querySelector('select[name="kelas_id"]');
                select.addEventListener('change', function () {
                    // Enable/disable tombol submit berdasarkan apakah kelas dipilih
                    submitButton.disabled = !this.value;
                });
            });
        });
    </script>
@endsection
