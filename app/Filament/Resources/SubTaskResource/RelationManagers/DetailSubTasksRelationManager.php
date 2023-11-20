<?php

namespace App\Filament\Resources\SubTaskResource\RelationManagers;

use App\Models\DetailSubTask;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DetailSubTasksRelationManager extends RelationManager
{
    protected static string $relationship = 'DetailSubTask';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('koefisien')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('koefisien')
            ->columns([
                TextColumn::make('component.nama')
                ->label('Nama Komponen'),
                TextColumn::make('component.harga_Unit')
                ->label('Harga Satuan')
                ->money('IDR'),
                TextColumn::make('koefisien'),
                TextColumn::make('Total')
                ->state(function (DetailSubTask $record): float {
                    return $record->component->harga_Unit * $record->koefisien;
                })
                ->money('IDR')
                ->sortable(),
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
