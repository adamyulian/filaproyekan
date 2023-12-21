<?php

namespace App\Filament\Resources\SubTaskResource\Pages;

use App\Filament\Resources\SubTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSubTask extends CreateRecord
{
    protected static string $resource = SubTaskResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
