<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KontenSliderResource\Pages;
use App\Filament\Resources\KontenSliderResource\RelationManagers;
use App\Models\KontenSlider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KontenSliderResource extends Resource
{
    protected static ?string $model = KontenSlider::class;
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_user')
                    ->numeric(),
                Forms\Components\TextInput::make('id_galeri')
                    ->numeric(),
                Forms\Components\TextInput::make('id_produk')
                    ->numeric(),
                Forms\Components\TextInput::make('id_lowongan')
                    ->numeric(),
                Forms\Components\TextInput::make('id_event')
                    ->numeric(),
                Forms\Components\TextInput::make('id_artikel')
                    ->numeric(),
                Forms\Components\TextInput::make('judul_header')
                    ->required()
                    ->maxLength(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_user')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('id_galeri')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('id_produk')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('id_lowongan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('id_event')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('id_artikel')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('judul_header')
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
            'index' => Pages\ListKontenSliders::route('/'),
            'create' => Pages\CreateKontenSlider::route('/create'),
            'edit' => Pages\EditKontenSlider::route('/{record}/edit'),
        ];
    }
}
