<?php

namespace App\Services\Campaign;

use App\Models\Campaign;
use Illuminate\Pagination\LengthAwarePaginator;

class GetAllCampaignsService
{
    public function execute(array $filters = []): LengthAwarePaginator
    {
        $query = Campaign::query()
            ->where('team_id', auth()->user()->current_team_id)
            ->with(['includedSegments', 'excludedSegments']);

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($filters['per_page'] ?? 15);
    }
}
