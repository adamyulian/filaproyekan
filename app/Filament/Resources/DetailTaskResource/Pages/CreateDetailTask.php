<?php

namespace App\Filament\Resources\DetailTaskResource\Pages;

use App\Filament\Resources\DetailTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDetailTask extends CreateRecord
{
    protected static string $resource = DetailTaskResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
