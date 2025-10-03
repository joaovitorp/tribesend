<?php

namespace App\Services\Form;

use App\Models\Form;
use App\Models\Segment;
use App\Models\Subscriber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProcessFormSubscriptionService
{
    /**
     * Execute the form subscription process.
     */
    public function execute(Form $form, array $data): Subscriber
    {
        // Verificar se o formulário é válido
        if (! $form->isValid()) {
            throw ValidationException::withMessages([
                'form' => ['Este formulário não está mais disponível.'],
            ]);
        }

        // Verificar se o email já está inscrito em algum dos segmentos
        $existingSubscriber = Subscriber::where('team_id', $form->team_id)
            ->where('email', $data['email'])
            ->whereHas('segments', function ($query) use ($form) {
                $query->whereIn('segments.id', $form->segments);
            })
            ->first();

        if ($existingSubscriber) {
            throw ValidationException::withMessages([
                'email' => ['Este email já está inscrito nesta lista.'],
            ]);
        }

        return DB::transaction(function () use ($form, $data) {
            // Criar ou encontrar o subscriber
            $subscriber = Subscriber::firstOrCreate(
                [
                    'team_id' => $form->team_id,
                    'email' => $data['email'],
                ],
                [
                    'first_name' => $data['first_name'] ?? '',
                    'last_name' => $data['last_name'] ?? '',
                    'status' => 'active',
                    'subscribed_at' => now(),
                    'metadata' => $this->buildMetadata($form, $data),
                ]
            );

            // Adicionar aos segmentos do formulário
            $segments = Segment::whereIn('id', $form->segments)->get();

            foreach ($segments as $segment) {
                $subscriber->segments()->syncWithoutDetaching([
                    $segment->id => ['id' => Str::uuid()],
                ]);
            }

            return $subscriber->fresh();
        });
    }

    /**
     * Build metadata from form submission.
     */
    private function buildMetadata(Form $form, array $data): array
    {
        $metadata = [
            'form_id' => $form->id,
            'form_name' => $form->name,
            'referral' => $form->referral,
            'submitted_at' => now()->toISOString(),
        ];

        // Adicionar campos customizados
        foreach ($form->fields as $field) {
            if (isset($data[$field['name']])) {
                $metadata['custom_fields'][$field['name']] = $data[$field['name']];
            }
        }

        return $metadata;
    }
}
