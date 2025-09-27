<?php

use App\Models\Form;
use App\Models\SubscriberGroup;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->team = Team::factory()->create(['owner_id' => $this->user->id]);

    $this->subscriberGroup = SubscriberGroup::factory()->create([
        'team_id' => $this->team->id,
    ]);
});

test('user can view forms index', function () {
    $this->actingAs($this->user);

    $response = $this->get(route('forms.index'));

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page->component('Forms/Index'));
});

test('user can create a form', function () {
    $this->actingAs($this->user);

    $formData = [
        'name' => 'Newsletter Test',
        'subscriber_groups' => [$this->subscriberGroup->id],
        'fields' => [
            [
                'name' => 'email',
                'type' => 'email',
                'label' => 'Email',
                'required' => true,
            ],
            [
                'name' => 'first_name',
                'type' => 'text',
                'label' => 'Nome',
                'required' => true,
            ],
        ],
        'content' => 'Inscreva-se na nossa newsletter',
        'is_active' => true,
    ];

    $response = $this->post(route('forms.store'), $formData);

    $response->assertRedirect(route('forms.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('forms', [
        'name' => 'Newsletter Test',
        'team_id' => $this->team->id,
        'is_active' => true,
    ]);
});

test('public form can be viewed by hash', function () {
    $form = Form::factory()->create([
        'team_id' => $this->team->id,
        'is_active' => true,
        'expires_at' => null,
    ]);

    $response = $this->get(route('public.forms.show', $form->hash));

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page->component('Public/Form'));
});

test('user can subscribe to a form', function () {
    $form = Form::factory()->create([
        'team_id' => $this->team->id,
        'subscriber_groups' => [$this->subscriberGroup->id],
        'fields' => [
            [
                'name' => 'email',
                'type' => 'email',
                'label' => 'Email',
                'required' => true,
            ],
            [
                'name' => 'first_name',
                'type' => 'text',
                'label' => 'Nome',
                'required' => true,
            ],
        ],
        'is_active' => true,
        'expires_at' => null,
    ]);

    $subscriptionData = [
        'email' => 'test@example.com',
        'first_name' => 'João',
    ];

    $response = $this->post(route('public.forms.subscribe', $form->hash), $subscriptionData);

    $response->assertRedirect(route('forms.success', $form->hash));

    $this->assertDatabaseHas('subscribers', [
        'email' => 'test@example.com',
        'first_name' => 'João',
        'team_id' => $this->team->id,
    ]);
});

test('expired form cannot be accessed', function () {
    $form = Form::factory()->create([
        'team_id' => $this->team->id,
        'is_active' => true,
        'expires_at' => now()->subDay(),
    ]);

    $response = $this->get(route('public.forms.show', $form->hash));

    $response->assertNotFound();
});

test('inactive form cannot be accessed', function () {
    $form = Form::factory()->create([
        'team_id' => $this->team->id,
        'is_active' => false,
    ]);

    $response = $this->get(route('public.forms.show', $form->hash));

    $response->assertNotFound();
});
