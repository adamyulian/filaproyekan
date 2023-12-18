<?php

namespace App\Filament\Resources\CostComponentResource\Pages;

use App\Filament\Resources\CostComponentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCostComponent extends EditRecord
{
    protected static string $resource = CostComponentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
