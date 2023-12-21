<?php

namespace App\Filament\Resources\TaskResource\RelationManagers;

use App\Models\DetailCostSubTask;
use App\Models\DetailCostTask;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DetailCostSubTaskRelationManager extends RelationManager
{
    protected static string $relationship = 'DetailCostSubTask';

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
                ->label('Sub Task')
                ->searchable(),
                TextColumn::make('Total')
                ->state(function (DetailCostSubTask $record): float {
                    $subtotal = 0;
                    $detailcostsubtasks = DetailCostSubTask::select('*')->where('task_id', $record->id)->get();
                    foreach ($detailcostsubtasks as $key => $rincian){
                        $koefisien = $rincian->volume;
                        $harga_unit = $rincian->costcomponent->hargaunit;
                        $subtotal1 = $koefisien * $harga_unit;
                        $subtotal+=$subtotal1;
                    }
                    return $subtotal;
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
