<?php

use App\Models\SubscriberGroup;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->team = Team::factory()->create(['owner_id' => $this->user->id]);
});

test('user can view subscriber groups index', function () {
    $this->actingAs($this->user);

    $response = $this->get(route('subscriber-groups.index'));

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page->component('SubscriberGroups/Index'));
});

test('user can create a subscriber group', function () {
    $this->actingAs($this->user);

    $subscriberGroupData = [
        'name' => 'Newsletter Test',
        'description' => 'Grupo de teste para newsletter',
        'is_active' => true,
    ];

    $response = $this->post(route('subscriber-groups.store'), $subscriberGroupData);

    $response->assertRedirect(route('subscriber-groups.index'));
    $response->assertSessionHas('success', 'Grupo de assinantes criado com sucesso');

    $this->assertDatabaseHas('subscriber_groups', [
        'team_id' => $this->team->id,
        'name' => 'Newsletter Test',
        'description' => 'Grupo de teste para newsletter',
        'is_active' => true,
    ]);
});

test('user can view subscriber group create form', function () {
    $this->actingAs($this->user);

    $response = $this->get(route('subscriber-groups.create'));

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page->component('SubscriberGroups/Create'));
});

test('user can view a subscriber group', function () {
    $this->actingAs($this->user);

    $subscriberGroup = SubscriberGroup::factory()->create([
        'team_id' => $this->team->id,
    ]);

    $response = $this->get(route('subscriber-groups.show', $subscriberGroup));

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page->component('SubscriberGroups/Show'));
});

test('user can edit a subscriber group', function () {
    $this->actingAs($this->user);

    $subscriberGroup = SubscriberGroup::factory()->create([
        'team_id' => $this->team->id,
    ]);

    $response = $this->get(route('subscriber-groups.edit', $subscriberGroup));

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page->component('SubscriberGroups/Edit'));
});

test('user can update a subscriber group', function () {
    $this->actingAs($this->user);

    $subscriberGroup = SubscriberGroup::factory()->create([
        'team_id' => $this->team->id,
        'name' => 'Original Name',
    ]);

    $updateData = [
        'name' => 'Updated Name',
        'description' => 'Updated description',
        'is_active' => false,
    ];

    $response = $this->put(route('subscriber-groups.update', $subscriberGroup), $updateData);

    $response->assertRedirect(route('subscriber-groups.index'));
    $response->assertSessionHas('success', 'Grupo de assinantes atualizado com sucesso');

    $this->assertDatabaseHas('subscriber_groups', [
        'id' => $subscriberGroup->id,
        'name' => 'Updated Name',
        'description' => 'Updated description',
        'is_active' => false,
    ]);
});

test('user can delete a subscriber group', function () {
    $this->actingAs($this->user);

    $subscriberGroup = SubscriberGroup::factory()->create([
        'team_id' => $this->team->id,
    ]);

    $response = $this->delete(route('subscriber-groups.destroy', $subscriberGroup));

    $response->assertRedirect(route('subscriber-groups.index'));
    $response->assertSessionHas('success', 'Grupo de assinantes excluído com sucesso');

    $this->assertDatabaseMissing('subscriber_groups', [
        'id' => $subscriberGroup->id,
    ]);
});

test('user cannot delete subscriber group with campaigns', function () {
    $this->actingAs($this->user);

    $subscriberGroup = SubscriberGroup::factory()->create([
        'team_id' => $this->team->id,
    ]);

    // Simular que o grupo tem campanhas (mock do relacionamento)
    $subscriberGroup->campaigns()->create([
        'name' => 'Test Campaign',
        'subject' => 'Test Subject',
        'body_html' => '<p>Test content</p>',
        'body_text' => 'Test content',
        'status' => 'draft',
        'team_id' => $this->team->id,
    ]);

    $response = $this->delete(route('subscriber-groups.destroy', $subscriberGroup));

    $response->assertRedirect();
    $response->assertSessionHas('error', 'Não é possível excluir grupo que possui campanhas associadas');

    $this->assertDatabaseHas('subscriber_groups', [
        'id' => $subscriberGroup->id,
    ]);
});

test('validation fails for required fields', function () {
    $this->actingAs($this->user);

    $response = $this->post(route('subscriber-groups.store'), []);

    $response->assertSessionHasErrors(['name']);
});

test('validation fails for name too long', function () {
    $this->actingAs($this->user);

    $response = $this->post(route('subscriber-groups.store'), [
        'name' => str_repeat('a', 256), // Mais de 255 caracteres
    ]);

    $response->assertSessionHasErrors(['name']);
});

test('validation fails for description too long', function () {
    $this->actingAs($this->user);

    $response = $this->post(route('subscriber-groups.store'), [
        'name' => 'Valid Name',
        'description' => str_repeat('a', 1001), // Mais de 1000 caracteres
    ]);

    $response->assertSessionHasErrors(['description']);
});

test('user can only see their team subscriber groups', function () {
    $this->actingAs($this->user);

    // Criar grupo do próprio team
    $ownGroup = SubscriberGroup::factory()->create([
        'team_id' => $this->team->id,
        'name' => 'Own Group',
    ]);

    // Criar grupo de outro team
    $otherTeam = Team::factory()->create();
    $otherGroup = SubscriberGroup::factory()->create([
        'team_id' => $otherTeam->id,
        'name' => 'Other Group',
    ]);

    $response = $this->get(route('subscriber-groups.index'));

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page->has('subscriberGroups.data', 1)
        ->where('subscriberGroups.data.0.name', 'Own Group')
    );
});

test('user cannot access other team subscriber groups', function () {
    $otherTeam = Team::factory()->create();
    $otherGroup = SubscriberGroup::factory()->create([
        'team_id' => $otherTeam->id,
    ]);

    $this->actingAs($this->user);

    $response = $this->get(route('subscriber-groups.show', $otherGroup));

    $response->assertForbidden();
});
