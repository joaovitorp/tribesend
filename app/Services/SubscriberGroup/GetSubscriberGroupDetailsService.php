<?php

namespace App\Services\SubscriberGroup;

use App\Models\SubscriberGroup;

class GetSubscriberGroupDetailsService
{
    /**
     * Execute the subscriber group details retrieval process.
     */
    public function execute(SubscriberGroup $subscriberGroup): SubscriberGroup
    {
        $subscriberGroup->load(['team', 'subscribers', 'campaigns']);

        // Adicionar dados computados
        $subscriberGroup->total_subscribers = $subscriberGroup->subscribers()->count();
        $subscriberGroup->total_campaigns = $subscriberGroup->campaigns()->count();
        $subscriberGroup->active_campaigns = $subscriberGroup->campaigns()->where('is_active', true)->count();

        return $subscriberGroup;
    }
}
