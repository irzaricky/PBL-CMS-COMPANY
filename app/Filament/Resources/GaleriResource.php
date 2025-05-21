<?php

namespace App\Filament\Resources;

use App\Enums\ContentStatus;
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
use App\Filament\Resources\GaleriResource\Widgets\GaleriStats;

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

                        Forms\Components\Select::make('status_galeri')
                            ->label('Status Galeri')
                            ->options([
                                ContentStatus::TERPUBLIKASI->value => ContentStatus::TERPUBLIKASI->label(),
                                ContentStatus::TIDAK_TERPUBLIKASI->value => ContentStatus::TIDAK_TERPUBLIKASI->label()
                            ])
                            ->default(ContentStatus::TIDAK_TERPUBLIKASI)
                            ->required(),
                    ]),

                Forms\Components\Section::make('Media & Konten')
                    ->schema([
                        Forms\Components\FileUpload::make('thumbnail_galeri')
                            ->label('Gambar Galeri')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->directory('galeri-thumbnails')
                            ->maxFiles(10)
                            ->helperText('Upload hingga 10 gambar (format: jpg, png, webp)')
                            ->disk('public')
                            ->columnSpanFull()
                            ->imageEditor()
                            ->imageResizeMode('cover')
                            ->imageResizeTargetWidth(width: 1280)
                            ->imageResizeTargetHeight(720)
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
                    ->limit(1)
                    ->limitedRemainingText()
                    ->extraImgAttributes(['class' => 'object-cover']),

                Tables\Columns\TextColumn::make('judul_galeri')
                    ->label('Judul')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('kategoriGaleri.nama_kategori_galeri')
                    ->label('Kategori')
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengunggah')
                    ->searchable(),

                Tables\Columns\TextColumn::make('jumlah_unduhan')
                    ->label('Jumlah Unduhan')
                    ->numeric()
                    ->alignCenter()
                    ->badge(),

                Tables\Columns\SelectColumn::make('status_galeri')
                    ->label('Status')
                    ->options([
                        ContentStatus::TERPUBLIKASI->value => ContentStatus::TERPUBLIKASI->label(),
                        ContentStatus::TIDAK_TERPUBLIKASI->value => ContentStatus::TIDAK_TERPUBLIKASI->label(),
                    ])
                    ->rules(['required']),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Dihapus Pada')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('id_kategori_galeri')
                    ->label('Kategori')
                    ->relationship('kategoriGaleri', 'nama_kategori_galeri'),

                Tables\Filters\SelectFilter::make('id_user')
                    ->label('Pengunggah')
                    ->relationship('user', 'name'),

                Tables\Filters\SelectFilter::make('status_galeri')
                    ->label('Status')
                    ->options([
                        ContentStatus::TERPUBLIKASI->value => ContentStatus::TERPUBLIKASI->label(),
                        ContentStatus::TIDAK_TERPUBLIKASI->value => ContentStatus::TIDAK_TERPUBLIKASI->label(),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('Arsipkan')
                    ->icon('heroicon-s-archive-box-arrow-down')
                    ->color('warning')
                    ->successNotificationTitle('Galeri berhasil diarsipkan'),
                Tables\Actions\RestoreAction::make()
                    ->successNotificationTitle('Galeri berhasil dipulihkan'),
                Tables\Actions\ForceDeleteAction::make()
                    ->label('hapus permanen')
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

    public static function getWidgets(): array
    {
        return [
            GaleriStats::class,
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
