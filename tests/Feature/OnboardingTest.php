<?php

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('shows the onboarding team creation page', function () {
    $response = $this->actingAs($this->user)->get('/onboarding/team');

    $response->assertSuccessful()
        ->assertInertia(fn ($page) => $page
            ->component('Onboarding/CreateTeam')
            ->has('categories')
        );
});

it('creates a team successfully', function () {
    $teamData = [
        'name' => 'My Awesome Team',
        'description' => 'A great team for testing',
        'category' => 'Developer',
    ];

    $response = $this->actingAs($this->user)
        ->post('/onboarding/team', $teamData);

    $response->assertRedirect('/dashboard')
        ->assertSessionHas('success', 'Time criado com sucesso! Bem-vindo ao TribeSend.');

    $this->assertDatabaseHas('teams', [
        'name' => 'My Awesome Team',
        'description' => 'A great team for testing',
        'category' => 'Developer',
        'owner_id' => $this->user->id,
    ]);

    // Verify user is attached to team with owner role
    $team = Team::where('name', 'My Awesome Team')->first();
    $this->assertTrue($team->users->contains($this->user));
    $this->assertEquals('owner', $team->users->where('id', $this->user->id)->first()->pivot->role);

    // Verify the team was set as the user's current team
    $this->user->refresh();
    expect($this->user->current_team_id)->toBe($team->id);
});

it('validates required fields', function () {
    $response = $this->actingAs($this->user)
        ->post('/onboarding/team', []);

    $response->assertSessionHasErrors(['name', 'category']);
});

it('validates category is in allowed list', function () {
    $response = $this->actingAs($this->user)
        ->post('/onboarding/team', [
            'name' => 'Test Team',
            'category' => 'InvalidCategory',
        ]);

    $response->assertSessionHasErrors(['category']);
});

it('allows optional description', function () {
    $teamData = [
        'name' => 'Team Without Description',
        'category' => 'Marketing',
    ];

    $response = $this->actingAs($this->user)
        ->post('/onboarding/team', $teamData);

    $response->assertRedirect('/dashboard');

    $this->assertDatabaseHas('teams', [
        'name' => 'Team Without Description',
        'category' => 'Marketing',
        'description' => null,
        'owner_id' => $this->user->id,
    ]);
});
