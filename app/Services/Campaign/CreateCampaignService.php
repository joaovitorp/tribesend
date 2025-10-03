<?php

namespace App\Services\Campaign;

use App\Models\Campaign;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateCampaignService
{
    public function execute(array $data): Campaign
    {
        return DB::transaction(function () use ($data) {
            $campaignData = [
                'team_id' => auth()->user()->current_team_id,
                'name' => $data['name'],
                'subject' => $data['subject'],
                'body_html' => $data['body_html'],
                'body_text' => $data['body_text'] ?? null,
                'status' => $data['status'] ?? 'draft',
                'scheduled_at' => $data['scheduled_at'] ?? null,
            ];

            $campaign = Campaign::create($campaignData);

            if (isset($data['included_segments']) && is_array($data['included_segments'])) {
                $this->attachSegments($campaign, $data['included_segments'], 'include');
            }

            if (isset($data['excluded_segments']) && is_array($data['excluded_segments'])) {
                $this->attachSegments($campaign, $data['excluded_segments'], 'exclude');
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
