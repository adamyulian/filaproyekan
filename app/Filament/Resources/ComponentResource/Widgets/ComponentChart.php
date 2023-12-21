<?php

namespace App\Filament\Resources\ComponentResource\Widgets;

use App\Models\Component;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class ComponentChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected static string $color = 'success';

    protected int | string | array $columnSpan = 'full';

    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        $data = Trend::model(Component::class)
            ->between(
                start: now()->startOfMonth(),
                end: now(),
            )
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Components',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
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
