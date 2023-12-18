<?php

namespace App\Filament\Resources\DetailCostSubTaskResource\Pages;

use App\Filament\Resources\DetailCostSubTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDetailCostSubTask extends EditRecord
{
    protected static string $resource = DetailCostSubTaskResource::class;

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
