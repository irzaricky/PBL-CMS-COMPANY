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
use App\Helpers\FilamentGroupingHelper;

class ProfilPerusahaanResource extends Resource
{
    protected static ?string $model = ProfilPerusahaan::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $recordTitleAttribute = 'nama_perusahaan';
    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return FilamentGroupingHelper::getNavigationGroup('Company Owner');
    }

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
                            ->directory('perusahaan-logo')
                            ->disk('public')
                            ->helperText('Unggah logo perusahaan (format: jpg, png, svg)')
                            ->imageEditor()
                            ->optimize('webp'),


                        Forms\Components\FileUpload::make('thumbnail_perusahaan')
                            ->label('Gambar Perusahaan')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->directory('perusahaan-images')
                            ->disk('public')
                            ->helperText('Unggah gambar untuk profil perusahaan (format: jpg, png)')
                            ->imageEditor()
                            ->imageResizeMode('contain')
                            ->imageResizeTargetWidth(1280)
                            ->imageResizeTargetHeight(720)
                            ->optimize('webp'),
                    ]),

                Forms\Components\Section::make('Kontak dan Deskripsi')
                    ->schema([
                        Forms\Components\Textarea::make('alamat_perusahaan')
                            ->label('Alamat')
                            ->required()
                            ->maxLength(200)
                            ->rows(3)
                            ->placeholder('Masukkan alamat lengkap perusahaan'),

                        Forms\Components\TextInput::make('link_alamat_perusahaan')
                            ->label('Link Lokasi Perusahaan (Google Maps)')
                            ->required()
                            ->placeholder('<iframe src="https://www.google.com/maps/embed?..')
                            ->helperText('Berikan URL Embed Google Maps untuk lokasi perusahaan')
                            ->prefixIcon('heroicon-s-map-pin')
                            ->suffixAction(
                                Forms\Components\Actions\Action::make('open')
                                    ->icon('heroicon-o-arrow-top-right-on-square')
                                    ->tooltip('Open map in new tab')
                                    ->url(fn($get) => $get('link_alamat_perusahaan'), true)
                                    ->visible(fn($get) => filled($get('link_alamat_perusahaan')))
                            ),

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

                Forms\Components\Section::make('Sejarah, Visi dan Misi')
                    ->schema([
                        Forms\Components\RichEditor::make('sejarah_perusahaan')
                            ->label('Sejarah Perusahaan')
                            ->disableToolbarButtons([
                                'attachFiles'
                            ])
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('visi_perusahaan')
                            ->label('Visi Perusahaan')
                            ->disableToolbarButtons([
                                'attachFiles'
                            ])
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('misi_perusahaan')
                            ->label('Misi Perusahaan')
                            ->disableToolbarButtons([
                                'attachFiles'
                            ])
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
                ,

                Tables\Columns\TextColumn::make('email_perusahaan')
                    ->label('Email')
                    ->searchable()
                ,

                Tables\Columns\TextColumn::make('alamat_perusahaan')
                    ->label('Alamat')
                    ->limit(30)
                    ->searchable(),

                Tables\Columns\TextColumn::make('link_alamat_perusahaan')
                    ->label('Link Alamat')
                    ->searchable()
                    ->limit(30)
                    ->url(fn($record) => $record->link_lokasi_event)
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-map-pin')
                    ->tooltip('Klik untuk melihat di Google Maps')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('sejarah_perusahaan')
                    ->label('Sejarah')
                    ->limit(20)
                    ->html()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('visi_perusahaan')
                    ->label('Visi')
                    ->limit(20)
                    ->html()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('misi_perusahaan')
                    ->label('Misi')
                    ->limit(20)
                    ->html()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d M Y H:i')
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

    public static function canCreate(): bool
    {
        return false;
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
