<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\DetailSubTask;
use App\Models\DetailTask;
use App\Models\SubTask;
use App\Models\Task;
use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

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
                ->description(fn (Task $record): string => $record->deskripsi)
                ->searchable(),
                TextColumn::make('unit.nama'),
                TextColumn::make('Task Value')
                ->state(function (Task $record): float {
                    $detailtasks = DetailTask::select('*')->where('task_id', $record->id)->get();
                    // $detailsubtasks = DetailSubTask::select('*')->where('sub_task_id', 1)->get();
                    // foreach ($detailsubtasks as $key => $rincian){
                    //     $koefisien = $rincian->koefisien;
                    //     $harga_unit = $rincian->component->hargaunit;
                    //     $subtotal1 = $koefisien * $harga_unit;
                    //     $subtotal+=$subtotal1;
                    // }

                    $taskvalue = 0;
                    foreach ($detailtasks as $key => $rincian1){
                        $subtotal = 0;
                        $detailsubtasks = DetailSubTask::select('*')->where('sub_task_id', $rincian1->sub_task_id)->get();
                        foreach ($detailsubtasks as $key => $rincian){
                            $koefisien = $rincian->koefisien;
                            $harga_unit = $rincian->component->hargaunit;
                            $subtotal1 = $koefisien * $harga_unit;
                            $subtotal+=$subtotal1;
                        }
                        $taskvalue1 = $subtotal * $rincian1->koefisien;
                        $taskvalue+=$taskvalue1;
                    }
                    return $taskvalue;
                })
                ->money('IDR')
                ->label('Task Price'),
                IconColumn::make('is_published')
                ->label('Status Tayang')
                ->boolean(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
