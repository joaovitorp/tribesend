<?php

namespace App\Http\Requests\Subscriber;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubscriberRequest extends FormRequest
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
            'email' => ['required', 'email', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:active,inactive,unsubscribed'],
            'segments' => ['nullable', 'array'],
            'segments.*' => ['string', 'exists:segments,id'],
            'metadata' => ['nullable', 'array'],
        ];
    }

    /**
     * Get custom error messages for validator.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ter um formato válido.',
            'email.max' => 'O email não pode ter mais de 255 caracteres.',
            'first_name.max' => 'O primeiro nome não pode ter mais de 255 caracteres.',
            'last_name.max' => 'O sobrenome não pode ter mais de 255 caracteres.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'Status inválido. Deve ser: ativo, inativo ou cancelado.',
            'segments.*.exists' => 'Um dos segmentos selecionados não existe.',
        ];
    }
}
