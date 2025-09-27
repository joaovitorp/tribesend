<?php

namespace App\Services\SubscriberGroup;

use App\Models\SubscriberGroup;
use App\Models\Team;
use Illuminate\Support\Facades\DB;

class CreateSubscriberGroupService
{
    /**
     * Execute the subscriber group creation process.
     */
    public function execute(array $data, Team $team): SubscriberGroup
    {
        return DB::transaction(function () use ($data, $team) {
            $subscriberGroup = SubscriberGroup::create([
                'team_id' => $team->id,
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'is_active' => $data['is_active'] ?? true,
            ]);

            return $subscriberGroup->fresh();
        });
    }
}
