<?php

namespace App\Http\Requests\Campaign;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCampaignRequest extends FormRequest
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
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'subject' => ['sometimes', 'required', 'string', 'max:255'],
            'body_html' => ['sometimes', 'required', 'string'],
            'body_text' => ['nullable', 'string'],
            'status' => ['sometimes', 'required', 'string', 'in:draft,scheduled,sending,sent,canceled'],
            'scheduled_at' => ['nullable', 'date', 'after:now'],
            'included_segments' => ['nullable', 'array'],
            'included_segments.*' => ['exists:segments,id'],
            'excluded_segments' => ['nullable', 'array'],
            'excluded_segments.*' => ['exists:segments,id'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome da campanha é obrigatório.',
            'name.max' => 'O nome da campanha não pode ter mais de 255 caracteres.',
            'subject.required' => 'O assunto da campanha é obrigatório.',
            'subject.max' => 'O assunto da campanha não pode ter mais de 255 caracteres.',
            'body_html.required' => 'O conteúdo da campanha é obrigatório.',
            'status.required' => 'O status da campanha é obrigatório.',
            'status.in' => 'O status deve ser: draft, scheduled, sending, sent ou canceled.',
            'scheduled_at.date' => 'A data de agendamento deve ser uma data válida.',
            'scheduled_at.after' => 'A data de agendamento deve ser posterior à data atual.',
            'included_segments.array' => 'Os segmentos incluídos devem ser um array.',
            'included_segments.*.exists' => 'Um ou mais segmentos incluídos não existem.',
            'excluded_segments.array' => 'Os segmentos excluídos devem ser um array.',
            'excluded_segments.*.exists' => 'Um ou mais segmentos excluídos não existem.',
        ];
    }
}
