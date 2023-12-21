<?php

namespace App\Filament\Resources\DetailCostSubTaskResource\Pages;

use App\Filament\Resources\DetailCostSubTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDetailCostSubTask extends CreateRecord
{
    protected static string $resource = DetailCostSubTaskResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
