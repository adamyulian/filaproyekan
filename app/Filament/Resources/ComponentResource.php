<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComponentResource\Pages;
use App\Filament\Resources\ComponentResource\RelationManagers;
use App\Models\Brand;
use App\Models\Component;
use App\Models\Unit;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ComponentResource extends Resource
{
    protected static ?string $model = Component::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                TextInput::make('harga_Unit')
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
                ->searchable(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                ->sortable()->description(fn (Component $record): string => $record->deskripsi),
                SelectColumn::make('jenis')
                ->options([
                    'Tenaga Kerja' => 'Tenaga Kerja',
                    'Bahan' => 'Bahan',
                    'Peralatan' => 'Peralatan',
                ]),
                TextColumn::make('unit.nama'),
                TextColumn::make('harga_Unit')
                ->label('Harga Satuan')
                ->money('IDR'),
                // ->numeric(
                //     decimalPlaces: 0,
                //     decimalSeparator: '.',
                //     thousandsSeparator: ','),
                TextColumn::make('brand.nama'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComponents::route('/'),
            'create' => Pages\CreateComponent::route('/create'),
            'edit' => Pages\EditComponent::route('/{record}/edit'),
        ];
    }
}
