<?php

use App\Models\Waitlist;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the landing page', function () {
    $response = $this->get(route('home'));

    $response->assertSuccessful();
});

it('stores a waitlist subscription with valid email', function () {
    $response = $this->post(route('waitlist.subscribe'), [
        'email' => 'test@example.com',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Obrigado! Você entrou na lista de espera para o TribeSend.');

    $this->assertDatabaseHas('waitlists', [
        'email' => 'test@example.com',
    ]);
});

it('stores a waitlist subscription with name and email', function () {
    $response = $this->post(route('waitlist.subscribe'), [
        'email' => 'john@example.com',
        'name' => 'John Doe',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('waitlists', [
        'email' => 'john@example.com',
        'name' => 'John Doe',
    ]);
});

it('stores ip address and user agent when subscribing', function () {
    $response = $this->post(route('waitlist.subscribe'), [
        'email' => 'tracked@example.com',
    ]);

    $waitlist = Waitlist::where('email', 'tracked@example.com')->first();

    expect($waitlist)->not->toBeNull();
    expect($waitlist->ip_address)->not->toBeNull();
    expect($waitlist->user_agent)->not->toBeNull();
});

it('requires email to subscribe to waitlist', function () {
    $response = $this->post(route('waitlist.subscribe'), []);

    $response->assertSessionHasErrors(['email']);
});

it('requires valid email format', function () {
    $response = $this->post(route('waitlist.subscribe'), [
        'email' => 'invalid-email',
    ]);

    $response->assertSessionHasErrors(['email']);
});

it('prevents duplicate email subscriptions', function () {
    Waitlist::create([
        'email' => 'duplicate@example.com',
    ]);

    $response = $this->post(route('waitlist.subscribe'), [
        'email' => 'duplicate@example.com',
    ]);

    $response->assertSessionHasErrors(['email']);
});

it('allows different emails to subscribe', function () {
    $this->post(route('waitlist.subscribe'), [
        'email' => 'first@example.com',
    ]);

    $response = $this->post(route('waitlist.subscribe'), [
        'email' => 'second@example.com',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseCount('waitlists', 2);
});
