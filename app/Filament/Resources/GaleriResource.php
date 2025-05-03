<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Galeri;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\GaleriResource\Pages;
use App\Services\FileHandlers\MultipleFileHandler;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Resources\GaleriResource\RelationManagers;

class GaleriResource extends Resource
{
    protected static ?string $model = Galeri::class;
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationIcon = 'heroicon-s-photo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Galeri')
                    ->schema([
                        Forms\Components\TextInput::make('judul_galeri')
                            ->label('Judul Galeri')
                            ->required()
                            ->maxLength(200)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (!empty($state)) {
                                    $set('slug', str($state)->slug());
                                } else {
                                    $set('slug', null);
                                }
                            }),

                        Forms\Components\Select::make('id_kategori_galeri')
                            ->label('Kategori Galeri')
                            ->relationship('kategoriGaleri', 'nama_kategori_galeri')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('nama_kategori_galeri')
                                    ->label('Nama Kategori')
                                    ->required()
                                    ->maxLength(50),
                                Forms\Components\Textarea::make('deskripsi')
                                    ->label('Deskripsi')
                                    ->maxLength(200),
                            ])
                            ->editOptionForm([
                                Forms\Components\TextInput::make('nama_kategori_galeri')
                                    ->label('Nama Kategori')
                                    ->required()
                                    ->maxLength(50),
                                Forms\Components\Textarea::make('deskripsi')
                                    ->label('Deskripsi')
                                    ->maxLength(200),
                            ])
                            ->manageOptionForm([
                                Forms\Components\TextInput::make('nama_kategori_galeri')
                                    ->label('Nama Kategori')
                                    ->required()
                                    ->maxLength(50),
                                Forms\Components\Textarea::make('deskripsi')
                                    ->label('Deskripsi')
                                    ->maxLength(200),
                            ]),


                        Forms\Components\Select::make('id_user')
                            ->label('Pengunggah')
                            ->relationship('user', 'name')
                            ->default(fn() => Auth::id())
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
                        Forms\Components\FileUpload::make('thumbnail_galeri')
                            ->label('Gambar Galeri')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->directory('galeri-images')
                            ->maxFiles(10)
                            ->helperText('Upload hingga 10 gambar (format: jpg, png, webp)')
                            ->disk('public')
                            ->columnSpanFull()
                            ->optimize('webp'),

                        Forms\Components\RichEditor::make('deskripsi_galeri')
                            ->label('Deskripsi Galeri')
                            ->required()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('galeri-attachments')
                            ->columnSpanFull(),
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail_galeri')
                    ->label('Gambar')
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText()
                    ->extraImgAttributes(['class' => 'object-cover']),

                Tables\Columns\TextColumn::make('judul_galeri')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('kategoriGaleri.nama_kategori_galeri')
                    ->label('Kategori')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengunggah')
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

                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Dihapus Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('id_kategori_galeri')
                    ->label('Kategori')
                    ->relationship('kategoriGaleri', 'nama_kategori_galeri'),

                Tables\Filters\SelectFilter::make('id_user')
                    ->label('Pengunggah')
                    ->relationship('user', 'name'),

                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotificationTitle('Galeri berhasil diarsipkan'),
                Tables\Actions\RestoreAction::make()
                    ->successNotificationTitle('Galeri berhasil dipulihkan'),
                Tables\Actions\ForceDeleteAction::make()
                    ->successNotificationTitle('Galeri berhasil dihapus permanen')
                    ->before(function ($record) {
                        MultipleFileHandler::deleteFiles($record, 'thumbnail_galeri');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotificationTitle('Galeri berhasil diarsipkan')
                        ->before(function (Collection $records) {
                            MultipleFileHandler::deleteBulkFiles($records, 'thumbnail_galeri');
                        }),
                    RestoreBulkAction::make()
                        ->successNotificationTitle('Galeri berhasil dipulihkan'),
                    ForceDeleteBulkAction::make()
                        ->successNotificationTitle('Galeri berhasil dihapus permanen')
                        ->before(function (Collection $records) {
                            MultipleFileHandler::deleteBulkFiles($records, 'thumbnail_galeri');
                        }),
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
            'index' => Pages\ListGaleris::route('/'),
            'create' => Pages\CreateGaleri::route('/create'),
            // ada tambahan validasi pada edit event
            'edit' => Pages\EditGaleri::route('/{record}/edit'),
        ];
    }
}
