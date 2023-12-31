<?php

namespace App\Filament\Resources\DetailCostTaskResource\Pages;

use App\Filament\Resources\DetailCostTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDetailCostTask extends CreateRecord
{
    protected static string $resource = DetailCostTaskResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
