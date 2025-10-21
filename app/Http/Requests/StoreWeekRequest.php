<?php

namespace App\Http\Requests;

use App\Models\WeekType;
use App\Rules\isAsisten;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreWeekRequest extends FormRequest
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
            'week_type_id' => ['required', 'exists:week_types,id'],
            'grades' => ['required', 'array', 'min:1'],
            'notes' => ['sometimes', 'nullable', 'string'],
            'grader_id' => ['required', 'integer', 'exists:users,id', new isAsisten],
            'date' => ['required', 'date'],
            'project_id' => ['required', 'integer', 'exists:projects,id'],

            // validation within the grade array : 
            'grades.*.grade_type_id' => ['required', 'exists:grade_types,id'],
            'grades.*.grade' => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }

    public function withValidator(Validator $validator): void
    {

        $validator->after(function (Validator $validator) {
            $weekTypeId = $this->input('week_type_id');
            $grades = $this->input('grades', []);
            // Skip check if no week_type_id provided
            if (! $weekTypeId || empty($grades)) {
                return;
            }

            $weekType = WeekType::with('gradeType')->find($weekTypeId);
            if (! $weekType) {
                return;
            }

            $allowedIds = $weekType->gradeType->pluck('id')->toArray();
            $providedIds = collect($grades)->pluck('grade_type_id')->toArray();

            foreach ($grades as $index => $gradeData) {
                if (!in_array($gradeData['grade_type_id'], $allowedIds)) {
                    $validator->errors()->add(
                        "grades.$index.grade_type_id",
                        'This grade type is not valid for the selected week type.'
                    );
                }
            }

            
            $missing = array_diff($allowedIds, $providedIds);

            if (!empty($missing)) {
                $missingNames = $weekType->gradeType
                    ->whereIn('id', $missing)
                    ->pluck('name')
                    ->implode(', ');

                $validator->errors()->add(
                    'grades',
                    "Missing required grades: {$missingNames}."
                );
            }
        });
    }
}
