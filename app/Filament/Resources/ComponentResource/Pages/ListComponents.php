<?php

namespace App\Filament\Resources\ComponentResource\Pages;

use App\Filament\Resources\ComponentResource;
use App\Filament\Resources\ComponentResource\Widgets\ComponentChart;
use App\Imports\ImportComponents;
use App\Models\Component;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ListComponents extends ListRecords
{
    protected static string $resource = ComponentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ExcelImportAction::make()
                ->color("primary"),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        $user = auth()->user();

        if ($user && $user->id === 1) {
            // Admin gets all data
            return [
                ComponentChart::class
            ];
        } else {

            return [];
        }
    }
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Components'),
            'Bahan' => Tab::make('Materials')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('jenis', 'Bahan')),
            'Tenaga Kerja' => Tab::make('Workers')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('jenis', 'Tenaga Kerja')),
            'Peralatan' => Tab::make('Tools')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('jenis', 'Peralatan')),
        ];
    }
}
