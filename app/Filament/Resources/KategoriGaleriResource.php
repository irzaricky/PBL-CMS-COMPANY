<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\GaleriCluster;
use App\Filament\Resources\KategoriGaleriResource\Pages;
use App\Filament\Resources\KategoriGaleriResource\RelationManagers;
use App\Models\KategoriGaleri;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KategoriGaleriResource extends Resource
{
    protected static ?string $model = KategoriGaleri::class;

    protected static ?string $navigationIcon = 'heroicon-s-tag';

    protected static ?string $cluster = GaleriCluster::class;
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_kategori_galeri')
                    ->required()
                    ->maxLength(50),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_kategori_galeri')
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
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListKategoriGaleris::route('/'),
            'create' => Pages\CreateKategoriGaleri::route('/create'),
            'edit' => Pages\EditKategoriGaleri::route('/{record}/edit'),
        ];
    }
}
