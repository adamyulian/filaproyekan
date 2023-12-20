<?php

namespace App\Filament\Resources\DetailCostTaskResource\Pages;

use App\Filament\Resources\DetailCostTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDetailCostTask extends ViewRecord
{
    protected static string $resource = DetailCostTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
