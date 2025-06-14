<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Infolists;
use Filament\Forms\Form;
use App\Models\Testimoni;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Enums\ContentStatus;
use App\Models\TestimoniEvent;
use App\Models\TestimoniProduk;
use App\Models\TestimoniArtikel;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use App\Models\TestimoniLowongan;
use App\Helpers\FilamentGroupingHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Services\FileHandlers\MultipleFileHandler;
use App\Filament\Resources\TestimoniSliderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TestimoniSliderResource\RelationManagers;

class TestimoniSliderResource extends Resource
{
    protected static ?string $model = Testimoni::class;
    protected static ?string $navigationIcon = 'heroicon-s-chat-bubble-bottom-center-text';
    protected static ?string $navigationLabel = 'Testimoni Slider';

    public static function getNavigationGroup(): ?string
    {
        return FilamentGroupingHelper::getNavigationGroup('Customer Service');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Pilih Testimoni')
                    ->schema([
                        Forms\Components\Select::make('id_testimoni_produk')
                            ->label('Testimoni Produk')
                            ->relationship('testimoniProduk', 'isi_testimoni')
                            ->getOptionLabelFromRecordUsing(
                                fn($record) => ($record->user ? $record->user->name . ' - ' : '') .
                                Str::limit($record->isi_testimoni, 50)
                            )
                            ->searchable(['isi_testimoni', 'user.name'])
                            ->preload()
                            ->native(false),

                        Forms\Components\Select::make('id_testimoni_lowongan')
                            ->label('Testimoni Lowongan')
                            ->relationship('testimoniLowongan', 'isi_testimoni')
                            ->getOptionLabelFromRecordUsing(
                                fn($record) => ($record->user ? $record->user->name . ' - ' : '') .
                                Str::limit($record->isi_testimoni, 50)
                            )
                            ->searchable(['isi_testimoni', 'user.name'])
                            ->preload()
                            ->native(false),

                        Forms\Components\Select::make('id_testimoni_event')
                            ->label('Testimoni Event')
                            ->relationship('testimoniEvent', 'isi_testimoni')
                            ->getOptionLabelFromRecordUsing(
                                fn($record) => ($record->user ? $record->user->name . ' - ' : '') .
                                Str::limit($record->isi_testimoni, 50)
                            )
                            ->searchable(['isi_testimoni', 'user.name'])
                            ->preload()
                            ->native(false),

                        Forms\Components\Select::make('id_testimoni_artikel')
                            ->label('Testimoni Artikel')
                            ->relationship('testimoniArtikel', 'isi_testimoni')
                            ->getOptionLabelFromRecordUsing(
                                fn($record) => ($record->user ? $record->user->name . ' - ' : '') .
                                Str::limit($record->isi_testimoni, 50)
                            )
                            ->searchable(['isi_testimoni', 'user.name'])
                            ->preload()
                            ->native(false),
                    ])
                    ->description('Pilih salah satu testimoni dari kategori yang tersedia'),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(1)
            ->schema([
                Infolists\Components\Section::make('Testimoni Slider')
                    ->description('Testimoni yang akan ditampilkan di homepage')
                    ->icon('heroicon-s-chat-bubble-bottom-center-text')
                    ->compact()
                    ->columns(1)
                    ->schema([
                        // Detail Testimoni Produk
                        Infolists\Components\Card::make()
                            ->schema([
                                Infolists\Components\Grid::make([
                                    'sm' => 1,
                                    'md' => 3,
                                ])
                                    ->schema([
                                        Infolists\Components\ImageEntry::make('testimonial_photo')
                                            ->label('')
                                            ->getStateUsing(function (Testimoni $record): ?string {
                                                return $record->testimoniProduk && $record->testimoniProduk->user && $record->testimoniProduk->user->foto_profil
                                                    ? $record->testimoniProduk->user->foto_profil
                                                    : null;
                                            })
                                            ->circular()
                                            ->size(120)
                                            ->disk('public')
                                            ->columnSpan(1),

                                        Infolists\Components\Grid::make(1)
                                            ->schema([
                                                Infolists\Components\TextEntry::make('testimonial_title')
                                                    ->label('Testimoni Produk')
                                                    ->getStateUsing(function (Testimoni $record): string {
                                                        return $record->testimoniProduk && $record->testimoniProduk->user
                                                            ? $record->testimoniProduk->user->name
                                                            : 'User Anonim';
                                                    })
                                                    ->weight('bold')
                                                    ->size('lg')
                                                    ->color('primary'),

                                                Infolists\Components\TextEntry::make('testimonial_content')
                                                    ->label('Isi Testimoni')
                                                    ->getStateUsing(function (Testimoni $record): string {
                                                        return $record->testimoniProduk ? ($record->testimoniProduk->isi_testimoni ?? 'Tidak ada konten') : 'Tidak ada konten';
                                                    })
                                                    ->html()
                                                    ->prose(),

                                                Infolists\Components\TextEntry::make('testimonial_rating')
                                                    ->label('Rating')
                                                    ->getStateUsing(function (Testimoni $record): string {
                                                        if ($record->testimoniProduk && isset($record->testimoniProduk->rating)) {
                                                            return str_repeat('⭐', $record->testimoniProduk->rating) . " ({$record->testimoniProduk->rating}/5)";
                                                        }
                                                        return 'Tidak ada rating';
                                                    })
                                                    ->visible(fn(Testimoni $record): bool => $record->testimoniProduk && isset($record->testimoniProduk->rating)),

                                                Infolists\Components\Grid::make(2)
                                                    ->schema([
                                                        Infolists\Components\TextEntry::make('testimoniProduk.produk.nama_produk')
                                                            ->label('Nama Produk')
                                                            ->icon('heroicon-o-shopping-bag')
                                                            ->badge()
                                                            ->color('success'),

                                                        Infolists\Components\TextEntry::make('testimoniProduk.produk.harga_produk')
                                                            ->label('Harga Produk')
                                                            ->money('IDR')
                                                            ->icon('heroicon-o-ticket')
                                                            ->badge()
                                                            ->color('warning'),
                                                    ]),
                                            ])
                                            ->columnSpan(2),
                                    ]),
                            ])
                            ->visible(fn(Testimoni $record): bool => !is_null($record->id_testimoni_produk)),

                        // Detail Testimoni Lowongan
                        Infolists\Components\Card::make()
                            ->schema([
                                Infolists\Components\Grid::make([
                                    'sm' => 1,
                                    'md' => 3,
                                ])
                                    ->schema([
                                        Infolists\Components\ImageEntry::make('testimonial_photo')
                                            ->label('')
                                            ->getStateUsing(function (Testimoni $record): ?string {
                                                return $record->testimoniLowongan && $record->testimoniLowongan->user && $record->testimoniLowongan->user->foto_profil
                                                    ? $record->testimoniLowongan->user->foto_profil
                                                    : null;
                                            })
                                            ->circular()
                                            ->size(120)
                                            ->disk('public')
                                            ->columnSpan(1),

                                        Infolists\Components\Grid::make(1)
                                            ->schema([
                                                Infolists\Components\TextEntry::make('testimonial_title')
                                                    ->label('Testimoni Lowongan')
                                                    ->getStateUsing(function (Testimoni $record): string {
                                                        return $record->testimoniLowongan && $record->testimoniLowongan->user
                                                            ? $record->testimoniLowongan->user->name
                                                            : 'User Anonim';
                                                    })
                                                    ->weight('bold')
                                                    ->size('lg')
                                                    ->color('info'),

                                                Infolists\Components\TextEntry::make('testimonial_content')
                                                    ->label('Isi Testimoni')
                                                    ->getStateUsing(function (Testimoni $record): string {
                                                        return $record->testimoniLowongan ? ($record->testimoniLowongan->isi_testimoni ?? 'Tidak ada konten') : 'Tidak ada konten';
                                                    })
                                                    ->html()
                                                    ->prose(),

                                                Infolists\Components\TextEntry::make('testimonial_rating')
                                                    ->label('Rating')
                                                    ->getStateUsing(function (Testimoni $record): string {
                                                        if ($record->testimoniLowongan && isset($record->testimoniLowongan->rating)) {
                                                            return str_repeat('⭐', $record->testimoniLowongan->rating) . " ({$record->testimoniLowongan->rating}/5)";
                                                        }
                                                        return 'Tidak ada rating';
                                                    })
                                                    ->visible(fn(Testimoni $record): bool => $record->testimoniLowongan && isset($record->testimoniLowongan->rating)),

                                                Infolists\Components\Grid::make(2)
                                                    ->schema([
                                                        Infolists\Components\TextEntry::make('testimoniLowongan.lowongan.judul_lowongan')
                                                            ->label('Posisi Lowongan')
                                                            ->icon('heroicon-o-briefcase')
                                                            ->badge()
                                                            ->color('info'),
                                                        Infolists\Components\TextEntry::make('testimoniLowongan.lowongan.jenis_lowongan')
                                                            ->label('Jenis Lowongan')
                                                            ->icon('heroicon-o-tag')
                                                            ->badge()
                                                            ->color('gray'),
                                                    ]),
                                            ])
                                            ->columnSpan(2),
                                    ]),
                            ])
                            ->visible(fn(Testimoni $record): bool => !is_null($record->id_testimoni_lowongan)),

                        // Detail Testimoni Event
                        Infolists\Components\Card::make()
                            ->schema([
                                Infolists\Components\Grid::make([
                                    'sm' => 1,
                                    'md' => 3,
                                ])
                                    ->schema([
                                        Infolists\Components\ImageEntry::make('testimonial_photo')
                                            ->label('')
                                            ->getStateUsing(function (Testimoni $record): ?string {
                                                return $record->testimoniEvent && $record->testimoniEvent->user && $record->testimoniEvent->user->foto_profil
                                                    ? $record->testimoniEvent->user->foto_profil
                                                    : null;
                                            })
                                            ->circular()
                                            ->size(120)
                                            ->disk('public')
                                            ->columnSpan(1),

                                        Infolists\Components\Grid::make(1)
                                            ->schema([
                                                Infolists\Components\TextEntry::make('testimonial_title')
                                                    ->label('Testimoni Event')
                                                    ->getStateUsing(function (Testimoni $record): string {
                                                        return $record->testimoniEvent && $record->testimoniEvent->user
                                                            ? $record->testimoniEvent->user->name
                                                            : 'User Anonim';
                                                    })
                                                    ->weight('bold')
                                                    ->size('lg')
                                                    ->color('warning'),

                                                Infolists\Components\TextEntry::make('testimonial_content')
                                                    ->label('Isi Testimoni')
                                                    ->getStateUsing(function (Testimoni $record): string {
                                                        return $record->testimoniEvent ? ($record->testimoniEvent->isi_testimoni ?? 'Tidak ada konten') : 'Tidak ada konten';
                                                    })
                                                    ->html()
                                                    ->prose(),

                                                Infolists\Components\TextEntry::make('testimonial_rating')
                                                    ->label('Rating')
                                                    ->getStateUsing(function (Testimoni $record): string {
                                                        if ($record->testimoniEvent && isset($record->testimoniEvent->rating)) {
                                                            return str_repeat('⭐', $record->testimoniEvent->rating) . " ({$record->testimoniEvent->rating}/5)";
                                                        }
                                                        return 'Tidak ada rating';
                                                    })
                                                    ->visible(fn(Testimoni $record): bool => $record->testimoniEvent && isset($record->testimoniEvent->rating)),

                                                Infolists\Components\TextEntry::make('testimoniEvent.event.nama_event')
                                                    ->label('Nama Event')
                                                    ->icon('heroicon-o-calendar-days')
                                                    ->badge()
                                                    ->color('warning'),
                                            ])
                                            ->columnSpan(2),
                                    ]),
                            ])
                            ->visible(fn(Testimoni $record): bool => !is_null($record->id_testimoni_event)),

                        // Detail Testimoni Artikel
                        Infolists\Components\Card::make()
                            ->schema([
                                Infolists\Components\Grid::make([
                                    'sm' => 1,
                                    'md' => 3,
                                ])
                                    ->schema([
                                        Infolists\Components\ImageEntry::make('testimonial_photo')
                                            ->label('')
                                            ->getStateUsing(function (Testimoni $record): ?string {
                                                return $record->testimoniArtikel && $record->testimoniArtikel->user && $record->testimoniArtikel->user->foto_profil
                                                    ? $record->testimoniArtikel->user->foto_profil
                                                    : null;
                                            })
                                            ->circular()
                                            ->size(120)
                                            ->disk('public')
                                            ->columnSpan(1),

                                        Infolists\Components\Grid::make(1)
                                            ->schema([
                                                Infolists\Components\TextEntry::make('testimonial_title')
                                                    ->label('Testimoni Artikel')
                                                    ->getStateUsing(function (Testimoni $record): string {
                                                        return $record->testimoniArtikel && $record->testimoniArtikel->user
                                                            ? $record->testimoniArtikel->user->name
                                                            : 'User Anonim';
                                                    })
                                                    ->weight('bold')
                                                    ->size('lg')
                                                    ->color('primary'),

                                                Infolists\Components\TextEntry::make('testimonial_content')
                                                    ->label('Isi Testimoni')
                                                    ->getStateUsing(function (Testimoni $record): string {
                                                        return $record->testimoniArtikel ? ($record->testimoniArtikel->isi_testimoni ?? 'Tidak ada konten') : 'Tidak ada konten';
                                                    })
                                                    ->html()
                                                    ->prose(),

                                                Infolists\Components\TextEntry::make('testimonial_rating')
                                                    ->label('Rating')
                                                    ->getStateUsing(function (Testimoni $record): string {
                                                        if ($record->testimoniArtikel && isset($record->testimoniArtikel->rating)) {
                                                            return str_repeat('⭐', $record->testimoniArtikel->rating) . " ({$record->testimoniArtikel->rating}/5)";
                                                        }
                                                        return 'Tidak ada rating';
                                                    })
                                                    ->visible(fn(Testimoni $record): bool => $record->testimoniArtikel && isset($record->testimoniArtikel->rating)),

                                                Infolists\Components\TextEntry::make('testimoniArtikel.artikel.judul_artikel')
                                                    ->label('Judul Artikel')
                                                    ->icon('heroicon-o-document-text')
                                                    ->badge()
                                                    ->color('primary'),
                                            ])
                                            ->columnSpan(2),
                                    ]),
                            ])
                            ->visible(fn(Testimoni $record): bool => !is_null($record->id_testimoni_artikel)),
                    ])
                    ->columns(1),

                Infolists\Components\Section::make('Informasi Waktu')
                    ->schema([
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Dibuat Pada')
                            ->dateTime('d M Y H:i'),

                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Terakhir Diperbarui')
                            ->dateTime('d M Y H:i'),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tidak ada kolom karena kita tidak menggunakan table view
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
    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ViewTestimoni::route('/{record?}'),
            'edit' => Pages\EditTestimoni::route('/{record}/edit'),
        ];
    }
}
