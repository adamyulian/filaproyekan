<?php

namespace App\Filament\Resources\DetailCostTaskResource\Pages;

use App\Filament\Resources\DetailCostTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDetailCostTask extends EditRecord
{
    protected static string $resource = DetailCostTaskResource::class;

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
