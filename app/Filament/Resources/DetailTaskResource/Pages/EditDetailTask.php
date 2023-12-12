<?php

namespace App\Filament\Resources\DetailTaskResource\Pages;

use App\Filament\Resources\DetailTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDetailTask extends EditRecord
{
    protected static string $resource = DetailTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
