<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnduhanResource\Pages;
use App\Filament\Resources\UnduhanResource\RelationManagers;
use App\Models\Unduhan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UnduhanResource extends Resource
{
    protected static ?string $model = Unduhan::class;
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_kategori_unduhan')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('id_user')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('nama_unduhan')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('lokasi_file')
                    ->required()
                    ->maxLength(200),
                Forms\Components\Textarea::make('deskripsi')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('jumlah_unduhan')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_kategori_unduhan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('id_user')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_unduhan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lokasi_file')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jumlah_unduhan')
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
            'index' => Pages\ListUnduhans::route('/'),
            'create' => Pages\CreateUnduhan::route('/create'),
            'edit' => Pages\EditUnduhan::route('/{record}/edit'),
        ];
    }
}
