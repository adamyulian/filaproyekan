<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Attendance;
use Filament\Tables\Table;
use App\Rules\AttendanceRadius;
use Filament\Resources\Resource;
use Filament\Actions\CreateAction;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use Stevebauman\Location\Facades\Location;
use Cheesegrits\FilamentGoogleMaps\Fields\Map;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    public static function getBreadcrumb(): string
    {
        return '';
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('User')
                    ->default(Auth::user()->name)
                    ->columnSpanFull()
                    ->disabled(),
                Section::make('Location')
                    ->schema([
                        Map::make('loc')
                            ->columnSpanFull()
                            ->label('Google Map Location')
                            ->geolocate() // adds a button to request device location and set map marker accordingly
                            ->geolocateOnLoad(true, 'always')// Enable geolocation on load for every form
                            ->draggable(false) // Disable dragging to move the marker
                            ->clickable(false) // Disable clicking to move the marker
                            ->defaultZoom(15) // Set the initial zoom level to 500
                            ->autocomplete('note') // field on form to use as Places geocompletion field
                            ->autocompleteReverse(true) // reverse geocode marker location to autocomplete field
                            ->reactive()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $set('lat', $state['lat']);
                                $set('long', $state['lng']);})
                            ->rules([new AttendanceRadius(-7.271158089956116, 112.74158163514575, 100)]),
                        TextInput::make('lat')
                            ->numeric()
                            ->columnSpan(1),
                        TextInput::make('long')
                            ->numeric()
                            ->columnSpan(1),
                        TextInput::make('note')
                            ->label('Address')
                            ->columnSpanFull(),
                        ])
                        ->collapsible()
                        ->columns(2),
                ])
                ;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Check In')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('note')
                    ->label('Address')
                    ->limit(30)
                    ->sortable(),
                Tables\Columns\TextColumn::make('lat')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('long')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'view' => Pages\ViewAttendance::route('/{record}'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
