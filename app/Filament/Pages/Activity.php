<?php

namespace App\Filament\Pages;

use Dotswan\FilamentLaravelPulse\Widgets\PulseCache;
use Dotswan\FilamentLaravelPulse\Widgets\PulseExceptions;
use Dotswan\FilamentLaravelPulse\Widgets\PulseQueues;
use Dotswan\FilamentLaravelPulse\Widgets\PulseServers;
use Dotswan\FilamentLaravelPulse\Widgets\PulseSlowOutGoingRequests;
use Dotswan\FilamentLaravelPulse\Widgets\PulseSlowQueries;
use Dotswan\FilamentLaravelPulse\Widgets\PulseSlowRequests;
use Dotswan\FilamentLaravelPulse\Widgets\PulseUsage;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Pages\Dashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Filament\Support\Enums\ActionSize;
use Illuminate\Contracts\Support\Htmlable;

class Activity extends Dashboard
{
    protected static ?string $navigationLabel = 'Activity';

    protected static ?string $navigationGroup = 'Analytics';

    protected static ?int $navigationSort = -3;

    protected static string $routePath = 'activity';

    public static ?string $title = 'Activity';

    use HasFiltersAction;

    public static function getNavigationIcon(): string|Htmlable|null
    {
        return null;
    }

    public function getColumns(): int|string|array
    {
        return 12;
    }

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('1h')
                    ->action(fn () => $this->redirect(route('filament.manager.pages.activity'))),
                Action::make('24h')
                    ->action(fn () => $this->redirect(route('filament.manager.pages.activity', ['period' => '24_hours']))),
                Action::make('7d')
                    ->action(fn () => $this->redirect(route('filament.manager.pages.activity', ['period' => '7_days']))),
            ])
                ->label(__('Filter'))
                ->icon('heroicon-m-funnel')
                ->size(ActionSize::Small)
                ->color('gray')
                ->button(),
        ];
    }

    public function getWidgets(): array
    {
        return [
            PulseServers::class,
            PulseCache::class,
            PulseExceptions::class,
            PulseUsage::class,
            PulseQueues::class,
            PulseSlowQueries::class,
            PulseSlowRequests::class,
            PulseSlowOutGoingRequests::class,
        ];
    }
}
