<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DetailCostSubTaskResource\Pages;
use App\Filament\Resources\DetailCostSubTaskResource\RelationManagers;
use App\Models\Brand;
use App\Models\Component;
use App\Models\CostComponent;
use App\Models\DetailCostSubTask;
use App\Models\SubTask;
use App\Models\Task;
use App\Models\Unit;
use App\Models\User;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
use Illuminate\Support\Facades\Auth;

class DetailCostSubTaskResource extends Resource
{
    protected static ?string $model = DetailCostSubTask::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Detail Cost Sub Tasks (ACWP)';

    protected static ?string $navigationGroup = 'Construction';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('task_id')
                ->required()
                ->label('Task')
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
                    modifyQueryUsing: function (Builder $query) {
                        $userId = Auth::user()->id;
                        $query->where('user_id', $userId);}
                    )
                ->searchable(),

                Section::make('Choose Cost Component')
                    ->description('Choose Cost Component already created before')
                    ->compact()
                    ->columns(2)
                    ->schema([
                        Select::make('cost_component_id')
                        ->required()
                        ->label('Cost Component')
                        ->searchable()
                        ->live(onBlur:True)
                        ->afterStateUpdated(function (string $state, Unit $unit, Forms\Set $set) {
                            $set('unit', CostComponent::find($state)->unit->nama);
                            $set('hargaunit', CostComponent::find($state)->hargaunit);
                            $set('brand', CostComponent::find($state)->brand->nama);
                        })
                        ->relationship(
                            name: 'costcomponent',
                            titleAttribute: 'nama',
                            modifyQueryUsing: function (Builder $query) {
                                $userId = Auth::user()->id;
                                $query->where('user_id', $userId);}
                            )
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
                                ->searchable()
                                ->relationship(
                                    name: 'unit',
                                    titleAttribute: 'nama',
                                    modifyQueryUsing: function (Builder $query) {
                                        $userId = Auth::user()->id;
                                        $query->where('user_id', $userId);}
                                    ),
                            TextInput::make('hargaunit')
                                ->label('Harga Satuan')
                                ->required()
                                ->numeric(),
                            Textarea::make('deskripsi')
                                ->required()
                                ->maxLength(65535)
                                ->columnSpanFull(),
                            Select::make('brand_id')
                                ->required()
                                ->label('Brand')
                                ->options(Brand::all()->pluck('nama', 'id'))
                                ->searchable(),
                                ]),
                        TextInput::make('unit')
                        ->label('Unit')
                        ->disabled(),
                        TextInput::make('hargaunit')
                        ->label('Price')
                        ->disabled(),
                        TextInput::make('brand')
                        ->label('Merk')
                        ->disabled(),
                            ]),

                TextInput::make('volume')
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
            ->defaultGroup('subtask.nama')
            ->groups([
                Group::make('costcomponent.jenis')
                ->collapsible()
                ->label('Category'),
                Group::make('subtask.nama')
                ->collapsible()
                ->label('Sub Task'),
            ])
            ->columns([
                TextColumn::make('costcomponent.nama')
                ->label('Cost Component'),
                TextColumn::make('costcomponent.unit.nama')
                ->label('Unit'),
                TextColumn::make('costcomponent.hargaunit')
                ->label('Price')
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
