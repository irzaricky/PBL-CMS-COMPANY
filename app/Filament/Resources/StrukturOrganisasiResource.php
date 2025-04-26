<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StrukturOrganisasiResource\Pages;
use App\Filament\Resources\StrukturOrganisasiResource\RelationManagers;
use App\Models\StrukturOrganisasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StrukturOrganisasiResource extends Resource
{
    protected static ?string $model = StrukturOrganisasi::class;
    protected static ?string $navigationGroup = 'Company Management';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $recordTitleAttribute = 'deskripsi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Posisi')
                    ->schema([
                        Forms\Components\Select::make('id_user')
                            ->label('Pengguna')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('Pilih pengguna yang menempati posisi ini'),

                        Forms\Components\TextInput::make('deskripsi')
                            ->label('Posisi/Jabatan')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Direktur Utama, Manager, dsb'),
                    ]),

                Forms\Components\Section::make('Media')
                    ->schema([
                        Forms\Components\FileUpload::make('thumbnail_struktur_organisasi')
                            ->label('Foto Profil')
                            ->image()
                            ->multiple(false)
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1')
                            ->directory('struktur-organisasi-images')
                            ->disk('public')
                            ->helperText('Unggah foto profil posisi ini (format: jpg, png)'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail_struktur_organisasi')
                    ->label('Foto')
                    ->circular()
                    ->disk('public'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Posisi/Jabatan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('deskripsi', 'asc')
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
            'index' => Pages\ListStrukturOrganisasis::route('/'),
            'create' => Pages\CreateStrukturOrganisasi::route('/create'),
            'edit' => Pages\EditStrukturOrganisasi::route('/{record}/edit'),
        ];
    }
}
