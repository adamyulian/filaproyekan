<?php

namespace App\Filament\Widgets;

use App\Models\Component;
use App\Models\DetailSubTask;
use App\Models\SubTask;
use App\Models\Unit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Component', Component::count()),
            Stat::make('Total Subtask', SubTask::count()),
            Stat::make('Total Detail Subtask', DetailSubTask::count())
        ];
    }
}
