<?php

namespace App\Console\Commands;

use App\Models\Waitlist;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class WaitlistReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'waitlist:report {--json : Output as JSON}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera relatório da waitlist com estatísticas de total, diário, semanal e mensal';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('📊 Gerando relatório da Waitlist...');
        $this->newLine();

        $stats = $this->generateStats();

        if ($this->option('json')) {
            $this->line(json_encode($stats, JSON_PRETTY_PRINT));

            return self::SUCCESS;
        }

        $this->displayReport($stats);

        return self::SUCCESS;
    }

    /**
     * Gera as estatísticas da waitlist.
     */
    private function generateStats(): array
    {
        $now = Carbon::now();

        return [
            'total' => [
                'count' => Waitlist::count(),
                'confirmed' => Waitlist::whereNotNull('confirmed_at')->count(),
                'pending' => Waitlist::whereNull('confirmed_at')->count(),
            ],
            'daily' => [
                'count' => Waitlist::whereDate('created_at', $now->toDateString())->count(),
                'confirmed' => Waitlist::whereDate('created_at', $now->toDateString())
                    ->whereNotNull('confirmed_at')
                    ->count(),
                'pending' => Waitlist::whereDate('created_at', $now->toDateString())
                    ->whereNull('confirmed_at')
                    ->count(),
            ],
            'weekly' => [
                'count' => Waitlist::whereBetween('created_at', [
                    $now->copy()->startOfWeek(),
                    $now->copy()->endOfWeek(),
                ])->count(),
                'confirmed' => Waitlist::whereBetween('created_at', [
                    $now->copy()->startOfWeek(),
                    $now->copy()->endOfWeek(),
                ])->whereNotNull('confirmed_at')->count(),
                'pending' => Waitlist::whereBetween('created_at', [
                    $now->copy()->startOfWeek(),
                    $now->copy()->endOfWeek(),
                ])->whereNull('confirmed_at')->count(),
            ],
            'monthly' => [
                'count' => Waitlist::whereMonth('created_at', $now->month)
                    ->whereYear('created_at', $now->year)
                    ->count(),
                'confirmed' => Waitlist::whereMonth('created_at', $now->month)
                    ->whereYear('created_at', $now->year)
                    ->whereNotNull('confirmed_at')
                    ->count(),
                'pending' => Waitlist::whereMonth('created_at', $now->month)
                    ->whereYear('created_at', $now->year)
                    ->whereNull('confirmed_at')
                    ->count(),
            ],
            'growth' => $this->calculateGrowthRate(),
            'generated_at' => $now->toIso8601String(),
        ];
    }

    /**
     * Calcula a taxa de crescimento comparando com períodos anteriores.
     */
    private function calculateGrowthRate(): array
    {
        $now = Carbon::now();

        $currentMonth = Waitlist::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        $lastMonth = Waitlist::whereMonth('created_at', $now->copy()->subMonth()->month)
            ->whereYear('created_at', $now->copy()->subMonth()->year)
            ->count();

        $monthlyGrowth = $lastMonth > 0
            ? round((($currentMonth - $lastMonth) / $lastMonth) * 100, 2)
            : 0;

        $currentWeek = Waitlist::whereBetween('created_at', [
            $now->copy()->startOfWeek(),
            $now->copy()->endOfWeek(),
        ])->count();

        $lastWeek = Waitlist::whereBetween('created_at', [
            $now->copy()->subWeek()->startOfWeek(),
            $now->copy()->subWeek()->endOfWeek(),
        ])->count();

        $weeklyGrowth = $lastWeek > 0
            ? round((($currentWeek - $lastWeek) / $lastWeek) * 100, 2)
            : 0;

        return [
            'monthly' => $monthlyGrowth,
            'weekly' => $weeklyGrowth,
        ];
    }

    /**
     * Exibe o relatório formatado no console.
     */
    private function displayReport(array $stats): void
    {
        // Total
        $this->info('═══════════════════════════════════════════════════');
        $this->info('                    TOTAL GERAL                    ');
        $this->info('═══════════════════════════════════════════════════');
        $this->table(
            ['Métrica', 'Quantidade'],
            [
                ['Total de Inscritos', $stats['total']['count']],
                ['Confirmados', $stats['total']['confirmed']],
                ['Pendentes', $stats['total']['pending']],
            ]
        );
        $this->newLine();

        // Diário
        $this->info('═══════════════════════════════════════════════════');
        $this->info('                 HOJE ('.Carbon::now()->format('d/m/Y').')                 ');
        $this->info('═══════════════════════════════════════════════════');
        $this->table(
            ['Métrica', 'Quantidade'],
            [
                ['Novos Inscritos', $stats['daily']['count']],
                ['Confirmados', $stats['daily']['confirmed']],
                ['Pendentes', $stats['daily']['pending']],
            ]
        );
        $this->newLine();

        // Semanal
        $this->info('═══════════════════════════════════════════════════');
        $this->info('              ESTA SEMANA (7 dias)                 ');
        $this->info('═══════════════════════════════════════════════════');
        $this->table(
            ['Métrica', 'Quantidade'],
            [
                ['Novos Inscritos', $stats['weekly']['count']],
                ['Confirmados', $stats['weekly']['confirmed']],
                ['Pendentes', $stats['weekly']['pending']],
            ]
        );
        $growthWeekly = $stats['growth']['weekly'];
        $growthWeeklyColor = $growthWeekly >= 0 ? 'green' : 'red';
        $this->components->info(
            "Crescimento: <fg={$growthWeeklyColor}>{$growthWeekly}%</> em relação à semana anterior"
        );
        $this->newLine();

        // Mensal
        $this->info('═══════════════════════════════════════════════════');
        $this->info('        ESTE MÊS ('.Carbon::now()->format('F/Y').')        ');
        $this->info('═══════════════════════════════════════════════════');
        $this->table(
            ['Métrica', 'Quantidade'],
            [
                ['Novos Inscritos', $stats['monthly']['count']],
                ['Confirmados', $stats['monthly']['confirmed']],
                ['Pendentes', $stats['monthly']['pending']],
            ]
        );
        $growthMonthly = $stats['growth']['monthly'];
        $growthMonthlyColor = $growthMonthly >= 0 ? 'green' : 'red';
        $this->components->info(
            "Crescimento: <fg={$growthMonthlyColor}>{$growthMonthly}%</> em relação ao mês anterior"
        );
        $this->newLine();

        // Taxa de conversão
        $conversionRate = $stats['total']['count'] > 0
            ? round(($stats['total']['confirmed'] / $stats['total']['count']) * 100, 2)
            : 0;

        $this->info('═══════════════════════════════════════════════════');
        $this->components->info("Taxa de Confirmação Geral: <fg=cyan>{$conversionRate}%</>");
        $this->info('═══════════════════════════════════════════════════');
        $this->newLine();

        $this->components->success('✓ Relatório gerado com sucesso!');
    }
}
