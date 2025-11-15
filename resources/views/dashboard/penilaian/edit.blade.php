@extends('dashboard.layouts.app')

@section('container')
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="card shadow-lg p-8 bg-white rounded-2xl" id="edit_form">

                <form action="{{ route('penilaian.perbarui', $data->alternatif_id) }}" method="post" enctype="multipart/form-data">
                    <h3 class="font-bold text-lg">Ubah {{ $judul }}:
                        <span class="text-greenPrimary" id="title_form">{{ $data->alternatif->objek->nama }}</span>
                    </h3>
                    @csrf
                    <input type="text" name="alternatif_id" value="{{ $data->alternatif_id }}" hidden />
                    @foreach ($subKriteria->unique('kriteria_id') as $item)
                        <div class="form-control w-full max-w-xs">
                            <label class="label">
                                <span class="label-text">Sub Kriteria: <span class="text-greenPrimary">{{ $item->kriteria->nama }}</span></span>
                            </label>
                            <select class="select select-bordered text-dark" name="kriteria_id[]" id="kriteria_id[]">
                                <option disabled selected>--Pilih--</option>
                                @foreach ($subKriteria->where('kriteria_id', $item->kriteria_id) as $value)
                                    @php
                                        // Ambil sub_kriteria_id dari array yang telah dibuat di controller
                                        $selectedSubKriteriaId = $nilai_per_kriteria[$item->kriteria_id] ?? null;
                                    @endphp
                                    <option value="{{ $value->id }}" {{ $value->id == $selectedSubKriteriaId ? 'selected' : '' }}>
                                        {{ $value->nama }}
                                    </option>
                                @endforeach
                            </select>
                            <label class="label">
                                @error('sub_kriteria_id')
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                @enderror
                            </label>
                        </div>
                    @endforeach
                    <div class="modal-action">
                        <button type="submit" class="btn btn-success">Perbarui</button>
                        <a href="{{ route('penilaian') }}" class="btn">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
