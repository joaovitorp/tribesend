<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirects to onboarding after successful registration', function () {
    $userData = [
        'name' => 'João Silva',
        'email' => 'joao@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $response = $this->post('/register', $userData);

    $response->assertRedirect('/onboarding/team');

    // Verify user was created
    expect(User::where('email', 'joao@example.com')->exists())->toBeTrue();

    // Verify user is authenticated
    $this->assertAuthenticated();
});

it('completes full registration to dashboard flow', function () {
    // Step 1: Register
    $userData = [
        'name' => 'Maria Santos',
        'email' => 'maria@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $response = $this->post('/register', $userData);
    $response->assertRedirect('/onboarding/team');

    // Step 2: Create team in onboarding
    $user = User::where('email', 'maria@example.com')->first();
    $this->assertNotNull($user);

    $teamData = [
        'name' => 'Minha Empresa',
        'description' => 'Uma empresa incrível',
        'category' => 'Marketing',
    ];

    $response = $this->actingAs($user)
        ->post('/onboarding/team', $teamData);

    $response->assertRedirect('/dashboard')
        ->assertSessionHas('success', 'Time criado com sucesso! Bem-vindo ao TribeSend.');

    // Verify team was created
    expect($user->fresh()->teams()->where('name', 'Minha Empresa')->exists())->toBeTrue();

    // Verify user is owner of the team
    $team = $user->teams()->where('name', 'Minha Empresa')->first();
    expect($team->users()->where('user_id', $user->id)->first()->pivot->role)->toBe('owner');
});
