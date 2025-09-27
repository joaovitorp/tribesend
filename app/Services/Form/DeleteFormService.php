<?php

namespace App\Services\Form;

use App\Models\Form;

class DeleteFormService
{
    /**
     * Execute the form deletion process.
     */
    public function execute(Form $form): bool
    {
        return $form->delete();
    }
}
