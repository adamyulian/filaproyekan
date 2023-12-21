<?php

namespace App\Filament\Widgets;

use App\Models\Component;
use App\Models\DetailSubTask;
use App\Models\DetailTask;
use App\Models\SubTask;
use App\Models\Task;
use App\Models\Unit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = Auth::id();
        $user = auth()->user();

        if ($user && $user->id === 1) {
            // Admin gets all data
            return [
                Stat::make('Total Component', Component::count()),
                Stat::make('Total Subtask', SubTask::count()),
                Stat::make('Total Detail Subtask', DetailSubTask::count()),
                Stat::make('Total Task', Task::count()),
                Stat::make('Total Detail Task', DetailTask::count())
            ];
        } else {
            // Regular user gets data based on their ID
            $userId = $user->id;

            return [
                Stat::make('Total Component', Component::where('user_id', $userId)->count()),
                Stat::make('Total Subtask', SubTask::where('user_id', $userId)->count()),
                Stat::make('Total Detail Subtask', DetailSubTask::where('user_id', $userId)->count()),
                Stat::make('Total Task', Task::where('user_id', $userId)->count()),
                Stat::make('Total Detail Task', DetailTask::where('user_id', $userId)->count())
            ];
        }
    }
    // //Disabling StatOverview for another User except Admin
    // public static function canView(): bool
    // {
    //     $user = auth()->user();

    //     // Check if the user's ID is equal to 1
    //     return $user && $user->id === 1;
    // }
}
