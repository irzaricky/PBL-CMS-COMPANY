<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_event')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('deskripsi_event')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('lokasi_event')
                    ->required()
                    ->maxLength(200),
                Forms\Components\DateTimePicker::make('waktu_start_event')
                    ->required(),
                Forms\Components\DateTimePicker::make('waktu_end_event')
                    ->required(),
                Forms\Components\TextInput::make('link_daftar_event')
                    ->maxLength(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_event')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lokasi_event')
                    ->searchable(),
                Tables\Columns\TextColumn::make('waktu_start_event')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('waktu_end_event')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('link_daftar_event')
                    ->searchable(),
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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
