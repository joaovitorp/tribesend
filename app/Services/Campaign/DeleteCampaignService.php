<?php

namespace App\Services\Campaign;

use App\Models\Campaign;

class DeleteCampaignService
{
    public function execute(Campaign $campaign): bool
    {
        if ($campaign->isSent()) {
            throw new \Exception('Não é possível excluir uma campanha que já foi enviada.');
        }

        if ($campaign->status === 'sending') {
            throw new \Exception('Não é possível excluir uma campanha que está sendo enviada.');
        }

        return $campaign->delete();
    }
}
