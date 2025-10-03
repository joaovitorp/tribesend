<?php

namespace App\Services\Campaign;

use App\Models\Campaign;
use Illuminate\Support\Facades\Log;

class SendCampaignService
{
    public function execute(Campaign $campaign): bool
    {
        if (! $campaign->isDraft() && ! $campaign->isScheduled()) {
            throw new \Exception('Apenas campanhas em rascunho ou agendadas podem ser enviadas.');
        }

        if ($campaign->includedSegments->isEmpty()) {
            throw new \Exception('A campanha precisa ter pelo menos um segmento incluído.');
        }

        $campaign->update([
            'status' => 'sending',
        ]);

        Log::info('Campaign queued for sending', ['campaign_id' => $campaign->id]);

        return true;
    }
}
