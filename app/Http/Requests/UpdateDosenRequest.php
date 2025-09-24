<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class UpdateDosenRequest extends FormRequest
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
        $user = User::with('dosen_data')->findOrFail($this->route('dosen'));

        return [
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',

            'nidn' => 'sometimes|string|unique:dosen_datas,nidn,' . optional($user->dosen_data)->id,
            'fakultas' => 'sometimes|string',
        ];
    }
}
