<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LegalInformationRequest extends FormRequest
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
            'employee_id' => 'required|integer',
            'details' => 'required|text',
            'answer' => 'required|string|max:255',
            'legal_information_question_id' => 'required|integer',
        ];
    }
}
