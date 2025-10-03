<?php

namespace App\Services\Campaign;

use App\Models\Campaign;

class GetCampaignDetailsService
{
    public function execute(Campaign $campaign): Campaign
    {
        $campaign->load([
            'includedSegments',
            'excludedSegments',
            'team',
        ]);

        $campaign->total_recipients = $this->calculateTotalRecipients($campaign);
        $campaign->open_rate = $campaign->getOpenRateAttribute();
        $campaign->click_rate = $campaign->getClickRateAttribute();

        return $campaign;
    }

    private function calculateTotalRecipients(Campaign $campaign): int
    {
        $includedCount = 0;
        foreach ($campaign->includedSegments as $segment) {
            $includedCount += $segment->subscribers()->count();
        }

        $excludedCount = 0;
        foreach ($campaign->excludedSegments as $segment) {
            $excludedCount += $segment->subscribers()->count();
        }

        return max(0, $includedCount - $excludedCount);
    }
}
