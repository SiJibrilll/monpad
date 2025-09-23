<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMahasiswaRequest extends FormRequest
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
        $user = User::with('mahasiswa_data')->findOrFail($this->route('mahasiswa'));

        return [
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',

            'nim' => 'sometimes|string|unique:mahasiswa_data,nim,' . optional($user->mahasiswa_data)->id,
            'angkatan' => 'sometimes|integer',
            'prodi' => 'sometimes|string',
            'jabatan' => 'sometimes|in:PM,UI/UX,BE,FE',
        ];
    }
}
