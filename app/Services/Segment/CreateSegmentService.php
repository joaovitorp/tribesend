<?php

namespace App\Services\Segment;

use App\Models\Segment;
use App\Models\Team;
use Illuminate\Support\Facades\DB;

class CreateSegmentService
{
    /**
     * Execute the segment creation process.
     */
    public function execute(array $data, Team $team): Segment
    {
        return DB::transaction(function () use ($data, $team) {
            $segment = Segment::create([
                'team_id' => $team->id,
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'is_active' => $data['is_active'] ?? true,
            ]);

            return $segment->fresh();
        });
    }
}
