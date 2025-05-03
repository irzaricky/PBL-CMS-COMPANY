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
    protected static ?string $navigationGroup = 'Company Management';
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $recordTitleAttribute = 'nama_perusahaan';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Utama')
                    ->schema([
                        Forms\Components\TextInput::make('nama_perusahaan')
                            ->label('Nama Perusahaan')
                            ->required()
                            ->maxLength(100),

                        Forms\Components\FileUpload::make('logo_perusahaan')
                            ->label('Logo Perusahaan')
                            ->image()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1')
                            ->directory('perusahaan-logo')
                            ->disk('public')
                            ->helperText('Unggah logo perusahaan (format: jpg, png, svg)')
                            ->optimize('webp'),

                        Forms\Components\FileUpload::make('thumbnail_perusahaan')
                            ->label('Gambar Perusahaan')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->directory('perusahaan-images')
                            ->disk('public')
                            ->helperText('Unggah gambar untuk profil perusahaan (format: jpg, png)')
                            ->optimize('webp'),

                        Forms\Components\Select::make('id_galeri')
                            ->label('Galeri Terkait')
                            ->relationship('galeri', 'judul_galeri')
                            ->searchable()
                            ->preload()
                            ->helperText('Pilih galeri yang terkait dengan perusahaan (opsional)'),
                    ]),

                Forms\Components\Section::make('Kontak dan Deskripsi')
                    ->schema([
                        Forms\Components\Textarea::make('alamat_perusahaan')
                            ->label('Alamat')
                            ->required()
                            ->maxLength(200)
                            ->rows(3)
                            ->placeholder('Masukkan alamat lengkap perusahaan'),

                        Forms\Components\TextInput::make('email_perusahaan')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(50)
                            ->placeholder('contoh@perusahaan.com'),

                        Forms\Components\RichEditor::make('deskripsi_perusahaan')
                            ->label('Deskripsi Perusahaan')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('perusahaan-attachments')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo_perusahaan')
                    ->label('Logo')
                    ->circular()
                    ->disk('public'),

                Tables\Columns\TextColumn::make('nama_perusahaan')
                    ->label('Nama Perusahaan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email_perusahaan')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('alamat_perusahaan')
                    ->label('Alamat')
                    ->limit(50)
                    ->tooltip(fn(ProfilPerusahaan $record): string => $record->alamat_perusahaan)
                    ->searchable(),

                Tables\Columns\TextColumn::make('galeri.judul_galeri')
                    ->label('Galeri Terkait')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

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
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            // 'create' => Pages\CreateProfilPerusahaan::route('/create'),
            'edit' => Pages\EditProfilPerusahaan::route('/{record}/edit'),
        ];
    }
}
