<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleResource\Pages;
use App\Filament\Resources\ScheduleResource\RelationManagers;
use App\Models\Schedule;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Planning';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('sub_task_id')
                ->required()
                ->columnSpan(2)
                ->label('Sub Task')
                ->relationship(
                    name: 'subtask',
                    titleAttribute: 'nama',
                    modifyQueryUsing: function (Builder $query) {
                        $userId = Auth::user()->id;
                        $query->where('user_id', $userId);}
                    )
                ->searchable(),
                Forms\Components\DatePicker::make('start')
                    ->columnSpan(1)
                    ->live()
                    ->afterStateUpdated(function (string $state, Forms\Set $set) {
                        $set('duration', 'start');
                    })
                    ->required(),
                Forms\Components\DatePicker::make('finish')
                    ->live()
                    ->afterStateUpdated(function (string $state, Forms\Set $set) {
                        $set('duration', 'finish');
                    })
                    ->columnSpan(1)
                    ->required(),
                    Placeholder::make('duration')
                    ->columnSpan(1)
                    ->content(function($get){
                        $toDate = Carbon::parse($get('finish'));
                        $fromDate = Carbon::parse($get('start'));
                        $days = $toDate->diffInDays($fromDate);

                        return $days . " Days";
                    })

            ])
            ->columns(5);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $userId = Auth::user()->id;
                $query->where('user_id', $userId);
            })
            ->columns([
                Tables\Columns\TextColumn::make('subtask.nama')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('finish')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration')
                    ->state(function (Schedule $record): float {
                        $schedule = Schedule::select('*')->where('id', $record->id)->get();
                        foreach ($schedule as $key => $rincian){
                            $toDate = Carbon::parse($rincian->finish);
                            $fromDate = Carbon::parse($rincian->start);
                            $days = $toDate->diffInDays($fromDate);

                        }
                        return $days ;
                    })
                    ->sortable(),
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
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
}
