<?php

namespace App\Services\SubscriberGroup;

use App\Models\SubscriberGroup;
use App\Models\Team;
use Illuminate\Pagination\LengthAwarePaginator;

class GetAllSubscriberGroupsService
{
    /**
     * Execute the subscriber groups listing process.
     */
    public function execute(Team $team, array $filters = []): LengthAwarePaginator
    {
        $query = SubscriberGroup::query()
            ->where('team_id', $team->id)
            ->withCount(['subscribers', 'campaigns']);

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (isset($filters['status'])) {
            $query->where('is_active', $filters['status'] === 'active');
        }

        return $query->orderBy('created_at', 'desc')
            ->paginate($filters['per_page'] ?? 15);
    }
}
