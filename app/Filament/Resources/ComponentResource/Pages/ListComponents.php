<?php

namespace App\Filament\Resources\ComponentResource\Pages;

use App\Filament\Resources\ComponentResource;
use App\Imports\ImportComponents;
use App\Models\Component;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ListComponents extends ListRecords
{
    protected static string $resource = ComponentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ExcelImportAction::make()
                ->color("primary"),
            // Action::make('Upload')
            // ->label('Upload File Excel')
            // ->icon('heroicon-m-arrow-up-tray')
            // ->form([
            //     FileUpload::make('Components')
            //         ->label('Upload Components')
            //         ->acceptedFileTypes(['text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']),
            // ])
            // ->action(fn (Component $record): string => route('import'))
            // // ->action(function (array $data, Component $record): void {
            // //     $record->author()->associate($data['authorId']);
            // //     $record->save();
            // // })
            // // view ('filament.custom.upload-file', compact('data'))

        ];
    }

    // public function getHeader(): ?View
    // {
    //     $data =  Actions\CreateAction::make();
    //     return view ('filament.custom.upload-file', compact('data'));
    // }

    // public $file = '';

    // public function save(){
    //     if($this->file != ''){
    //         Excel::import(new ImportComponents, $this->file);
    //     }
    // }
}
