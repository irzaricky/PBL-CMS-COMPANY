<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProfilPerusahaanResource\Pages;
use App\Filament\Resources\ProfilPerusahaanResource\RelationManagers;
use App\Models\ProfilPerusahaan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProfilPerusahaanResource extends Resource
{
    protected static ?string $model = ProfilPerusahaan::class;
    protected static ?string $navigationGroup = 'Company Profile';
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_perusahaan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('deskripsi_perusahaan')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('visi')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('misi')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('sejarah')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('pencapaian_perusahaan')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('alamat_perusahaan')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email_perusahaan')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nomor_telepon_perusahaan')
                    ->tel()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_perusahaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('alamat_perusahaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_perusahaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nomor_telepon_perusahaan')
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
            'index' => Pages\ListProfilPerusahaans::route('/'),
            'create' => Pages\CreateProfilPerusahaan::route('/create'),
            'view' => Pages\ViewProfilPerusahaan::route('/{record}'),
            'edit' => Pages\EditProfilPerusahaan::route('/{record}/edit'),
        ];
    }
}
