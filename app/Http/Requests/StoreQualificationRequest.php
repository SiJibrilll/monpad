<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQualificationRequest extends FormRequest
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
            'notes' => ['required', 'string'],
            'grades' => ['required', 'array'],
            'grades.*.personal_grade_type_id' => ['required', 'integer', 'exists:personal_grade_types,id'],
            'grades.*.grade' => ['required', 'integer', 'min:0', 'max:100']
        ];
    }
}
