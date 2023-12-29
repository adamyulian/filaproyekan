<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use App\Models\Task;
use Filament\Tables;
use App\Models\Schedule;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ScheduleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ScheduleResource\RelationManagers;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('task_id')
                ->required()
                ->label('Task')
                ->live(onBlur:True)
                    ->afterStateUpdated(function (string $state, Forms\Set $set) {
                        $set('sub_task_id', Task::find($state)->id );})
                ->relationship(
                    name: 'task',
                    titleAttribute: 'nama',
                    modifyQueryUsing: function (Builder $query) {
                        $userId = Auth::user()->id;
                        $query->where('user_id', $userId);}
                    )
                ->searchable(),
                Select::make('sub_task_id')
                ->required()
                ->label('Sub Task')

                ->relationship(
                    name: 'subtask',
                    titleAttribute: 'nama',
                    modifyQueryUsing: function (Builder $query, $get) {
                        $userId = Auth::user()->id;
                        $taskId = $get('task_id');
                        $query->where(['user_id','task_id'], [$userId, $taskId]);}
                    )
                ->searchable(),

                Forms\Components\DatePicker::make('start')
                    ->live(onBlur:True)
                    ->afterStateUpdated(function (string $state, Set $set) {
                        $set('duration', 'start');})
                    ->required(),
                Forms\Components\DatePicker::make('finish')
                    ->live(onBlur:True)
                    ->afterStateUpdated(function (string $state, Set $set) {
                        $set('duration', 'finish');})
                    ->required(),
                Forms\Components\Placeholder::make('duration')
                    ->content(function ($get){
                        $toDate = Carbon::parse($get('finish'));
                        $fromDate = Carbon::parse($get('start'));
                        $days = $toDate->diffInDays($fromDate);
                        // $days = $fromDate->diffInDaysFiltered(function(Carbon $date){
                        //     return !($date->isSunday()||$date->isSaturday());
                        // }, $toDate);
                        return $days . " In Work Days";
                    })
                    // ->content(function ($get): float {
                    //         $toData = $get('finish');
                    //         $fromDate = $get('start');
                    //         $days = $fromData->diffInDaysFiltered(function(Carbon $date) {
                    //             return !($date->isSunday() || $date->isSaturday());
                    //         }, $toData);
                    //     }
                    //     return $days . "in Work Days";
                    // ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('task_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sub_task_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('finish')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
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
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'view' => Pages\ViewSchedule::route('/{record}'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
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
