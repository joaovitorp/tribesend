<?php

namespace App\Services\Subscriber;

use App\Models\Subscriber;
use Illuminate\Support\Facades\DB;

class UpdateSubscriberService
{
    /**
     * Execute the subscriber update process.
     */
    public function execute(Subscriber $subscriber, array $data): Subscriber
    {
        return DB::transaction(function () use ($subscriber, $data) {
            $updateData = [
                'email' => $data['email'],
                'first_name' => $data['first_name'] ?? null,
                'last_name' => $data['last_name'] ?? null,
                'status' => $data['status'],
                'metadata' => $data['metadata'] ?? [],
            ];

            // Handle status changes
            if ($data['status'] === 'unsubscribed' && $subscriber->status !== 'unsubscribed') {
                $updateData['unsubscribed_at'] = now();
            } elseif ($data['status'] === 'active' && $subscriber->status === 'unsubscribed') {
                $updateData['unsubscribed_at'] = null;
                $updateData['subscribed_at'] = now();
            }

            $subscriber->update($updateData);

            // Sync subscriber groups if provided
            if (isset($data['subscriber_groups']) && is_array($data['subscriber_groups'])) {
                $subscriber->subscriberGroups()->sync($data['subscriber_groups']);
            }

            return $subscriber->fresh(['subscriberGroups']);
        });
    }
}



