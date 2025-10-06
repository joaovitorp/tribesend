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

    $response->assertRedirect(route('waitlist.success'));
    $response->assertSessionHas('email', 'test@example.com');

    $this->assertDatabaseHas('waitlists', [
        'email' => 'test@example.com',
    ]);
});

it('stores a waitlist subscription with name and email', function () {
    $response = $this->post(route('waitlist.subscribe'), [
        'email' => 'john@example.com',
        'name' => 'John Doe',
    ]);

    $response->assertRedirect(route('waitlist.success'));
    $response->assertSessionHas('email', 'john@example.com');

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

    $response->assertRedirect(route('waitlist.success'));
    $response->assertSessionHas('email', 'second@example.com');

    $this->assertDatabaseCount('waitlists', 2);
});

it('renders the success page with email', function () {
    $response = $this->withSession(['email' => 'test@example.com'])
        ->get(route('waitlist.success'));

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('Landing/Success')
        ->has('email')
        ->where('email', 'test@example.com')
    );
});

it('redirects to home if accessing success page without email', function () {
    $response = $this->get(route('waitlist.success'));

    $response->assertRedirect(route('home'));
});

it('redirects to success page after successful subscription', function () {
    $response = $this->post(route('waitlist.subscribe'), [
        'email' => 'success@example.com',
    ]);

    $response->assertRedirect(route('waitlist.success'));

    $followResponse = $this->followRedirects($response);
    $followResponse->assertSuccessful();
});
