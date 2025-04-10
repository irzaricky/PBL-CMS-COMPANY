<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LamaranResource\Pages;
use App\Filament\Resources\LamaranResource\RelationManagers;
use App\Models\Lamaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LamaranResource extends Resource
{
    protected static ?string $model = Lamaran::class;
    protected static ?string $navigationGroup = 'Customer Service';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_user')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('id_lowongan')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('cv')
                    ->maxLength(200),
                Forms\Components\TextInput::make('portfolio')
                    ->maxLength(200),
                Forms\Components\TextInput::make('status_lamaran')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_user')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('id_lowongan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cv')
                    ->searchable(),
                Tables\Columns\TextColumn::make('portfolio')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status_lamaran'),
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
            'index' => Pages\ListLamarans::route('/'),
            'create' => Pages\CreateLamaran::route('/create'),
            'edit' => Pages\EditLamaran::route('/{record}/edit'),
        ];
    }
}
