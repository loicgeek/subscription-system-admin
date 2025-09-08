<?php

namespace App\Filament\Widgets;

use App\Models\Link;
use App\Models\Project;
use App\Models\User;
use App\Models\Fingerprint;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('All registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
            
            Stat::make('Active Projects', Project::where('active', true)->count())
                ->description('Currently active projects')
                ->descriptionIcon('heroicon-m-folder')
                ->color('info'),
            
            Stat::make('Total Links', Link::count())
                ->description('All created links')
                ->descriptionIcon('heroicon-m-link')
                ->color('warning'),
            
            Stat::make('Total Clicks', Link::sum('clicked_count'))
                ->description('Total link clicks')
                ->descriptionIcon('heroicon-m-cursor-arrow-rays')
                ->color('primary'),
        ];
    }
}