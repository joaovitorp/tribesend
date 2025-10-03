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
        return $subscriber->delete();
    }
}
