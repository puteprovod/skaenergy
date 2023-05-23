<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'nick' => ['string', 'required', 'max:30', Rule::unique(User::class)->ignore($this->user()->id)],
            'city' => ['nullable','string', 'max:30'],
            'country' => ['nullable','string', 'max:30'],
            'favourite_clubs' => ['nullable','string', 'max:80'],
            'avatar' => ['nullable','image','file' => 'max:300', Rule::dimensions()->maxWidth(800)->maxHeight(800)],
        ];
    }
    public function messages()
    {
        return [
            'nick.required' => 'Не указан Ник/Имя',
            'nick.max' => 'Максимальный размер поля Ник/Имя - 30 символов',
            'city.max' => 'Максимальный размер поля Город - 30 символов',
            'country.max' => 'Максимальный размер поля Страна - 30 символов',
            'favourite_clubs.max' => 'Максимальный размер поля Любимые клубы - 80 символов',
            'avatar.image' => 'Такой тип изображения не поддерживается',
            'avatar.max.file' => 'Превышен максимальный размер изображения',
            'avatar.dimensions' => 'Допустимый размер изображения 800х800пикс',
            'nick.unique' => 'Такое имя уже занято',
        ];
    }
}
