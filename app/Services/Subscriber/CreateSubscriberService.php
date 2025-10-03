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

            // Sync segments if provided
            if (isset($data['segments']) && is_array($data['segments'])) {
                $subscriber->segments()->sync($data['segments']);
            }

            return $subscriber->fresh(['segments']);
        });
    }
}
