<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComponentResource\Pages;
use App\Filament\Resources\ComponentResource\RelationManagers;
use App\Models\Brand;
use App\Models\Component;
use App\Models\Unit;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Components\Tab;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class ComponentResource extends Resource
{
    protected static ?string $model = Component::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    protected static ?string $navigationGroup = 'Planning';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                ->required(),
                Select::make('jenis')
                ->required()
                ->options([
                    'Tenaga Kerja' => 'Tenaga Kerja',
                    'Bahan' => 'Bahan',
                    'Peralatan' => 'Peralatan',
                ]),
                Select::make('unit_id')
                // ->mutateFormDataUsing(function (array $data): array {
                //     $data['user_id']= auth()->id();
                //     return $data;
                // })
                ->required()
                ->label('Unit')
                ->relationship(
                    name: 'unit',
                    titleAttribute: 'nama',
                    modifyQueryUsing: function (Builder $query) {
                        $userId = Auth::user()->id;
                        $query->where('user_id', $userId);}
                    )
                // ->options(Unit::all()->pluck('nama', 'id'))
                ->createOptionForm([
                    // $userId = Auth::user()->id,
                    TextInput::make(name:'nama')
                    ->required(),
                    TextInput::make(name:'deskripsi')
                    ->required(),
                    Toggle::make('is_published')->label('Is Published?'),
                    TextInput::make(name:'user_id')
                    ->default(Auth::user()->id)
                    // ->mutateFormDataUsing(function (array $data): array {
                    //     $data['user_id']= auth()->id();
                    //     return $data;
                    // })
                    ->required()
                    ->disabled()
                    ])
                ->searchable(),
                TextInput::make('hargaunit')
                ->label('Harga Satuan')
                ->required(),
                TextInput::make('deskripsi')
                ->required(),
                Select::make('brand_id')
                ->required()
                ->label('Brand')
                ->options(Brand::all()->pluck('nama', 'id'))
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
            ->columns([
                TextColumn::make('nama')
                ->label('Name')
                ->sortable()
                ->description(fn (Component $record): string => $record->deskripsi)
                ->searchable(),
                TextColumn::make('unit.nama')
                ->label('Unit'),
                TextColumn::make('hargaunit')
                ->label('Price')
                ->money('IDR'),
                TextColumn::make('brand.nama')
                ->label('Brand'),

            ])
            ->filters([
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
                    ExportBulkAction::make(),
                ]),
            ])
            // ->headerActions([
            //     Tables\Actions\CreateAction::make(),

            // ])
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
            'index' => Pages\ListComponents::route('/'),
            'create' => Pages\CreateComponent::route('/create'),
            'edit' => Pages\EditComponent::route('/{record}/edit'),
        ];
    }

}
