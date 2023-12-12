<?php

namespace App\Filament\Resources\TaskResource\RelationManagers;

use App\Models\DetailSubTask;
use App\Models\DetailTask;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DetailTasksRelationManager extends RelationManager
{
    protected static string $relationship = 'DetailTask';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama')
            ->columns([
                TextColumn::make('subtask.nama')
                ->label('Sub Task'),
                TextColumn::make('sub_task_price')
                ->state(function (DetailTask $record): float {
                    $subtotal = 0;
                    $detailsubtasks = DetailSubTask::select('*')->where('sub_task_id', $record->sub_task_id)->get();
                    foreach ($detailsubtasks as $key => $rincian){
                        $koefisien = $rincian->koefisien;
                        $harga_unit = $rincian->component->hargaunit;
                        $subtotal1 = $koefisien * $harga_unit;
                        $subtotal+=$subtotal1;
                    }
                    return $subtotal;
                })
                ->label('SubTask Price')
                ->money('IDR'),
                TextColumn::make('koefisien'),
                TextColumn::make('Total')
                ->state(function (DetailTask $record): float {
                    $subtotal = 0;
                    $detailsubtasks = DetailSubTask::select('*')->where('sub_task_id', $record->sub_task_id)->get();
                    foreach ($detailsubtasks as $key => $rincian){
                        $koefisien = $rincian->koefisien;
                        $harga_unit = $rincian->component->hargaunit;
                        $subtotal1 = $koefisien * $harga_unit;
                        $subtotal+=$subtotal1;
                    }
                    return $subtotal * $record->koefisien;
                })
                ->money('IDR'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
}
