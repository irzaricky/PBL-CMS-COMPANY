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
    protected static ?string $navigationGroup = 'Downloads';
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_kategori_unduhan')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('id_users')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('lokasi_file')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('jenis_file')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('ukuran_file')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('deskripsi')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('jumlah_unduhan')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('status_publikasi')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_kategori_unduhan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('id_users')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lokasi_file')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_file')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ukuran_file')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_unduhan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('status_publikasi')
                    ->boolean(),
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
            'index' => Pages\ListUnduhans::route('/'),
            'create' => Pages\CreateUnduhan::route('/create'),
            'view' => Pages\ViewUnduhan::route('/{record}'),
            'edit' => Pages\EditUnduhan::route('/{record}/edit'),
        ];
    }
}
