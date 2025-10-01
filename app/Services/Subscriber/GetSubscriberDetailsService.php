<?php

namespace App\Services\Subscriber;

use App\Models\Subscriber;

class GetSubscriberDetailsService
{
    /**
     * Execute the subscriber details retrieval process.
     */
    public function execute(Subscriber $subscriber): Subscriber
    {
        $subscriber->load(['subscriberGroups', 'campaignSends']);

        // Add computed data
        $subscriber->total_campaigns_sent = $subscriber->campaignSends()->count();
        $subscriber->last_campaign_sent = $subscriber->campaignSends()
            ->latest()
            ->first()?->created_at?->diffForHumans();

        return $subscriber;
    }
}



