<?php

namespace App\Filament\Resources\DetailSubTaskResource\Pages;

use App\Filament\Resources\DetailSubTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDetailSubTasks extends ListRecords
{
    protected static string $resource = DetailSubTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
