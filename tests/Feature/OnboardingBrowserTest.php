<?php

use App\Models\User;

it('can create a team through the onboarding interface', function () {
    $user = User::factory()->create();

    $page = $this->actingAs($user)->visit('/onboarding/team');

    $page->assertSee('Bem-vindo ao TribeSend!')
        ->assertSee('Crie seu Time')
        ->assertSee('Nome do Time')
        ->assertSee('Categoria')
        ->assertSee('Descrição (Opcional)')
        ->assertNoJavascriptErrors();

    // Fill out the form
    $page->fill('name', 'Minha Startup Incrível')
        ->click('[data-testid="category-trigger"]') // Assuming we add test ids
        ->click('text=Developer')
        ->fill('description', 'Uma startup focada em desenvolvimento de software')
        ->click('Criar Time e Continuar');

    // Should redirect to dashboard with success message
    $page->assertPath('/dashboard')
        ->assertSee('Time criado com sucesso! Bem-vindo ao TribeSend.');

    // Verify team was created in database
    expect($user->fresh()->teams()->where('name', 'Minha Startup Incrível')->exists())
        ->toBeTrue();
});
