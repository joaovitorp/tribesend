<?php

namespace App\Http\Requests\Team;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateTeamRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'category' => [
                'required',
                Rule::in([
                    'AI',
                    'Developer',
                    'Marketing',
                    'Game Development',
                    'Journalist',
                    'Writer',
                    'Travel',
                    'E-commerce',
                    'Finance',
                    'Healthcare',
                    'Education',
                    'Consulting',
                    'Design',
                    'Photography',
                    'Music',
                    'Sports',
                    'Food & Beverage',
                    'Real Estate',
                    'Legal',
                    'Non-profit',
                    'Other',
                ]),
            ],
        ];
    }

    /**
     * Get custom error messages for validator.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome do time é obrigatório.',
            'name.max' => 'O nome do time não pode ter mais de 255 caracteres.',
            'description.max' => 'A descrição não pode ter mais de 1000 caracteres.',
            'category.required' => 'A categoria é obrigatória.',
            'category.in' => 'A categoria selecionada é inválida.',
        ];
    }
}
