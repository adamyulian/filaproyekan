<?php

namespace App\Filament\Resources\CostComponentResource\Pages;

use App\Filament\Resources\CostComponentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCostComponent extends CreateRecord
{
    protected static string $resource = CostComponentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
