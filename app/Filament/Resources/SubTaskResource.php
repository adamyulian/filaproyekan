<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubTaskResource\Pages;
use App\Filament\Resources\SubTaskResource\RelationManagers;
use App\Filament\Resources\SubTaskResource\RelationManagers\DetailSubTasksRelationManager;
use App\Models\DetailSubTask;
use App\Models\SubTask;
use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class SubTaskResource extends Resource
{
    protected static ?string $model = SubTask::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'Planning';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make(name:'nama')->required(),
                TextInput::make(name:'deskripsi')->required(),
                Select::make('unit_id')
                ->required()
                ->label('Unit')
                ->options(Unit::all()->pluck('nama', 'id'))
                ->searchable(),
                Radio::make('is_published')->label('Is Published?')->boolean()

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                ->sortable()
                ->description(fn (SubTask $record): string => $record->deskripsi)
                ->searchable(),
                TextColumn::make('unit.nama'),
                TextColumn::make('NilaiHSPK')
                ->state(function (SubTask $record): float {
                    $subtotal = 0;
                    $detailsubtasks = DetailSubTask::select('*')->where('sub_task_id', $record->id)->get();
                    foreach ($detailsubtasks as $key => $rincian){
                        $koefisien = $rincian->koefisien;
                        $harga_unit = $rincian->component->hargaunit;
                        $subtotal1 = $koefisien * $harga_unit;
                        $subtotal+=$subtotal1;
                    }
                    return $subtotal;
                })
                ->money('IDR')
                ->sortable()
                ->label('Sub Task Price'),
                IconColumn::make('is_published')
                ->label('Status Tayang')
                ->boolean(),
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
                    ExportBulkAction::make()
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist

    {
        return $infolist
            ->schema([
                TextEntry::make('nama'),
                TextEntry::make('deskripsi'),
                TextEntry::make('NilaiHSPK')
                ->state(function (SubTask $record): float {
                    $subtotal = 0;
                    $detailsubtasks = DetailSubTask::select('*')->where('sub_task_id', $record->id)->get();
                    foreach ($detailsubtasks as $key => $rincian){
                        $koefisien = $rincian->koefisien;
                        $harga_unit = $rincian->component->hargaunit;
                        $subtotal1 = $koefisien * $harga_unit;
                        $subtotal+=$subtotal1;
                    }
                    return $subtotal;
                })
                ->money('IDR')
                ->label('Sub Task Price')
            ]);
    }

    public static function getRelations(): array
    {
        return [
            DetailSubTasksRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubTasks::route('/'),
            'create' => Pages\CreateSubTask::route('/create'),
            'view' => Pages\ViewSubTask::route('/{record}'),
            'edit' => Pages\EditSubTask::route('/{record}/edit'),
        ];
    }
}
