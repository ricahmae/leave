<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkExperienceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'date_from' => 'required|date',
            'date_to' => 'required|date',
            'position_title' => "required|string|max:255",
            'appointment_status' => "required|string|max:255",
            'salary' => "required|double",
            'salary_grade_and_step' => "required|integer",
            'company' => "required|string|max:255",
            'government_office' => "required|string|max:255",
            'is_voluntary_work' => "required|bool",
            'personal_information_id' => "required|integer"
        ];
    }
}
