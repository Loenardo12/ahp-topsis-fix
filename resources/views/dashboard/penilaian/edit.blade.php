@extends('dashboard.layouts.app')

@section('container')
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="card shadow-lg p-8 bg-white rounded-2xl" id="edit_form">
                <form id="edit-form" action="{{ route('penilaian.perbarui', $data->alternatif_id) }}" method="post" enctype="multipart/form-data">
                    <h3 class="font-bold text-lg">Ubah {{ $judul }}:
                        <span class="text-greenPrimary" id="title_form">{{ $data->alternatif->objek->nama }}</span>
                    </h3>
                    @csrf
                    <input type="text" name="alternatif_id" value="{{ $data->alternatif_id }}" hidden />

                    @php
                        // Ambil semua kriteria
                        $kriteria = \App\Models\Kriteria::all();
                        // Kelompokkan data penilaian berdasarkan kriteria_id untuk memudahkan pencarian
                        $nilaiPerKriteria = $data2->pluck('nilai_asli', 'kriteria_id')->toArray();
                    @endphp

                    @foreach ($kriteria as $item)
                        <div class="form-control w-full max-w-xs">
                            <label class="label">
                                <span class="label-text">Kriteria: <span class="text-greenPrimary">{{ $item->nama }}</span></span>
                            </label>
                            <div class="flex items-center gap-2"> {{-- Wrapper untuk input dan label kategori --}}
                                <input
                                    type="number"
                                    name="nilai_asli[{{ $item->id }}]"
                                    value="{{ old('nilai_asli.'.$item->id, $nilaiPerKriteria[$item->id] ?? '') }}" 
                                    min="0"
                                    max="100"
                                    class="input input-bordered text-dark w-full max-w-xs"
                                    required
                                />
                                <span class="label-text-alt text-gray-500"> {{-- Elemen untuk menampilkan kategori --}}
                                    <span id="kategori_{{ $item->id }}">-</span> {{-- Gunakan ID unik --}}
                                </span>
                            </div>
                            <label class="label">
                                @error('nilai_asli.'.$item->id)
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                @enderror
                            </label>
                        </div>
                    @endforeach

                    <div class="modal-action">
                        <!-- Tombol Perbarui (Submit Form ke route yang sama) -->
                        <button type="submit" name="action" value="update" class="btn btn-success">Perbarui</button>

                        <!-- Tombol Previous -->
                        @if($prevId)
                            <button type="submit" name="action" value="previous" formaction="{{ route('penilaian.ubah', $prevId) }}" class="btn btn-secondary">Previous</button>
                        @else
                            <button type="button" class="btn btn-secondary" disabled>Previous</button>
                        @endif

                        <!-- Tombol Next -->
                        @if($nextId)
                            <button type="submit" name="action" value="next" formaction="{{ route('penilaian.ubah', $nextId) }}" class="btn btn-primary">Next</button>
                        @else
                            <button type="button" class="btn btn-primary" disabled>Next</button>
                        @endif

                        <!-- Tombol Back -->
                        <a href="{{ route('penilaian') }}" class="btn">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        // Tambahkan skrip untuk menampilkan kategori
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil data sub-kriteria dari PHP ke JavaScript
            const subKriteriaGrouped = @json($subKriteriaGrouped); // Konversi koleksi Laravel ke JSON

            // Ambil semua input nilai_asli
            const nilaiInputs = document.querySelectorAll('input[name^="nilai_asli["]');

            nilaiInputs.forEach(input => {
                // Tambahkan event listener untuk setiap input
                input.addEventListener('input', function() { // Gunakan 'input' untuk deteksi real-time
                    const nameAttr = this.getAttribute('name');
                    const kriteriaId = nameAttr.match(/\[(\d+)\]/)[1]; // Ekstrak ID kriteria dari name="nilai_asli[123]"
                    const nilaiAsli = parseInt(this.value); // Ambil nilai input

                    let kategoriNama = '-'; // Default jika tidak ditemukan

                    // Cari rentang yang cocok di data sub_kriteria untuk kriteria_id ini
                    if (subKriteriaGrouped[kriteriaId]) {
                        const subKriterias = subKriteriaGrouped[kriteriaId];
                        for (let i = 0; i < subKriterias.length; i++) {
                            const sub = subKriterias[i];
                            if (nilaiAsli >= sub.nilai_min && nilaiAsli <= sub.nilai_max) {
                                kategoriNama = sub.nama; // Ambil nama sub-kriteria yang cocok
                                break; // Hentikan loop karena sudah ditemukan rentang yang cocok (karena diurutkan descending)
                            }
                        }
                    }

                    // Update span yang menampilkan kategori
                    const kategoriSpan = document.getElementById(`kategori_${kriteriaId}`);
                    if (kategoriSpan) {
                        kategoriSpan.textContent = kategoriNama;
                    }
                });

                // Panggil event listener sekali untuk menampilkan kategori awal jika nilai sudah ada
                input.dispatchEvent(new Event('input'));
            });
        });
    </script>
@endsection
