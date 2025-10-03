<?php

namespace App\Services\Form;

use App\Models\Form;
use Illuminate\Support\Facades\DB;

class UpdateFormService
{
    /**
     * Execute the form update process.
     */
    public function execute(Form $form, array $data): Form
    {
        return DB::transaction(function () use ($form, $data) {
            $form->update([
                'name' => $data['name'],
                'segments' => $data['segments'],
                'fields' => $data['fields'],
                'referral' => $data['referral'] ?? null,
                'content' => $data['content'] ?? null,
                'is_active' => $data['is_active'] ?? true,
                'expires_at' => $data['expires_at'] ?? null,
            ]);

            return $form->fresh();
        });
    }
}
