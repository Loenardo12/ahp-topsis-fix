<?php

namespace App\Http\Requests;

use App\Models\SubKriteria;
use Illuminate\Foundation\Http\FormRequest;

class SubKriteriaUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "id" => "required|exists:sub_kriteria,id", // Validasi ID jika perlu
            "nama" => "required|string|max:255",
            "nilai_min" => "required|integer|min:0|max:100", // Tambahkan ini
            "nilai_max" => "required|integer|min:0|max:100|gte:nilai_min", // Tambahkan ini, pastikan max >= min
            "bobot" => "required|integer|min:0|max:100", // Tambahkan ini
            "kriteria_id" => "required|exists:kriteria,id", // Tambahkan ini jika ingin divalidasi
        ];
    }
}
