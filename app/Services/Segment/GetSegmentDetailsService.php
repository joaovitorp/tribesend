<?php

namespace App\Services\Segment;

use App\Models\Segment;

class GetSegmentDetailsService
{
    /**
     * Execute the segment details retrieval process.
     */
    public function execute(Segment $segment): Segment
    {
        $segment->load(['team', 'subscribers', 'campaigns']);

        // Adicionar dados computados
        $segment->total_subscribers = $segment->subscribers()->count();
        $segment->total_campaigns = $segment->campaigns()->count();

        return $segment;
    }
}
