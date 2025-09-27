<?php

namespace App\Services\Form;

use App\Models\Form;

class GetFormDetailsService
{
    /**
     * Execute the form details retrieval process.
     */
    public function execute(Form $form): Form
    {
        $form->load(['team']);

        // Adicionar dados computados
        $form->is_expired = $form->isExpired();
        $form->is_valid = $form->isValid();
        $form->public_url = route('public.forms.show', $form->hash);

        return $form;
    }
}
