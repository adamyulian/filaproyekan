<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DetailCostSubTaskResource\Pages;
use App\Filament\Resources\DetailCostSubTaskResource\RelationManagers;
use App\Models\Brand;
use App\Models\CostComponent;
use App\Models\DetailCostSubTask;
use App\Models\SubTask;
use App\Models\Unit;
use App\Models\User;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup as ActionsActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DetailCostSubTaskResource extends Resource
{
    protected static ?string $model = DetailCostSubTask::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Contruction';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('sub_task_id')
                ->required()
                ->label('Sub Task')
                ->options(SubTask::all()->pluck('nama', 'id'))
                ->searchable(),
                Select::make('cost_component_id')
                ->required()
                ->label('Cost Component')
                ->options(CostComponent::all()->pluck('nama','id'))
                ->createOptionForm([
                    Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                    Select::make('jenis')
                        ->required()
                        ->options([
                            'Tenaga Kerja' => 'Tenaga Kerja',
                            'Bahan' => 'Bahan',
                            'Peralatan' => 'Peralatan',
                            'Subkontraktor' => 'Subkontraktor'
                        ]),
                    Select::make('unit_id')
                    ->required()
                    ->label('Unit')
                    ->options(Unit::all()->pluck('nama', 'id'))
                    ->searchable(),
                    Forms\Components\TextInput::make('hargaunit')
                        ->label('Harga Satuan')
                        ->required()
                        ->numeric(),
                    Forms\Components\Textarea::make('deskripsi')
                        ->required()
                        ->maxLength(65535)
                        ->columnSpanFull(),
                    Select::make('brand_id')
                    ->required()
                    ->label('Brand')
                    ->options(Brand::all()->pluck('nama', 'id'))
                    ->searchable(),
                    Select::make('user_id')
                    ->options(User::all()->pluck('name','id'))
                    ->searchable(),
                    ])
                ->searchable(),
                TextInput::make('volume')
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultGroup('subtask.nama')
            ->groups([
                Group::make('costcomponent.jenis')
                ->collapsible()
                ->label('Jenis Biaya'),
                Group::make('subtask.nama')
                ->collapsible()
                ->label('Sub Task'),
            ])
            ->columns([
                TextColumn::make('subtask.nama')
                ->label('Sub Task')
                ->searchable(),
                TextColumn::make('costcomponent.nama')
                ->label('Komponen'),
                TextColumn::make('costcomponent.unit.nama')
                ->label('Satuan'),
                TextColumn::make('costcomponent.hargaunit')
                ->label('Harga Satuan')
                ->money('IDR'),
                TextColumn::make('volume'),
                TextColumn::make('Total')
                ->state(function (DetailCostSubTask $record): float {
                    return $record->costcomponent->hargaunit * $record->volume;
                })
                ->money('IDR'),
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
            'index' => Pages\ListDetailCostSubTasks::route('/'),
            'create' => Pages\CreateDetailCostSubTask::route('/create'),
            'view' => Pages\ViewDetailCostSubTask::route('/{record}'),
            'edit' => Pages\EditDetailCostSubTask::route('/{record}/edit'),
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
