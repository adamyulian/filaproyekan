<?php

namespace App\Filament\Resources\DetailTaskResource\Pages;

use App\Filament\Resources\DetailTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDetailTasks extends ListRecords
{
    protected static string $resource = DetailTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
