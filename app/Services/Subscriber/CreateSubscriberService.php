<?php

namespace App\Services\Subscriber;

use App\Models\Subscriber;
use App\Models\Team;
use Illuminate\Support\Facades\DB;

class CreateSubscriberService
{
    /**
     * Execute the subscriber creation process.
     */
    public function execute(array $data, Team $team): Subscriber
    {
        return DB::transaction(function () use ($data, $team) {
            $subscriber = Subscriber::create([
                'team_id' => $team->id,
                'email' => $data['email'],
                'first_name' => $data['first_name'] ?? null,
                'last_name' => $data['last_name'] ?? null,
                'status' => $data['status'] ?? 'active',
                'subscribed_at' => now(),
                'metadata' => $data['metadata'] ?? [],
            ]);

            // Sync subscriber groups if provided
            if (isset($data['subscriber_groups']) && is_array($data['subscriber_groups'])) {
                $subscriber->subscriberGroups()->sync($data['subscriber_groups']);
            }

            return $subscriber->fresh(['subscriberGroups']);
        });
    }
}



