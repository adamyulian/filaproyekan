<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use App\Models\Task;
use Filament\Tables;
use App\Models\SubTask;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Component;
use App\Models\DetailTask;
use Filament\Tables\Table;
use App\Models\DetailSubTask;
use Filament\Resources\Resource;
use App\Models\DetailCostSubTask;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DetailTaskResource\Pages;
use App\Filament\Resources\DetailTaskResource\RelationManagers;

class DetailTaskResource extends Resource
{
    protected static ?string $model = DetailTask::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Planning';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make([
                Select::make('task_id')
                ->label('Task')
                ->relationship(name: 'task', titleAttribute: 'nama')
                ->searchable()
                ->required()
                ->columnSpan(2),
                Select::make('sub_task_id')
                ->required()
                ->label('Sub Task')
                ->relationship(name: 'subtask', titleAttribute: 'nama')
                ->live(onBlur:True)
                ->afterStateUpdated(function (string $state, Set $set) {
                     $set('total', 'subtask');})
                ->columnSpan(2),
                TextInput::make('koefisien')
                ->required()
                ->live(onBlur:True)
                ->afterStateUpdated(function (string $state, Set $set) {
                     $set('total', 'koefisien');})
                ->columnSpan(1),
                Forms\Components\Placeholder::make('total')
                    ->columnSpan(1)
                    ->content(function ($get){
                        $subtotal = 0;
                        $detailsubtasks = DetailSubTask::select('*')->where('sub_task_id', $get('subtask'))->get();
                        dd($detailsubtasks);
                        foreach ($detailsubtasks as $key => $rincian){
                            $koefisien = $rincian->koefisien;
                            $harga_unit = $rincian->component->hargaunit;
                            $subtotal1 = $koefisien * $harga_unit;
                            $subtotal+=$subtotal1;
                        }
                        // dd($subtotal);
                        return $subtotal * $get('koefisien');
                    })
            ])
            ->columns(6)
            ->label('Detail Task Information'),
            
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
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $userId = Auth::user()->id;
                $query->where('user_id', $userId);
            })
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
