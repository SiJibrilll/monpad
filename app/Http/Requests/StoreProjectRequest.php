<?php

namespace App\Http\Requests;

use App\Rules\isAsisten;
use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_projek' => 'required|string',
            'deskripsi' => 'required|string',
            'semester' => 'required|integer',
            'tahun_ajaran' => 'required|integer',
            'asisten_id' => ['required', 'integer', 'exists:users,id', new isAsisten]
            // 'user_id' => 'required|integer|exists:users,id' // keep this if user can make projects for other users
        ];
    }
}
