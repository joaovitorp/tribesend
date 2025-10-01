<?php

namespace App\Services\Subscriber;

use App\Models\Subscriber;

class DeleteSubscriberService
{
    /**
     * Execute the subscriber deletion process.
     */
    public function execute(Subscriber $subscriber): bool
    {
        // Detach from all subscriber groups
        $subscriber->subscriberGroups()->detach();

        return $subscriber->delete();
    }
}



