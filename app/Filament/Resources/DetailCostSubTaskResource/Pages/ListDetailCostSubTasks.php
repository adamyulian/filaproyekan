<?php

namespace App\Filament\Resources\DetailCostSubTaskResource\Pages;

use App\Filament\Resources\DetailCostSubTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDetailCostSubTasks extends ListRecords
{
    protected static string $resource = DetailCostSubTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
