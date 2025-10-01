<?php

namespace App\Services\Subscriber;

use App\Models\Subscriber;
use App\Models\Team;
use Illuminate\Pagination\LengthAwarePaginator;

class GetAllSubscribersService
{
    /**
     * Execute the subscribers listing process.
     */
    public function execute(Team $team, array $filters = []): LengthAwarePaginator
    {
        $query = Subscriber::query()
            ->where('team_id', $team->id)
            ->with(['subscriberGroups']);

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['subscriber_group'])) {
            $query->whereHas('subscriberGroups', function ($q) use ($filters) {
                $q->where('subscriber_groups.id', $filters['subscriber_group']);
            });
        }

        return $query->latest()->paginate($filters['per_page'] ?? 15);
    }
}



