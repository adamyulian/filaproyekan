<?php

namespace App\Filament\Resources\DetailSubTaskResource\Pages;

use App\Filament\Resources\DetailSubTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDetailSubTask extends CreateRecord
{
    protected static string $resource = DetailSubTaskResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
