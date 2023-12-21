<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CostComponentResource\Pages;
use App\Filament\Resources\CostComponentResource\RelationManagers;
use App\Models\Brand;
use App\Models\CostComponent;
use App\Models\Unit;
use App\Models\User;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup as ActionsActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class CostComponentResource extends Resource
{
    protected static ?string $model = CostComponent::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    protected static ?string $navigationGroup = 'Contruction';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                    ->relationship(
                        name: 'unit',
                        titleAttribute: 'nama',
                        modifyQueryUsing: function (Builder $query) {
                            $userId = Auth::user()->id;
                            $query->where('user_id', $userId);}
                        )
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $userId = Auth::user()->id;
                $query->where('user_id', $userId);
            })
            ->defaultGroup('jenis')
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->label('Name'),
                Tables\Columns\TextColumn::make('unit.nama')
                    ->label('Unit')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hargaunit')
                    ->label('Price')
                    ->numeric()
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('brand.nama')
                    ->label('Brand')
                    ->numeric()
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
            'index' => Pages\ListCostComponents::route('/'),
            'create' => Pages\CreateCostComponent::route('/create'),
            'view' => Pages\ViewCostComponent::route('/{record}'),
            'edit' => Pages\EditCostComponent::route('/{record}/edit'),
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
