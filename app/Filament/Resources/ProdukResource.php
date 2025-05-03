<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdukResource\Pages;
use App\Filament\Resources\ProdukResource\RelationManagers;
use App\Models\Produk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;
use App\Services\FileHandlers\MultipleFileHandler;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Filters\TrashedFilter;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationIcon = 'heroicon-s-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Produk')
                    ->schema([
                        Forms\Components\TextInput::make('nama_produk')
                            ->label('Nama Produk')
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

                        Forms\Components\Select::make('id_kategori_produk')
                            ->label('Kategori Produk')
                            ->relationship('kategoriProduk', 'nama_kategori_produk')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('nama_kategori_produk')
                                    ->label('Nama Kategori')
                                    ->required()
                                    ->maxLength(50),
                                Forms\Components\Textarea::make('deskripsi')
                                    ->label('Deskripsi')
                                    ->maxLength(200),
                            ])
                            ->editOptionForm([
                                Forms\Components\TextInput::make('nama_kategori_produk')
                                    ->label('Nama Kategori')
                                    ->required()
                                    ->maxLength(50),
                                Forms\Components\Textarea::make('deskripsi')
                                    ->label('Deskripsi')
                                    ->maxLength(200),
                            ])
                            ->manageOptionForm([
                                Forms\Components\TextInput::make('nama_kategori_produk')
                                    ->label('Nama Kategori')
                                    ->required()
                                    ->maxLength(50),
                                Forms\Components\Textarea::make('deskripsi')
                                    ->label('Deskripsi')
                                    ->maxLength(200),
                            ]),

                        Forms\Components\TextInput::make('harga_produk')
                            ->label('Harga Produk')
                            ->required()
                            ->maxLength(50)
                            ->placeholder('0'),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true)
                            ->dehydrated()
                            ->helperText('Akan terisi otomatis berdasarkan nama produk'),
                    ]),

                Forms\Components\Section::make('Media & Konten')
                    ->schema([
                        Forms\Components\FileUpload::make('thumbnail_produk')
                            ->label('Gambar Produk')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1')
                            ->directory('produk-images')
                            ->helperText('Upload gambar produk (format: jpg, png, webp)')
                            ->disk('public')
                            ->columnSpanFull()
                            ->optimize('webp'),

                        Forms\Components\RichEditor::make('deskripsi_produk')
                            ->label('Deskripsi Produk')
                            ->required()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('produk-attachments')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail_produk')
                    ->label('Gambar')
                    ->disk('public')
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText()
                    ->extraImgAttributes(['class' => 'object-cover']),

                Tables\Columns\TextColumn::make('nama_produk')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('kategoriProduk.nama_kategori_produk')
                    ->label('Kategori')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('harga_produk')
                    ->label('Harga')
                    ->sortable()
                    ->money('IDR'),

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

                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Dihapus Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('id_kategori_produk')
                    ->label('Kategori')
                    ->relationship('kategoriProduk', 'nama_kategori_produk'),

                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotificationTitle('Produk berhasil diarsipkan'),
                Tables\Actions\RestoreAction::make()
                    ->successNotificationTitle('Produk berhasil dipulihkan'),
                Tables\Actions\ForceDeleteAction::make()
                    ->successNotificationTitle('Produk berhasil dihapus permanen')
                    ->before(function ($record) {
                        MultipleFileHandler::deleteFiles($record, 'thumbnail_produk');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotificationTitle('Produk berhasil diarsipkan')
                        ->before(function (Collection $records) {
                            MultipleFileHandler::deleteBulkFiles($records, 'thumbnail_produk');
                        }),
                    RestoreBulkAction::make()
                        ->successNotificationTitle('Produk berhasil dipulihkan'),
                    ForceDeleteBulkAction::make()
                        ->successNotificationTitle('Produk berhasil dihapus permanen')
                        ->before(function (Collection $records) {
                            MultipleFileHandler::deleteBulkFiles($records, 'thumbnail_produk');
                        }),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
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
            'index' => Pages\ListProduks::route('/'),
            'create' => Pages\CreateProduk::route('/create'),
            'edit' => Pages\EditProduk::route('/{record}/edit'),
        ];
    }
}