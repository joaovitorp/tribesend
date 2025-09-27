<?php

namespace App\Services\SubscriberGroup;

use App\Models\SubscriberGroup;
use Illuminate\Support\Facades\DB;

class UpdateSubscriberGroupService
{
    /**
     * Execute the subscriber group update process.
     */
    public function execute(SubscriberGroup $subscriberGroup, array $data): SubscriberGroup
    {
        return DB::transaction(function () use ($subscriberGroup, $data) {
            $subscriberGroup->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'is_active' => $data['is_active'] ?? $subscriberGroup->is_active,
            ]);

            return $subscriberGroup->fresh();
        });
    }
}
