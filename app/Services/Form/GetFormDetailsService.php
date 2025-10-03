<?php

namespace App\Services\Form;

use App\Models\Form;
use App\Models\Segment;

class GetFormDetailsService
{
    /**
     * Execute the form details retrieval process.
     */
    public function execute(Form $form): Form
    {
        $form->load(['team']);

        // Carregar detalhes dos segmentos
        if (is_array($form->segments) && count($form->segments) > 0) {
            $form->segment_details = Segment::whereIn('id', $form->segments)
                ->get(['id', 'name', 'description']);
        }

        // Adicionar dados computados
        $form->is_expired = $form->isExpired();
        $form->is_valid = $form->isValid();
        $form->public_url = route('public.forms.show', $form->hash);

        return $form;
    }
}
