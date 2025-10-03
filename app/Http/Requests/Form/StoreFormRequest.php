<?php

namespace App\Http\Requests\Form;

use Illuminate\Foundation\Http\FormRequest;

class StoreFormRequest extends FormRequest
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
            'segments' => ['required', 'array', 'min:1'],
            'segments.*' => ['required', 'string', 'exists:segments,id'],
            'fields' => ['required', 'array', 'min:1'],
            'fields.*.name' => ['required', 'string', 'max:255'],
            'fields.*.type' => ['required', 'string', 'in:text,email,phone,textarea,select,checkbox,radio'],
            'fields.*.label' => ['required', 'string', 'max:255'],
            'fields.*.required' => ['boolean'],
            'fields.*.options' => ['array'],
            'fields.*.options.*' => ['string', 'max:255'],
            'referral' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'expires_at' => ['nullable', 'date', 'after:now'],
        ];
    }

    /**
     * Get custom error messages for validator.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome do formulário é obrigatório.',
            'name.max' => 'O nome do formulário não pode ter mais de 255 caracteres.',
            'segments.required' => 'Pelo menos um segmento deve ser selecionado.',
            'segments.min' => 'Pelo menos um segmento deve ser selecionado.',
            'segments.*.exists' => 'Um dos segmentos selecionados não existe.',
            'fields.required' => 'Pelo menos um campo deve ser adicionado ao formulário.',
            'fields.min' => 'Pelo menos um campo deve ser adicionado ao formulário.',
            'fields.*.name.required' => 'O nome do campo é obrigatório.',
            'fields.*.type.required' => 'O tipo do campo é obrigatório.',
            'fields.*.type.in' => 'Tipo de campo inválido.',
            'fields.*.label.required' => 'O rótulo do campo é obrigatório.',
            'expires_at.after' => 'A data de expiração deve ser no futuro.',
        ];
    }
}
