<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArtikelResource\Pages;
use App\Filament\Resources\ArtikelResource\RelationManagers;
use App\Models\Artikel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;

class ArtikelResource extends Resource
{
    protected static ?string $model = Artikel::class;
    protected static ?string $navigationGroup = 'Content Management';

    protected static ?string $navigationIcon = 'heroicon-s-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Artikel')
                    ->schema([
                        Forms\Components\TextInput::make('judul_artikel')
                            ->label('Judul Artikel')
                            ->required()
                            ->maxLength(100)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (!empty($state)) {
                                    $set('slug', str($state)->slug());
                                } else {
                                    $set('slug', null);
                                }
                            }),

                        Forms\Components\Select::make('id_kategori_artikel')
                            ->label('Kategori Artikel')
                            ->relationship('kategoriArtikel', 'nama_kategori_artikel')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('nama_kategori_artikel')
                                    ->label('Nama Kategori')
                                    ->required()
                                    ->maxLength(50),
                                Forms\Components\Textarea::make('deskripsi')
                                    ->label('Deskripsi')
                                    ->maxLength(200),
                            ])
                            ->editOptionForm([
                                Forms\Components\TextInput::make('nama_kategori_artikel')
                                    ->label('Nama Kategori')
                                    ->required()
                                    ->maxLength(50),
                                Forms\Components\Textarea::make('deskripsi')
                                    ->label('Deskripsi')
                                    ->maxLength(200),
                            ])
                            ->manageOptionForm([
                                Forms\Components\TextInput::make('nama_kategori_artikel')
                                    ->label('Nama Kategori')
                                    ->required()
                                    ->maxLength(50),
                                Forms\Components\Textarea::make('deskripsi')
                                    ->label('Deskripsi')
                                    ->maxLength(200),
                            ]),

                        Forms\Components\Select::make('id_user')
                            ->label('Penulis')
                            ->relationship('user', 'name')
                            ->default(fn() => auth()->id())
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true)
                            ->dehydrated()
                            ->helperText('Akan terisi otomatis berdasarkan judul'),
                    ]),

                Forms\Components\Section::make('Media & Konten')
                    ->schema([
                        Forms\Components\FileUpload::make('thumbnail_artikel')
                            ->label('Galeri Gambar Artikel')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->directory('artikel-thumbnails')
                            ->maxFiles(5)
                            ->helperText('Upload hingga 5 gambar untuk artikel (format: jpg, png, webp)')
                            ->disk('public')
                            ->columnSpanFull()
                            ->optimize('webp'),

                        Forms\Components\RichEditor::make('konten_artikel')
                            ->label('Konten Artikel')
                            ->required()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('artikel-attachments')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail_artikel')
                    ->label('Thumbnail')
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText()
                    ->extraImgAttributes(['class' => 'object-cover']),

                Tables\Columns\TextColumn::make('judul_artikel')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('kategoriArtikel.nama_kategori_artikel')
                    ->label('Kategori')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Penulis')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('id_kategori_artikel')
                    ->label('Kategori')
                    ->relationship('kategoriArtikel', 'nama_kategori_artikel'),

                Tables\Filters\SelectFilter::make('id_user')
                    ->label('Penulis')
                    ->relationship('user', 'name'),
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
            'index' => Pages\ListArtikels::route('/'),
            'create' => Pages\CreateArtikel::route('/create'),
            'edit' => Pages\EditArtikel::route('/{record}/edit'),
        ];
    }
}
