<?php

namespace App\Services\Campaign;

use App\Models\Campaign;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateCampaignService
{
    public function execute(Campaign $campaign, array $data): Campaign
    {
        return DB::transaction(function () use ($campaign, $data) {
            $campaignData = [];

            if (isset($data['name'])) {
                $campaignData['name'] = $data['name'];
            }

            if (isset($data['subject'])) {
                $campaignData['subject'] = $data['subject'];
            }

            if (isset($data['body_html'])) {
                $campaignData['body_html'] = $data['body_html'];
            }

            if (isset($data['body_text'])) {
                $campaignData['body_text'] = $data['body_text'];
            }

            if (isset($data['status'])) {
                $campaignData['status'] = $data['status'];
            }

            if (isset($data['scheduled_at'])) {
                $campaignData['scheduled_at'] = $data['scheduled_at'];
            }

            if (! empty($campaignData)) {
                $campaign->update($campaignData);
            }

            if (isset($data['included_segments'])) {
                $campaign->segments()->wherePivot('type', 'include')->detach();

                if (is_array($data['included_segments']) && ! empty($data['included_segments'])) {
                    $this->attachSegments($campaign, $data['included_segments'], 'include');
                }
            }

            if (isset($data['excluded_segments'])) {
                $campaign->segments()->wherePivot('type', 'exclude')->detach();

                if (is_array($data['excluded_segments']) && ! empty($data['excluded_segments'])) {
                    $this->attachSegments($campaign, $data['excluded_segments'], 'exclude');
                }
            }

            return $campaign->fresh(['includedSegments', 'excludedSegments']);
        });
    }

    private function attachSegments(Campaign $campaign, array $segmentIds, string $type): void
    {
        $pivotData = [];
        foreach ($segmentIds as $segmentId) {
            $pivotData[$segmentId] = [
                'id' => Str::uuid()->toString(),
                'type' => $type,
            ];
        }

        $campaign->segments()->attach($pivotData);
    }
}
