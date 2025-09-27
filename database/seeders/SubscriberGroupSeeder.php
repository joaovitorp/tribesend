<?php

namespace Database\Seeders;

use App\Models\SubscriberGroup;
use App\Models\Team;
use Illuminate\Database\Seeder;

class SubscriberGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar todos os teams para criar grupos para cada um
        $teams = Team::all();

        foreach ($teams as $team) {
            // Criar grupos padrão para cada team
            SubscriberGroup::factory()->create([
                'team_id' => $team->id,
                'name' => 'Newsletter Geral',
                'description' => 'Grupo principal para newsletter geral',
                'is_active' => true,
            ]);

            SubscriberGroup::factory()->create([
                'team_id' => $team->id,
                'name' => 'Promoções',
                'description' => 'Grupo para campanhas promocionais',
                'is_active' => true,
            ]);

            SubscriberGroup::factory()->create([
                'team_id' => $team->id,
                'name' => 'Novidades',
                'description' => 'Grupo para novidades e atualizações',
                'is_active' => true,
            ]);

            // Criar alguns grupos adicionais aleatórios
            SubscriberGroup::factory()->count(2)->create([
                'team_id' => $team->id,
            ]);
        }
    }
}
