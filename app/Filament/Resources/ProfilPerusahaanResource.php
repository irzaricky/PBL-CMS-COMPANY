<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProfilPerusahaanResource\Pages;
use App\Filament\Resources\ProfilPerusahaanResource\RelationManagers;
use App\Models\ProfilPerusahaan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Helpers\FilamentGroupingHelper;

class ProfilPerusahaanResource extends Resource
{
    protected static ?string $model = ProfilPerusahaan::class;
    protected static ?string $navigationIcon = 'heroicon-s-building-office';
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
                            ->directory('logo-perusahaan')
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
                            ->imageResizeTargetHeight(720),
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
                            ->url()
                            ->helperText('Berikan URL Google Maps untuk lokasi perusahaan')
                            ->prefixIcon('heroicon-s-map-pin')
                            ->rules([
                                function($get, $value, $fail) {
                                    if (!str_contains(strtolower($value), 'google.com/maps') && 
                                        !str_contains(strtolower($value), 'goo.gl/maps')) {
                                        $fail('URL harus berupa tautan Google Maps yang valid.');
                                    }
                                },
                            ])
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
                        Forms\Components\Repeater::make('sejarah_perusahaan')
                            ->label('Sejarah Perusahaan Per Tahun')
                            ->schema([
                                Forms\Components\TextInput::make('tahun')
                                    ->label('Tahun')
                                    ->numeric()
                                    ->required(),
                                Forms\Components\TextInput::make('judul')
                                    ->label('Judul')
                                    ->required(),
                                Forms\Components\RichEditor::make('deskripsi')
                                    ->label('Deskripsi')
                                    ->disableToolbarButtons(['attachFiles'])
                                    ->required(),
                            ])
                            ->columnSpanFull()
                            ->orderColumn()
                            ->collapsed(true)
                            ->minItems(1)
                            ->addActionLabel('Tambah Tahun Baru'),

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
                Forms\Components\Section::make('Tampilan')
                    ->description('Pilih tema warna untuk tampilan website')
                    ->schema([
                        Forms\Components\Select::make('tema_perusahaan')
                            ->label('Tema Perusahaan')
                            ->helperText('Perlu refresh untuk mengambil perubahan')
                            ->options([
                                '#31487A' => 'YlnMn Blue',
                                '#793354' => 'Quinacridone Magenta',
                                '#796C2F' => 'Field Drab',
                                '#1B4332' => 'Brunswick Green',
                                '#3E1F47' => 'Purple Taupe',
                            ])
                            ->default('#31487A')
                            ->required()
                            ->reactive()
                            ->native(false)
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('tema_perusahaan', $state);
                            }),
                    ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Utama')
                    ->schema([
                        Infolists\Components\TextEntry::make('nama_perusahaan')
                            ->label('Nama Perusahaan')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold')
                            ->color('primary'),

                        Infolists\Components\TextEntry::make('email_perusahaan')
                            ->label('Email')
                            ->icon('heroicon-o-envelope')
                            ->copyable()
                            ->copyMessage('Email disalin!')
                            ->url(fn($record) => 'mailto:' . $record->email_perusahaan),

                        Infolists\Components\TextEntry::make('alamat_perusahaan')
                            ->label('Alamat')
                            ->icon('heroicon-o-map-pin'),

                        Infolists\Components\TextEntry::make('link_alamat_perusahaan')
                            ->label('Lokasi di Google Maps')
                            ->icon('heroicon-o-globe-alt')
                            ->url(fn($record) => $record->link_alamat_perusahaan)
                            ->openUrlInNewTab()
                            ->color('primary')
                            ->formatStateUsing(fn() => 'Lihat di Google Maps'),

                        Infolists\Components\ImageEntry::make('logo_perusahaan')
                            ->label('Logo Perusahaan')
                            ->disk('public')
                            ->height(150)
                            ->width(150),
                    ]),

                Infolists\Components\Section::make('Galeri Perusahaan')
                    ->schema([
                        Infolists\Components\ImageEntry::make('thumbnail_perusahaan')
                            ->label('')
                            ->disk('public')
                            ->height(200)
                            ->width(300)
                            ->extraAttributes(['class' => 'rounded-lg']),
                    ])
                    ->visible(fn($record) => !empty($record->thumbnail_perusahaan)),

                Infolists\Components\Section::make('Deskripsi Perusahaan')
                    ->schema([
                        Infolists\Components\TextEntry::make('deskripsi_perusahaan')
                            ->label('')
                            ->html(),
                    ])
                    ->visible(fn($record) => !empty($record->deskripsi_perusahaan)),

                Infolists\Components\Section::make('Sejarah Perusahaan')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('sejarah_perusahaan')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('tahun')
                                    ->label('Tahun')
                                    ->badge()
                                    ->color('primary')
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large),

                                Infolists\Components\TextEntry::make('judul')
                                    ->label('')
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Medium)
                                    ->weight('bold')
                                    ->color('gray'),

                                Infolists\Components\TextEntry::make('deskripsi')
                                    ->label('')
                                    ->html(),
                            ]),
                    ])
                    ->visible(fn($record) => !empty($record->sejarah_perusahaan)),

                Infolists\Components\Section::make('Visi & Misi')
                    ->schema([
                        Infolists\Components\TextEntry::make('visi_perusahaan')
                            ->label('Visi Perusahaan')
                            ->html(),

                        Infolists\Components\TextEntry::make('misi_perusahaan')
                            ->label('Misi Perusahaan')
                            ->html(),
                    ])
                    ->visible(fn($record) => !empty($record->visi_perusahaan) || !empty($record->misi_perusahaan)),

                Infolists\Components\Section::make('Pengaturan Tema')
                    ->schema([
                        Infolists\Components\TextEntry::make('tema_perusahaan')
                            ->label('Tema Warna')
                            ->formatStateUsing(function ($state) {
                                $themes = [
                                    '#31487A' => 'YlnMn Blue',
                                    '#793354' => 'Quinacridone Magenta',
                                    '#796C2F' => 'Field Drab',
                                    '#1B4332' => 'Brunswick Green',
                                    '#3E1F47' => 'Purple Taupe',
                                ];
                                return $themes[$state] ?? $state;
                            })
                            ->badge()
                            ->color(fn($state) => match ($state) {
                                '#31487A' => 'blue',
                                '#793354' => 'pink',
                                '#796C2F' => 'yellow',
                                '#1B4332' => 'green',
                                '#3E1F47' => 'purple',
                                default => 'gray',
                            }),

                        Infolists\Components\ColorEntry::make('tema_perusahaan')
                            ->label('Preview Warna'),
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
                    ->searchable(),

                Tables\Columns\TextColumn::make('email_perusahaan')
                    ->label('Email')
                    ->searchable(),

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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProfilPerusahaans::route('/'),
            'view' => Pages\ViewProfilPerusahaan::route('/{record}'),
            'edit' => Pages\EditProfilPerusahaan::route('/{record}/edit'),
        ];
    }
    public static function getUrl(string $name = 'index', array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?\Illuminate\Database\Eloquent\Model $tenant = null): string
    {
        // Jika mengakses index, redirect langsung ke view dengan record pertama
        if ($name === 'index') {
            $firstRecord = static::getModel()::first();
            if ($firstRecord) {
                return static::getUrl('view', ['record' => $firstRecord->getKey()], $isAbsolute, $panel, $tenant);
            }
        }

        return parent::getUrl($name, $parameters, $isAbsolute, $panel, $tenant);
    }
}
