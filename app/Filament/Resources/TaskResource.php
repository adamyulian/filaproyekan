<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubTaskResource\Pages\ViewTask;
use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Filament\Resources\TaskResource\RelationManagers\DetailCostSubTaskRelationManager;
use App\Filament\Resources\TaskResource\RelationManagers\DetailCostTaskRelationManager;
use App\Filament\Resources\TaskResource\RelationManagers\DetailTasksRelationManager;
use App\Models\DetailCostSubTask;
use App\Models\DetailCostTask;
use App\Models\DetailSubTask;
use App\Models\DetailTask;
use App\Models\SubTask;
use App\Models\Task;
use App\Models\Unit;
use Filament\Actions\ActionGroup;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup as ActionsActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

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
                ->createOptionForm([
                    Forms\Components\TextInput::make('nama')
                        ->required(),
                    Forms\Components\TextInput::make('deskripsi')
                        ->required(),
                    Forms\Components\Toggle::make('is_published')->label('Visibility')
                        ])
                ->searchable()
                ->relationship(
                    name: 'unit',
                    titleAttribute: 'nama',
                    modifyQueryUsing: function (Builder $query) {
                        $userId = Auth::user()->id;
                        $query->where('user_id', $userId);}
                    ),
                Radio::make('is_published')->label('Is Published?')->boolean()

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $userId = Auth::user()->id;
                $query->where('user_id', $userId);
            })
            ->columns([
                TextColumn::make('nama')
                ->sortable()
                ->description(fn (Task $record): string => $record->deskripsi)
                ->searchable()
                ->label('Name'),
                TextColumn::make('unit.nama'),
                TextColumn::make('BCWS')
                ->state(function (Task $record): float {
                    $detailtasks = DetailTask::select('*')->where('task_id', $record->id)->get();
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
                ->label('BCWS (Planned)'),
                TextColumn::make('BCWP')
                ->state(function (Task $record): float {
                    $detailcosttasks = DetailCostTask::select('*')->where('task_id', $record->id)->get();
                    $taskvalue = 0;
                    foreach ($detailcosttasks as $key => $rincian1){
                        $subtotal = 0;
                        $detailsubtasks = DetailSubTask::select('*')->where('sub_task_id', $rincian1->sub_task_id)->get();
                        foreach ($detailsubtasks as $key => $rincian){
                            $koefisien = $rincian->koefisien;
                            $harga_unit = $rincian->component->hargaunit;
                            $subtotal1 = $koefisien * $harga_unit;
                            $subtotal+=$subtotal1;
                        }
                        $taskvalue1 = $subtotal * $rincian1->volume;
                        $taskvalue+=$taskvalue1;
                    }
                    return $taskvalue;
                })
                ->money('IDR')
                ->label('BCWP (Earned)'),
                TextColumn::make('ACWP')
                ->state(function (Task $record): float {
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
                ->money('IDR')
                ->label('ACWP (Actual)'),
                IconColumn::make('is_published')
                ->label('Visibility')
                ->boolean(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                ActionsActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    ])
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


    public static function infolist(Infolist $infolist): Infolist

    {
        return $infolist
            ->schema([
                TextEntry::make('nama')
                ->label('Name'),
                TextEntry::make('deskripsi')
                ->label('Description'),
                TextEntry::make('BCWS')
                ->state(function (Task $record): float {
                    $detailtasks = DetailTask::select('*')->where('task_id', $record->id)->get();
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
                ->label('BCWS (Planned)'),
                TextEntry::make('BCWP')
                ->state(function (Task $record): float {
                    $detailcosttasks = DetailCostTask::select('*')->where('task_id', $record->id)->get();
                    $taskvalue = 0;
                    foreach ($detailcosttasks as $key => $rincian1){
                        $subtotal = 0;
                        $detailsubtasks = DetailSubTask::select('*')->where('sub_task_id', $rincian1->sub_task_id)->get();
                        foreach ($detailsubtasks as $key => $rincian){
                            $koefisien = $rincian->koefisien;
                            $harga_unit = $rincian->component->hargaunit;
                            $subtotal1 = $koefisien * $harga_unit;
                            $subtotal+=$subtotal1;
                        }
                        $taskvalue1 = $subtotal * $rincian1->volume;
                        $taskvalue+=$taskvalue1;
                    }
                    return $taskvalue;
                })
                ->money('IDR')
                ->label('BCWP (Earned)'),
                TextEntry::make('ACWP')
                ->state(function (Task $record): float {
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
                ->money('IDR')
                ->label('ACWP (Actual)'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            DetailTasksRelationManager::class,
            DetailCostTaskRelationManager::class,
            DetailCostSubTaskRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'view' => Pages\ViewTask::route('/{record}'),
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
