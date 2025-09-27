<?php

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirects user without team to onboarding when accessing dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertRedirect(route('onboarding.team.create'));
});

it('allows user with team to access dashboard', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['owner_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk();
});

it('allows user who is member of team to access dashboard', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $team = Team::factory()->create(['owner_id' => $owner->id]);

    $team->users()->attach($member->id, [
        'id' => \Illuminate\Support\Str::uuid(),
        'role' => 'member',
    ]);

    $this->actingAs($member)
        ->get(route('dashboard'))
        ->assertOk();
});

it('redirects user with team away from onboarding', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['owner_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('onboarding.team.create'))
        ->assertRedirect(route('dashboard'));
});

it('allows user without team to access onboarding', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('onboarding.team.create'))
        ->assertOk();
});

it('redirects user who is member of team away from onboarding', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $team = Team::factory()->create(['owner_id' => $owner->id]);

    $team->users()->attach($member->id, [
        'id' => \Illuminate\Support\Str::uuid(),
        'role' => 'member',
    ]);

    $this->actingAs($member)
        ->get(route('onboarding.team.create'))
        ->assertRedirect(route('dashboard'));
});

it('redirects unauthenticated user to login when accessing protected routes', function () {
    $this->get(route('dashboard'))
        ->assertRedirect(route('login'));

    $this->get(route('onboarding.team.create'))
        ->assertRedirect(route('login'));
});
