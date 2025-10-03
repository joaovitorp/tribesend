<?php

namespace App\Services\Form;

use App\Models\Form;
use App\Models\Team;
use Illuminate\Support\Facades\DB;

class CreateFormService
{
    /**
     * Execute the form creation process.
     */
    public function execute(array $data, Team $team): Form
    {
        return DB::transaction(function () use ($data, $team) {
            $form = Form::create([
                'team_id' => $team->id,
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
