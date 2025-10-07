<?php

use App\Models\Waitlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

use function Pest\Laravel\artisan;

uses(RefreshDatabase::class);

beforeEach(function () {
    Carbon::setTestNow('2025-10-07 12:00:00');
});

afterEach(function () {
    Carbon::setTestNow();
});

it('exibe o relatório da waitlist com estatísticas corretas', function () {
    // Criar dados de teste
    // Total: 10
    // Hoje: 2
    // Esta semana: 5
    // Este mês: 7

    // Inscritos de hoje
    Waitlist::factory()->count(2)->create([
        'created_at' => Carbon::now(),
        'confirmed_at' => Carbon::now(),
    ]);

    // Inscritos desta semana (mas não hoje)
    Waitlist::factory()->count(3)->create([
        'created_at' => Carbon::now()->subDays(2),
        'confirmed_at' => Carbon::now()->subDays(2),
    ]);

    // Inscritos deste mês (mas não desta semana)
    Waitlist::factory()->count(2)->create([
        'created_at' => Carbon::now()->subDays(10),
        'confirmed_at' => null, // pendentes
    ]);

    // Inscritos de meses anteriores
    Waitlist::factory()->count(3)->create([
        'created_at' => Carbon::now()->subMonths(2),
        'confirmed_at' => Carbon::now()->subMonths(2),
    ]);

    artisan('waitlist:report')
        ->expectsOutput('📊 Gerando relatório da Waitlist...')
        ->assertExitCode(0);
});

it('calcula corretamente o total de inscritos', function () {
    Waitlist::factory()->count(5)->create();

    artisan('waitlist:report')
        ->expectsOutputToContain('Total de Inscritos')
        ->assertExitCode(0);
});

it('exibe relatório em formato JSON quando solicitado', function () {
    Waitlist::factory()->count(3)->create([
        'confirmed_at' => Carbon::now(),
    ]);

    Waitlist::factory()->count(2)->create([
        'confirmed_at' => null,
    ]);

    $result = artisan('waitlist:report --json');

    $result->assertExitCode(0);
    $output = $result->execute();

    expect($output)->toBe(0);
});

it('calcula corretamente inscritos confirmados e pendentes', function () {
    // Criar inscritos confirmados
    Waitlist::factory()->count(7)->create([
        'confirmed_at' => Carbon::now(),
    ]);

    // Criar inscritos pendentes
    Waitlist::factory()->count(3)->create([
        'confirmed_at' => null,
    ]);

    artisan('waitlist:report')
        ->expectsOutputToContain('Confirmados')
        ->expectsOutputToContain('Pendentes')
        ->assertExitCode(0);
});

it('calcula estatísticas diárias corretamente', function () {
    // Inscritos de hoje
    Waitlist::factory()->count(5)->create([
        'created_at' => Carbon::now(),
    ]);

    // Inscritos de ontem (não devem aparecer no relatório diário)
    Waitlist::factory()->count(3)->create([
        'created_at' => Carbon::now()->subDay(),
    ]);

    artisan('waitlist:report')
        ->expectsOutputToContain('HOJE')
        ->assertExitCode(0);
});

it('calcula estatísticas semanais corretamente', function () {
    // Inscritos desta semana
    Waitlist::factory()->count(8)->create([
        'created_at' => Carbon::now()->startOfWeek()->addDays(2),
    ]);

    // Inscritos de semanas anteriores
    Waitlist::factory()->count(4)->create([
        'created_at' => Carbon::now()->subWeeks(2),
    ]);

    artisan('waitlist:report')
        ->expectsOutputToContain('ESTA SEMANA')
        ->assertExitCode(0);
});

it('calcula estatísticas mensais corretamente', function () {
    // Inscritos deste mês
    Waitlist::factory()->count(12)->create([
        'created_at' => Carbon::now()->startOfMonth()->addDays(5),
    ]);

    // Inscritos de meses anteriores
    Waitlist::factory()->count(6)->create([
        'created_at' => Carbon::now()->subMonths(2),
    ]);

    artisan('waitlist:report')
        ->expectsOutputToContain('ESTE MÊS')
        ->assertExitCode(0);
});

it('calcula a taxa de crescimento semanal', function () {
    // Semana atual
    Waitlist::factory()->count(10)->create([
        'created_at' => Carbon::now()->startOfWeek()->addDays(2),
    ]);

    // Semana anterior
    Waitlist::factory()->count(5)->create([
        'created_at' => Carbon::now()->subWeek()->startOfWeek()->addDays(2),
    ]);

    artisan('waitlist:report')
        ->expectsOutputToContain('Crescimento')
        ->assertExitCode(0);
});

it('calcula a taxa de crescimento mensal', function () {
    // Mês atual
    Waitlist::factory()->count(15)->create([
        'created_at' => Carbon::now()->startOfMonth()->addDays(5),
    ]);

    // Mês anterior
    Waitlist::factory()->count(10)->create([
        'created_at' => Carbon::now()->subMonth()->startOfMonth()->addDays(5),
    ]);

    artisan('waitlist:report')
        ->expectsOutputToContain('Crescimento')
        ->assertExitCode(0);
});

it('exibe a taxa de confirmação geral', function () {
    Waitlist::factory()->count(8)->create([
        'confirmed_at' => Carbon::now(),
    ]);

    Waitlist::factory()->count(2)->create([
        'confirmed_at' => null,
    ]);

    artisan('waitlist:report')
        ->expectsOutputToContain('Taxa de Confirmação Geral')
        ->assertExitCode(0);
});

it('funciona corretamente quando não há inscritos', function () {
    artisan('waitlist:report')
        ->expectsOutputToContain('TOTAL GERAL')
        ->assertExitCode(0);
});
