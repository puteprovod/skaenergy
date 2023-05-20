<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CodeVerificationRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|integer',
            'email' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'Не указан код',
            'email.required' => 'Не указан email пользователя',
            'code.integer' => 'Код должен быть числовым',
        ];
    }
}
