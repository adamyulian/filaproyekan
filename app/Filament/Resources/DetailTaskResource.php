<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DetailTaskResource\Pages;
use App\Filament\Resources\DetailTaskResource\RelationManagers;
use App\Models\Component;
use App\Models\DetailCostSubTask;
use App\Models\DetailSubTask;
use App\Models\DetailTask;
use App\Models\SubTask;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DetailTaskResource extends Resource
{
    protected static ?string $model = DetailTask::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Planning';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Select::make('task_id')
            ->required()
            ->label('Task')
            ->options(Task::all()->pluck('nama', 'id'))
            ->searchable(),
            Select::make('sub_task_id')
            ->required()
            ->label('Sub Task')
            ->relationship(name: 'subtask', titleAttribute: 'nama'),
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
            ->defaultGroup('task.nama')
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
                TextColumn::make('koefisien')
                ->label('Volume'),
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
                // TextColumn::make('Remaining Budget')
                // ->money('IDR')
                // ->label('Remaining Budget')
                // ->weight(FontWeight::Bold)
                // ->color('primary')
                // ->state(function (DetailTask $record): float {
                //     $subtotal = 0;
                //     $detailsubtasks = DetailSubTask::select('*')->where('sub_task_id', $record->sub_task_id)->get();
                //     foreach ($detailsubtasks as $key => $rincian){
                //         $koefisien = $rincian->koefisien;
                //         $harga_unit = $rincian->component->hargaunit;
                //         $subtotal1 = $koefisien * $harga_unit;
                //         $subtotal+=$subtotal1;
                //     }
                //     $subtotalcost = 0;
                //     $detailcostsubtasks = DetailCostSubTask::select('*')->where('sub_task_id', $record->sub_task_id)->get();
                //     foreach ($detailcostsubtasks as $key => $rincian){
                //         $volume = $rincian->volume;
                //         $harga_unit = $rincian->costcomponent->hargaunit;
                //         $subtotal1 = $volume * $harga_unit;
                //         $subtotalcost+=$subtotal1;
                //     }
                //     return $subtotal-$subtotalcost;
                // }),


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
            'index' => Pages\ListDetailTasks::route('/'),
            'create' => Pages\CreateDetailTask::route('/create'),
            'edit' => Pages\EditDetailTask::route('/{record}/edit'),
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
