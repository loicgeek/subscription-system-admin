<?php

namespace App\Filament\Widgets;

use App\Models\Link;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class LinkClicksChart extends ChartWidget
{
    protected static ?string $heading = 'Link Clicks Over Time';

    protected function getData(): array
    {
        $data = Trend::model(Link::class)
            ->between(
                start: now()->subDays(30),
                end: now(),
            )
            ->perDay()
            ->sum('clicked_count');

        return [
            'datasets' => [
                [
                    'label' => 'Total Clicks',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => '#3b82f6',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
