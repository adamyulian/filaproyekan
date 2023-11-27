<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DetailSubTaskResource\Pages;
use App\Filament\Resources\DetailSubTaskResource\RelationManagers;
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
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class DetailSubTaskResource extends Resource
{
    protected static ?string $model = DetailSubTask::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Planning';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('sub_task_id')
                ->required()
                ->label('Sub Task')
                ->options(SubTask::all()->pluck('nama', 'id'))
                ->searchable(),
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subtask.nama')
                ->label('Sub Task')
                ->searchable(),
                TextColumn::make('component.nama')
                ->label('Nama Komponen'),
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
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make(),
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
            'index' => Pages\ListDetailSubTasks::route('/'),
            'create' => Pages\CreateDetailSubTask::route('/create'),
            'edit' => Pages\EditDetailSubTask::route('/{record}/edit'),
        ];
    }
}
