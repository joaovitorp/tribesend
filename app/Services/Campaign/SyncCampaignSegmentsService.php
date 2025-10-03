<?php

namespace App\Services\Campaign;

use App\Models\Campaign;
use Illuminate\Support\Str;

class SyncCampaignSegmentsService
{
    /**
     * Sync campaign segments with include/exclude types.
     *
     * @param  array  $data  ['included_segments' => [...], 'excluded_segments' => [...]]
     */
    public function execute(Campaign $campaign, array $data): Campaign
    {
        $syncData = [];

        // Process included segments
        if (isset($data['included_segments']) && is_array($data['included_segments'])) {
            foreach ($data['included_segments'] as $segmentId) {
                $syncData[$segmentId] = [
                    'id' => Str::uuid()->toString(),
                    'type' => 'include',
                ];
            }
        }

        // Process excluded segments
        if (isset($data['excluded_segments']) && is_array($data['excluded_segments'])) {
            foreach ($data['excluded_segments'] as $segmentId) {
                $syncData[$segmentId] = [
                    'id' => Str::uuid()->toString(),
                    'type' => 'exclude',
                ];
            }
        }

        // Sync all segments at once
        $campaign->segments()->sync($syncData);

        return $campaign->fresh(['includedSegments', 'excludedSegments']);
    }

    /**
     * Get all target subscribers for a campaign (included minus excluded).
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTargetSubscribers(Campaign $campaign)
    {
        // Get all subscribers from included segments
        $includedSubscribers = collect();
        foreach ($campaign->includedSegments as $segment) {
            $includedSubscribers = $includedSubscribers->merge(
                $segment->subscribers()->where('status', 'active')->get()
            );
        }

        // Get all subscribers from excluded segments
        $excludedSubscriberIds = collect();
        foreach ($campaign->excludedSegments as $segment) {
            $excludedSubscriberIds = $excludedSubscriberIds->merge(
                $segment->subscribers()->pluck('id')
            );
        }

        // Remove duplicates and exclude the excluded subscribers
        return $includedSubscribers
            ->unique('id')
            ->filter(fn ($subscriber) => ! $excludedSubscriberIds->contains($subscriber->id));
    }
}
