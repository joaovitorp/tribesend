<?php

namespace App\Services\Segment;

use App\Models\Segment;

class DeleteSegmentService
{
    /**
     * Execute the segment deletion process.
     */
    public function execute(Segment $segment): bool
    {
        // Verificar se o segmento tem campanhas ativas
        if ($segment->campaigns()->exists()) {
            throw new \Exception('Não é possível excluir segmento que possui campanhas associadas');
        }

        // Verificar se o segmento tem assinantes
        if ($segment->subscribers()->exists()) {
            throw new \Exception('Não é possível excluir segmento que possui assinantes');
        }

        return $segment->delete();
    }
}
