<?php

namespace App\Services\Form;

use App\Models\Form;
use App\Models\Team;
use Illuminate\Pagination\LengthAwarePaginator;

class GetAllFormsService
{
    /**
     * Execute the forms listing process.
     */
    public function execute(Team $team, array $filters = []): LengthAwarePaginator
    {
        $query = Form::query()
            ->where('team_id', $team->id)
            ->with(['team']);

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('referral', 'like', "%{$search}%");
            });
        }

        if (isset($filters['status'])) {
            if ($filters['status'] === 'active') {
                $query->active();
            } elseif ($filters['status'] === 'inactive') {
                $query->where('is_active', false);
            } elseif ($filters['status'] === 'expired') {
                $query->where('expires_at', '<', now());
            }
        }

        $paginator = $query->latest()->paginate($filters['per_page'] ?? 15);

        // Carregar detalhes dos segmentos para cada formulário
        $paginator->getCollection()->transform(function (Form $form) {
            if (is_array($form->segments) && count($form->segments) > 0) {
                $form->segment_details = \App\Models\Segment::whereIn('id', $form->segments)
                    ->get(['id', 'name', 'description']);
            }

            return $form;
        });

        return $paginator;
    }
}
