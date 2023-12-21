<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DetailCostTaskResource\Pages;
use App\Filament\Resources\DetailCostTaskResource\RelationManagers;
use App\Models\DetailCostTask;
use App\Models\DetailSubTask;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class DetailCostTaskResource extends Resource
{
    protected static ?string $model = DetailCostTask::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Detail Cost Tasks (BCWP)';

    protected static ?string $navigationGroup = 'Contruction';

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

                TextInput::make('volume')
                ->label('Earned Volume')
                ->required(),
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
            ->label('Sub Task')
            ->searchable(),
            TextColumn::make('subtask.unit.nama'),
            TextColumn::make('sub_task_price')
            ->state(function (DetailCostTask $record): float {
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
            ->label('Price')
            ->money('IDR'),
            TextColumn::make('volume')
            ->label('Earned Volume'),
            TextColumn::make('Total')
            ->state(function (DetailCostTask $record): float {
                $subtotal = 0;
                $detailsubtasks = DetailSubTask::select('*')->where('sub_task_id', $record->sub_task_id)->get();
                foreach ($detailsubtasks as $key => $rincian){
                    $koefisien = $rincian->koefisien;
                    $harga_unit = $rincian->component->hargaunit;
                    $subtotal1 = $koefisien * $harga_unit;
                    $subtotal+=$subtotal1;
                }
                return $subtotal * $record->volume;
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
                ActionGroup::make([
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDetailCostTasks::route('/'),
            'create' => Pages\CreateDetailCostTask::route('/create'),
            'view' => Pages\ViewDetailCostTask::route('/{record}'),
            'edit' => Pages\EditDetailCostTask::route('/{record}/edit'),
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
