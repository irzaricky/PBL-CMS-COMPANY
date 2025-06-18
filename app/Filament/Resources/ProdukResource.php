<?php

namespace App\Filament\Resources;

use App\Enums\ContentStatus;
use App\Filament\Clusters\ProdukCluster;
use App\Filament\Resources\ProdukResource\Widgets\ProdukStats;
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
    protected static ?string $navigationIcon = 'heroicon-s-shopping-bag';
    protected static ?string $cluster = ProdukCluster::class;
    protected static ?int $navigationSort = 1;

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
                                    $baseSlug = str($state)->slug();
                                    $dateSlug = now()->format('Y-m-d');
                                    $set('slug', $baseSlug . '-' . $dateSlug);
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
                            ->native(false)
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

                        Forms\Components\Toggle::make('tampilkan_harga')
                            ->label('Tampilkan Harga')
                            ->default(true)
                            ->live() // Tambahkan live() untuk reaktivitas
                            ->helperText('Aktifkan untuk menampilkan harga produk di halaman publik'),

                        Forms\Components\TextInput::make('harga_produk')
                            ->label('Harga Produk')
                            ->numeric()
                            ->prefix('Rp')
                            ->suffix(',00')
                            ->required(fn (callable $get) => $get('tampilkan_harga')) // Conditional required
                            ->maxLength(50)
                            ->helperText('Masukkan harga produk dalam format angka tanpa titik')
                            ->placeholder('0')
                            ->visible(fn (callable $get) => $get('tampilkan_harga')), // Sembunyikan jika tampilkan_harga false

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(100)
                            ->unique(Produk::class, 'slug', ignoreRecord: true)
                            ->dehydrated()
                            ->helperText('Akan terisi otomatis berdasarkan nama produk')
                            ->validationMessages([
                                'unique' => 'Slug sudah terpakai. Silakan gunakan slug lain.',
                            ]),

                        Forms\Components\Select::make('status_produk')
                            ->label('Status Produk')
                            ->options([
                                ContentStatus::TERPUBLIKASI->value => ContentStatus::TERPUBLIKASI->label(),
                                ContentStatus::TIDAK_TERPUBLIKASI->value => ContentStatus::TIDAK_TERPUBLIKASI->label()
                            ])
                            ->default(ContentStatus::TIDAK_TERPUBLIKASI)
                            ->native(false)
                            ->required(),
                    ]),

                Forms\Components\Section::make('Media & Konten')
                    ->schema([
                        Forms\Components\FileUpload::make('thumbnail_produk')
                            ->label('Gambar Produk')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->directory('produk-thumbnails')
                            ->helperText('Upload gambar produk (format: jpg, png, webp)')
                            ->disk('public')
                            ->columnSpanFull()
                            ->imageEditor()
                            ->imageResizeMode('contain')
                            ->imageResizeTargetWidth(1280)
                            ->imageResizeTargetHeight(720)
                            ->optimize('webp'),

                        Forms\Components\TextInput::make('deskripsi_produk')
                            ->label('Deskripsi Produk')
                            ->required()
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Tautan & Informasi Tambahan')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('link_produk')
                                    ->label('Tautan Produk')
                                    ->url()
                                    ->maxLength(255)
                                    ->helperText('Masukkan tautan produk')
                                    ->required()
                                    ->columnSpan(1),
                                Forms\Components\Actions::make([
                                    Forms\Components\Actions\Action::make('openLink')
                                        ->label('Buka Tautan')
                                        ->icon('heroicon-s-arrow-top-right-on-square')
                                        ->url(fn($get) => $get('link_produk'), true)
                                        ->visible(fn($get) => filled($get('link_produk')))
                                        ->button()
                                ])
                                    ->verticallyAlignCenter()
                                    ->columnSpan(1),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('thumbnail_produk')
                    ->label('Thumbnail')
                    ->formatStateUsing(function ($record) {
                        $images = [];
                        $totalImages = 0;

                        if (is_array($record->thumbnail_produk) && !empty($record->thumbnail_produk)) {
                            $totalImages = count($record->thumbnail_produk);

                            // Ambil maksimal 3 gambar untuk stack effect
                            $imagesToShow = array_slice($record->thumbnail_produk, 0, 3);

                            foreach ($imagesToShow as $imagePath) {
                                $images[] = route('thumbnail', [
                                    'path' => base64_encode($imagePath),
                                    'w' => 80,
                                    'h' => 80,
                                    'q' => 80
                                ]);
                            }
                        }

                        return view('filament.tables.columns.image-stack-advanced', [
                            'images' => $images,
                            'total_images' => $totalImages,
                            'remaining_count' => max(0, $totalImages - 1)
                        ])->render();
                    })
                    ->html(),

                Tables\Columns\TextColumn::make('nama_produk')
                    ->label('Nama Produk')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('kategoriProduk.nama_kategori_produk')
                    ->label('Kategori')
                    ->searchable(),

                Tables\Columns\TextColumn::make('harga_produk')
                    ->label('Harga')
                    ->money('IDR'),

                Tables\Columns\SelectColumn::make('status_produk')
                    ->label('Status')
                    ->options([
                        ContentStatus::TERPUBLIKASI->value => ContentStatus::TERPUBLIKASI->label(),
                        ContentStatus::TIDAK_TERPUBLIKASI->value => ContentStatus::TIDAK_TERPUBLIKASI->label(),
                    ])
                    ->rules(['required']),

                Tables\Columns\TextColumn::make('link_produk')
                    ->label('Tautan Produk')
                    ->url(fn($record) => $record->link_produk)
                    ->openUrlInNewTab()
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),

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
                Tables\Filters\SelectFilter::make('id_kategori_produk')
                    ->label('Kategori')
                    ->relationship('kategoriProduk', 'nama_kategori_produk'),

                Tables\Filters\SelectFilter::make('status_produk')
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
                    ->modalHeading('Arsipkan Produk')
                    ->icon('heroicon-s-archive-box-arrow-down')
                    ->color('warning')
                    ->successNotificationTitle('Produk berhasil diarsipkan'),
                Tables\Actions\RestoreAction::make()
                    ->modalHeading('Pulihkan Produk')
                    ->successNotificationTitle('Produk berhasil dipulihkan'),
                Tables\Actions\ForceDeleteAction::make()
                    ->label('hapus permanen')
                    ->modalHeading('Hapus Permanen Produk')
                    ->successNotificationTitle('Produk berhasil dihapus permanen')
                    ->before(function ($record) {
                        MultipleFileHandler::deleteFiles($record, 'thumbnail_produk');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotificationTitle('Produk berhasil diarsipkan'),
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

    public static function getWidgets(): array
    {
        return [
            ProdukStats::class,
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
