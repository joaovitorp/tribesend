<?php

namespace App\Services\Team;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateTeamService
{
    /**
     * Execute the team creation process.
     */
    public function execute(array $data, User $owner): Team
    {
        return DB::transaction(function () use ($data, $owner) {
            // Create the team
            $team = Team::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'category' => $data['category'],
                'owner_id' => $owner->id,
            ]);

            // Add the owner as a team member with owner role
            $team->users()->attach($owner->id, [
                'id' => Str::uuid(),
                'role' => 'owner',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $team->fresh();
        });
    }
}
