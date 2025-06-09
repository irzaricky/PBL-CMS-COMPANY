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
                            ->suffixAction(
                                Forms\Components\Actions\Action::make('open')
                                    ->icon('heroicon-o-arrow-top-right-on-square')
                                    ->tooltip('Open map in new tab')
                                    ->url(fn($get) => $get('link_alamat_perusahaan'), true)
                                    ->visible(fn($get) => filled($get('link_alamat_perusahaan')))
                            ),

                        Forms\Components\TextInput::make('map_embed_perusahaan')
                            ->label('Kode Embed Google Maps')
                            ->placeholder('Salin seluruh kode iframe dari Google Maps di sini...')
                            ->helperText('Salin seluruh kode iframe dari Google Maps. Sistem akan otomatis mengambil URL embed-nya.')
                            ->columnSpan(2)
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    // Extract src URL from iframe
                                    if (preg_match('/src=["\']([^"\']+)["\']/', $state, $matches)) {
                                        $embedUrl = $matches[1];
                                        $set('map_embed_perusahaan', $embedUrl);
                                    }
                                }
                            })
                            ->reactive(),

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
                            ->label('📅 Timeline Sejarah Perusahaan')
                            ->schema([
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('tahun')
                                            ->label('Tahun')
                                            ->numeric()
                                            ->required()
                                            ->minValue(1900)
                                            ->maxValue(date('Y') + 10)
                                            ->placeholder('2024')
                                            ->helperText('Masukkan tahun kejadian')
                                            ->prefixIcon('heroicon-o-calendar-days'),

                                        Forms\Components\TextInput::make('judul')
                                            ->label('Pencapaian/Milestone')
                                            ->required()
                                            ->maxLength(100)
                                            ->placeholder('Contoh: Pendirian Perusahaan')
                                            ->helperText('Judul pencapaian atau milestone')
                                            ->prefixIcon('heroicon-o-trophy')
                                            ->columnSpan(2),
                                    ]),

                                Forms\Components\Textarea::make('deskripsi')
                                    ->label('Deskripsi Detail')
                                    ->required()
                                    ->rows(3)
                                    ->maxLength(500)
                                    ->placeholder('Jelaskan detail pencapaian atau peristiwa penting yang terjadi pada tahun tersebut...')
                                    ->helperText('Deskripsi lengkap tentang pencapaian atau peristiwa')
                                    ->columnSpanFull(),
                            ])
                            ->columnSpanFull()
                            ->orderColumn('tahun')
                            ->reorderableWithButtons()
                            ->collapsed()
                            ->itemLabel(
                                fn(array $state): ?string =>
                                isset($state['tahun'], $state['judul'])
                                ? "{$state['tahun']} - {$state['judul']}"
                                : 'Timeline Item'
                            )
                            ->minItems(1)
                            ->maxItems(20)
                            ->addActionLabel('+ Tambah Timeline Baru')
                            ->deleteAction(
                                fn(Forms\Components\Actions\Action $action) => $action
                                    ->requiresConfirmation()
                                    ->modalHeading('Hapus Timeline')
                                    ->modalDescription('Apakah Anda yakin ingin menghapus timeline ini?')
                                    ->modalSubmitActionLabel('Ya, Hapus')
                            )
                            ->cloneable()
                            ->extraAttributes([
                                'class' => 'timeline-form-section'
                            ])
                            ->helperText('Urutkan berdasarkan tahun untuk tampilan timeline yang rapi'),

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
                    ->description('Detail informasi perusahaan')
                    ->icon('heroicon-o-building-office')
                    ->columns(3)
                    ->schema([
                        Infolists\Components\Group::make([
                            Infolists\Components\ImageEntry::make('logo_perusahaan')
                                ->label('Logo Perusahaan')
                                ->disk('public')
                                ->height(120)
                                ->width(120)
                                ->circular()
                                ->extraAttributes(['class' => 'mx-auto']),
                        ])->columnSpan(1),

                        Infolists\Components\Group::make([
                            Infolists\Components\TextEntry::make('nama_perusahaan')
                                ->label('Nama Perusahaan')
                                ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                ->weight('bold')
                                ->color('primary')
                                ->icon('heroicon-o-building-office-2'),

                            Infolists\Components\TextEntry::make('email_perusahaan')
                                ->label('Email Perusahaan')
                                ->icon('heroicon-o-envelope')
                                ->copyable()
                                ->copyMessage('Email berhasil disalin!')
                                ->url(fn($record) => 'mailto:' . $record->email_perusahaan)
                                ->color('blue')
                                ->badge(),

                            Infolists\Components\TextEntry::make('alamat_perusahaan')
                                ->label('Alamat Lengkap')
                                ->icon('heroicon-o-map-pin')
                                ->html()
                                ->formatStateUsing(fn($state) => nl2br(e($state))),

                            Infolists\Components\TextEntry::make('link_alamat_perusahaan')
                                ->label('Google Maps')
                                ->icon('heroicon-o-globe-alt')
                                ->url(fn($record) => $record->link_alamat_perusahaan)
                                ->openUrlInNewTab()
                                ->color('primary')
                                ->badge()
                                ->formatStateUsing(fn() => 'Buka di Google Maps')
                                ->visible(fn($record) => !empty($record->link_alamat_perusahaan)),
                        ])->columnSpan(2),
                    ]),

                Infolists\Components\Section::make('Galeri Perusahaan')
                    ->description('Koleksi foto dan gambar perusahaan')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        Infolists\Components\ImageEntry::make('thumbnail_perusahaan')
                            ->label('')
                            ->disk('public')
                            ->height(250)
                            ->width(400)
                            ->extraAttributes(['class' => 'rounded-xl shadow-md']),
                    ])
                    ->collapsible()
                    ->visible(fn($record) => !empty($record->thumbnail_perusahaan)),

                Infolists\Components\Section::make('Tentang Perusahaan')
                    ->description('Deskripsi lengkap mengenai perusahaan')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Infolists\Components\TextEntry::make('deskripsi_perusahaan')
                            ->label('')
                            ->html()
                            ->prose(),
                    ])
                    ->collapsible()
                    ->visible(fn($record) => !empty($record->deskripsi_perusahaan)),

                Infolists\Components\Section::make('Perjalanan Sejarah Perusahaan')
                    ->description('Timeline pencapaian dan milestone penting dalam perkembangan perusahaan')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('sejarah_perusahaan')
                            ->label('')
                            ->schema([
                                Infolists\Components\Grid::make([
                                    'default' => 1,
                                    'sm' => 1,
                                    'md' => 12,
                                ])
                                    ->schema([
                                        // Year Badge Section 
                                        Infolists\Components\Group::make([
                                            Infolists\Components\TextEntry::make('tahun')
                                                ->label('')
                                                ->formatStateUsing(fn($state) => $state)
                                                ->badge()
                                                ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                                ->weight('bold')
                                                ->color(function ($state) {
                                                    $currentYear = date('Y');
                                                    $yearDiff = $currentYear - $state;

                                                    return match (true) {
                                                        $yearDiff <= 2 => 'success',
                                                        $yearDiff <= 5 => 'info',
                                                        $yearDiff <= 10 => 'warning',
                                                        $yearDiff <= 20 => 'gray',
                                                        default => 'danger'
                                                    };
                                                })
                                                ->icon('heroicon-o-calendar-days')
                                                ->extraAttributes([
                                                    'class' => 'timeline-year-badge flex justify-center items-center text-center min-h-[60px] md:min-h-[80px] text-lg md:text-2xl font-black mb-4 md:mb-0'
                                                ])
                                                ->tooltip(function ($state) {
                                                    if (!$state || !is_numeric($state)) {
                                                        return null;
                                                    }

                                                    $currentYear = (int) date('Y');
                                                    $yearDiff = $currentYear - (int) $state;

                                                    if ($yearDiff < 1) {
                                                        return 'Tahun ini';
                                                    }

                                                    if ($yearDiff == 1) {
                                                        return 'Tahun lalu';
                                                    }

                                                    return match (true) {
                                                        // Mulai dari 2 tahun
                                                        $yearDiff <= 5 => "Baru ({$yearDiff} tahun lalu)",
                                                        $yearDiff <= 10 => "Cukup lama ({$yearDiff} tahun lalu)",
                                                        $yearDiff <= 20 => "Lama ({$yearDiff} tahun lalu)",
                                                        default => "Sangat lama ({$yearDiff} tahun lalu)"
                                                    };
                                                }),
                                        ])->columnSpan([
                                                    'default' => 1,    // Mobile: full width
                                                    'md' => 2,         // Desktop: 2/12 kolom
                                                ]),

                                        // Content Section - Responsive  
                                        Infolists\Components\Group::make([
                                            // Achievement Title
                                            Infolists\Components\TextEntry::make('judul')
                                                ->label('')
                                                ->formatStateUsing(fn($state) => "🏆 " . $state)
                                                ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                                ->weight('bold')
                                                ->color('primary')
                                                ->extraAttributes([
                                                    'class' => 'timeline-title mb-3 text-base md:text-lg'
                                                ]),

                                            // Description
                                            Infolists\Components\TextEntry::make('deskripsi')
                                                ->label('')
                                                ->prose()
                                                ->color('gray')
                                                ->extraAttributes([
                                                    'class' => 'timeline-description leading-relaxed text-sm md:text-base'
                                                ]),
                                        ])->columnSpan([
                                                    'default' => 1,    // Mobile: full width
                                                    'md' => 10,        // Desktop: 10/12 kolom
                                                ]),
                                    ])
                                    ->extraAttributes([
                                        'class' => 'timeline-item bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-xl p-4 md:p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:border-primary-300 transition-all duration-300 mb-4'
                                    ]),
                            ])
                            ->contained(false)
                            ->extraAttributes([
                                'class' => 'timeline-container space-y-4'
                            ]),
                    ])
                    ->collapsible()
                    ->visible(fn($record) => !empty($record->sejarah_perusahaan)),

                Infolists\Components\Section::make('Visi & Misi Perusahaan')
                    ->description('Pandangan masa depan dan tujuan perusahaan')
                    ->icon('heroicon-o-eye')
                    ->columns(2)
                    ->schema([
                        Infolists\Components\Group::make([
                            Infolists\Components\TextEntry::make('visi_perusahaan')
                                ->label('Visi Perusahaan')
                                ->html()
                                ->prose()
                                ->color('primary'),
                        ])->columnSpan(1),

                        Infolists\Components\Group::make([
                            Infolists\Components\TextEntry::make('misi_perusahaan')
                                ->label('Misi Perusahaan')
                                ->html()
                                ->prose()
                                ->color('success'),
                        ])->columnSpan(1),
                    ])
                    ->collapsible()
                    ->visible(fn($record) => !empty($record->visi_perusahaan) || !empty($record->misi_perusahaan)),

                Infolists\Components\Section::make('Pengaturan Tampilan')
                    ->description('Konfigurasi tema dan warna website perusahaan')
                    ->icon('heroicon-o-paint-brush')
                    ->columns(2)
                    ->schema([
                        Infolists\Components\Group::make([
                            Infolists\Components\TextEntry::make('tema_perusahaan')
                                ->label('Nama Tema')
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
                                ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                ->color(fn($state) => match ($state) {
                                    '#31487A' => 'blue',
                                    '#793354' => 'pink',
                                    '#796C2F' => 'yellow',
                                    '#1B4332' => 'green',
                                    '#3E1F47' => 'purple',
                                    default => 'gray',
                                })
                                ->icon('heroicon-o-swatch'),
                        ])->columnSpan(1),

                        Infolists\Components\Group::make([
                            Infolists\Components\ColorEntry::make('tema_perusahaan')
                                ->label('Kode Warna')
                                ->copyable()
                                ->copyMessage('Kode warna berhasil disalin!'),
                        ])->columnSpan(1),
                    ])
                    ->collapsible(),
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
