<?php

namespace App\Services\Segment;

use App\Models\Segment;
use Illuminate\Support\Facades\DB;

class UpdateSegmentService
{
    /**
     * Execute the segment update process.
     */
    public function execute(Segment $segment, array $data): Segment
    {
        return DB::transaction(function () use ($segment, $data) {
            $segment->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'is_active' => $data['is_active'] ?? $segment->is_active,
            ]);

            return $segment->fresh();
        });
    }
}
