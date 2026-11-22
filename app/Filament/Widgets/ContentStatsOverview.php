<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Messages\MessageResource;
use App\Filament\Resources\Portfolios\PortfolioResource;
use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\Services\ServiceResource;
use App\Models\Message;
use App\Models\Portfolio;
use App\Models\Post;
use App\Models\Service;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContentStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $pollingInterval = null;

    public static function canView(): bool
    {
        return collect([Message::class, Post::class, Portfolio::class, Service::class])
            ->contains(fn (string $model): bool => auth()->user()?->can('viewAny', $model) ?? false);
    }

    /**
     * @return array<Stat>
     */
    protected function getStats(): array
    {
        $user = auth()->user();
        $stats = [];

        if ($user->can('viewAny', Message::class)) {
            $unread = Message::query()->unread()->count();

            $stats[] = Stat::make('Pesan belum dibaca', $unread)
                ->description(Message::query()->where('created_at', '>=', now()->subDays(7))->count().' pesan dalam 7 hari terakhir')
                ->descriptionIcon(Heroicon::OutlinedInbox)
                ->chart($this->messagesPerDay(7))
                ->color($unread > 0 ? 'warning' : 'success')
                ->url(MessageResource::getUrl('index', ['filters' => ['is_read' => ['value' => '0']]]));
        }

        if ($user->can('viewAny', Post::class)) {
            $stats[] = Stat::make('Berita terbit', Post::query()->published()->count())
                ->description(Post::query()->count().' total berita')
                ->descriptionIcon(Heroicon::OutlinedNewspaper)
                ->url(PostResource::getUrl('index'));
        }

        if ($user->can('viewAny', Portfolio::class)) {
            $stats[] = Stat::make('Portofolio aktif', Portfolio::query()->active()->count())
                ->description(Portfolio::query()->count().' total portofolio')
                ->descriptionIcon(Heroicon::OutlinedBriefcase)
                ->url(PortfolioResource::getUrl('index'));
        }

        if ($user->can('viewAny', Service::class)) {
            $stats[] = Stat::make('Layanan aktif', Service::query()->active()->count())
                ->description(Service::query()->count().' total layanan')
                ->descriptionIcon(Heroicon::OutlinedSquares2x2)
                ->url(ServiceResource::getUrl('index'));
        }

        return $stats;
    }

    /**
     * Message counts for each of the last $days days, oldest first.
     *
     * @return array<int, int>
     */
    protected function messagesPerDay(int $days): array
    {
        $counts = Message::query()
            ->where('created_at', '>=', now()->subDays($days - 1)->startOfDay())
            ->pluck('created_at')
            ->countBy(fn ($date): string => $date->toDateString());

        return collect(range($days - 1, 0))
            ->map(fn (int $daysAgo): int => $counts->get(now()->subDays($daysAgo)->toDateString(), 0))
            ->all();
    }
}
