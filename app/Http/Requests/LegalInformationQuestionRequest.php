<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LegalInformationQuestionRequest extends FormRequest
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
            'content_question' => 'required|string|max:255',
            'is_sub_question' => 'required|bool',
            'legal_information_question_id' => 'required|integer',
        ];
    }
}
