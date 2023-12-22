<?php

namespace App\Filament\Resources\CostComponentResource\Pages;

use App\Filament\Resources\CostComponentResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListCostComponents extends ListRecords
{
    protected static string $resource = CostComponentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
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
            'Subkontraktor' => Tab::make('Subcontractors')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('jenis', 'Subkontraktor')),
        ];
    }
}
