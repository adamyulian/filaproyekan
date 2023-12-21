<?php

namespace App\Filament\Resources\DetailSubTaskResource\Pages;

use App\Filament\Resources\DetailSubTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDetailSubTask extends EditRecord
{
    protected static string $resource = DetailSubTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
