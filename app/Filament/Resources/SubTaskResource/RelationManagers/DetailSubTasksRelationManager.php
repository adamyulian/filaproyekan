<?php

namespace App\Filament\Resources\SubTaskResource\RelationManagers;

use App\Models\Brand;
use App\Models\Component;
use App\Models\DetailSubTask;
use App\Models\SubTask;
use App\Models\Unit;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
                Select::make('component_id')
                ->required()
                ->label('Component')
                ->relationship(name: 'component', titleAttribute: 'nama')
                ->createOptionForm([
                        TextInput::make('nama')
                        ->required(),
                        Select::make('jenis')
                        ->required()
                        ->options([
                            'Tenaga Kerja' => 'Tenaga Kerja',
                            'Bahan' => 'Bahan',
                            'Peralatan' => 'Peralatan',
                        ]),
                        Select::make('unit_id')
                        ->required()
                        ->label('Unit')
                        ->options(Unit::all()->pluck('nama', 'id'))
                        ->searchable(),
                        TextInput::make('hargaunit')
                        ->label('Harga Satuan')
                        ->required(),
                        TextInput::make('deskripsi')
                        ->required(),
                        Select::make('brand_id')
                        ->required()
                        ->label('Brand')
                        ->options(Brand::all()->pluck('nama', 'id'))
                        ->searchable(),
                        Select::make('user_id')
                        ->options(User::all()->pluck('name','id'))
                        ->searchable()
                        ])
                ->searchable(),
                TextInput::make('koefisien')
                ->required(),
                // Select::make('user_id')
                // ->options(User::all()->pluck('name','id'))
                // ->searchable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('koefisien')
            ->defaultGroup('component.jenis')
            ->columns([
                TextColumn::make('component.nama')
                ->label('Nama Komponen'),
                TextColumn::make('component.unit.nama')
                ->label('Satuan'),
                TextColumn::make('component.hargaunit')
                ->label('Harga Satuan')
                ->money('IDR'),
                TextColumn::make('koefisien'),
                TextColumn::make('Total')
                ->state(function (DetailSubTask $record): float {
                    return $record->component->hargaunit * $record->koefisien;
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
