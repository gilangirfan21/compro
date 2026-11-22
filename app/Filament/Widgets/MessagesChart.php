<?php

namespace App\Filament\Widgets;

use App\Models\Message;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class MessagesChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Pesan Masuk per Bulan';

    protected ?string $description = '12 bulan terakhir';

    protected ?string $pollingInterval = null;

    protected ?string $maxHeight = '260px';

    public static function canView(): bool
    {
        return auth()->user()?->can('viewAny', Message::class) ?? false;
    }

    protected function getData(): array
    {
        $months = collect(range(11, 0))->map(fn (int $monthsAgo): Carbon => now()->startOfMonth()->subMonths($monthsAgo));

        // Grouped in PHP so the query works the same on MySQL and SQLite.
        $counts = Message::query()
            ->where('created_at', '>=', $months->first())
            ->pluck('created_at')
            ->countBy(fn ($date): string => $date->format('Y-m'));

        return [
            'datasets' => [
                [
                    'label' => 'Pesan',
                    'data' => $months->map(fn (Carbon $month): int => $counts->get($month->format('Y-m'), 0))->all(),
                    'fill' => true,
                ],
            ],
            'labels' => $months->map(fn (Carbon $month): string => $month->locale('id')->translatedFormat('M Y'))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => ['legend' => ['display' => false]],
            'scales' => ['y' => ['beginAtZero' => true, 'ticks' => ['precision' => 0]]],
        ];
    }
}
