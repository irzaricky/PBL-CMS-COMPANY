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
    protected static ?string $navigationGroup = 'Company Owner';
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
                            ->imageEditor(),
                        

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
                            ->collapsed(true)  // supaya lebih ringkas
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
                    ->getStateUsing(function ($record) {
                        $sejarah = $record->sejarah_perusahaan;

                        if (!is_array($sejarah) || empty($sejarah)) {
                            return '-';
                        }

                        // Gabungkan semua tahun + deskripsi (batasi panjang)
                        $texts = array_map(fn($item) => $item['tahun'] . ': ' . strip_tags($item['deskripsi']), $sejarah);
                        $combined = implode(' | ', $texts);

                        // Batasi teks panjang, misal 100 karakter
                        return strlen($combined) > 100 ? substr($combined, 0, 100) . '...' : $combined;
                    })
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
