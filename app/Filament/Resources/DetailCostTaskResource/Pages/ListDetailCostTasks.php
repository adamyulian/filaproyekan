<?php

namespace App\Filament\Resources\DetailCostTaskResource\Pages;

use App\Filament\Resources\DetailCostTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDetailCostTasks extends ListRecords
{
    protected static string $resource = DetailCostTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
