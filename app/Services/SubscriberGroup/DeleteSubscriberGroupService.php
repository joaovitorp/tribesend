<?php

namespace App\Services\SubscriberGroup;

use App\Models\SubscriberGroup;

class DeleteSubscriberGroupService
{
    /**
     * Execute the subscriber group deletion process.
     */
    public function execute(SubscriberGroup $subscriberGroup): bool
    {
        // Verificar se o grupo tem campanhas ativas
        if ($subscriberGroup->campaigns()->exists()) {
            throw new \Exception('Não é possível excluir grupo que possui campanhas associadas');
        }

        // Verificar se o grupo tem assinantes
        if ($subscriberGroup->subscribers()->exists()) {
            throw new \Exception('Não é possível excluir grupo que possui assinantes');
        }

        return $subscriberGroup->delete();
    }
}
