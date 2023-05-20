<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'nick' => ['required', 'string', 'max:30', 'unique:' . User::class],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:' . User::class],
            'password' => ['required', 'confirmed', 'min:6'],
            'city' => ['max:30'],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Не указан email',
            'password.required' => 'Не указан пароль',
            'password.confirm' => 'Указанные пароли не совпадают',
            'email.email' => 'Некорректный email',
            'nick.required' => 'Не указан Ник/имя',
            'nick.max' => 'Максимальный размер поля Ник/Имя - 30 символов',
            'city.max' => 'Максимальный размер поля Город - 30 символов',
            'nick.unique' => 'Такое имя уже занято',
            'email.unique' => 'Такой email уже есть в системе',
        ];
    }
}
