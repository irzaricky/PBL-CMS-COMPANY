<?php

namespace App\Filament\Resources;

use App\Enums\ContentStatus;
use App\Filament\Resources\TestimoniResource\Pages;
use App\Filament\Resources\TestimoniResource\RelationManagers;
use App\Models\Testimoni;
use App\Models\TestimoniProduk;
use App\Models\TestimoniLowongan;
use App\Models\TestimoniEvent;
use App\Models\TestimoniArtikel;
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
use App\Helpers\FilamentGroupingHelper;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Support\Str;

class TestimoniResource extends Resource
{
    protected static ?string $model = Testimoni::class;
    protected static ?string $navigationIcon = 'heroicon-s-chat-bubble-bottom-center-text';

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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('testimonial_type')
                    ->label('Jenis Testimoni')
                    ->getStateUsing(fn(Testimoni $record): string => $record->getTestimonialType())
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Produk' => 'success',
                        'Lowongan' => 'info',
                        'Event' => 'warning',
                        'Artikel' => 'primary',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('testimonial_content')
                    ->label('Isi Testimoni')
                    ->getStateUsing(function (Testimoni $record): string {
                        $data = $record->getTestimonialData();
                        return $data ? (Str::limit($data->isi_testimoni ?? 'Tidak ada konten', 50)) : 'Tidak ada konten';
                    })
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
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListTestimonis::route('/'),
            'create' => Pages\CreateTestimoni::route('/create'),
            'view' => Pages\ViewTestimoni::route('/{record}'),
            'edit' => Pages\EditTestimoni::route('/{record}/edit'),
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Detail Testimoni Produk
                Infolists\Components\Section::make('Detail Testimoni Produk')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\ImageEntry::make('testimonial_photo')
                                    ->label('Foto Profil')
                                    ->getStateUsing(function (Testimoni $record): ?string {
                                        return $record->testimoniProduk && $record->testimoniProduk->user && $record->testimoniProduk->user->foto_profil
                                            ? $record->testimoniProduk->user->foto_profil
                                            : null;
                                    })
                                    ->circular()
                                    ->size(100)
                                    ->visible(function (Testimoni $record): bool {
                                        return $record->testimoniProduk && $record->testimoniProduk->user && $record->testimoniProduk->user->foto_profil;
                                    }),

                                Infolists\Components\TextEntry::make('testimonial_content')
                                    ->label('Isi Testimoni')
                                    ->getStateUsing(function (Testimoni $record): string {
                                        return $record->testimoniProduk ? ($record->testimoniProduk->isi_testimoni ?? 'Tidak ada konten') : 'Tidak ada konten';
                                    })
                                    ->html()
                                    ->columnSpan(2),
                            ]),

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
                                    ->icon('heroicon-o-shopping-bag'),

                                Infolists\Components\TextEntry::make('testimoniProduk.produk.harga_produk')
                                    ->label('Harga Produk')
                                    ->money('IDR')
                                    ->icon('heroicon-o-ticket'),
                            ]),
                    ])
                    ->visible(fn(Testimoni $record): bool => !is_null($record->id_testimoni_produk)),

                // Detail Testimoni Lowongan
                Infolists\Components\Section::make('Detail Testimoni Lowongan')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\ImageEntry::make('testimonial_photo')
                                    ->label('Foto Profil')
                                    ->getStateUsing(function (Testimoni $record): ?string {
                                        return $record->testimoniLowongan && $record->testimoniLowongan->user && $record->testimoniLowongan->user->foto_profil
                                            ? $record->testimoniLowongan->user->foto_profil
                                            : null;
                                    })
                                    ->circular()
                                    ->size(100)
                                    ->visible(function (Testimoni $record): bool {
                                        return $record->testimoniLowongan && $record->testimoniLowongan->user && $record->testimoniLowongan->user->foto_profil;
                                    }),

                                Infolists\Components\TextEntry::make('testimonial_content')
                                    ->label('Isi Testimoni')
                                    ->getStateUsing(function (Testimoni $record): string {
                                        return $record->testimoniLowongan ? ($record->testimoniLowongan->isi_testimoni ?? 'Tidak ada konten') : 'Tidak ada konten';
                                    })
                                    ->html()
                                    ->columnSpan(2),
                            ]),

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
                                    ->icon('heroicon-o-briefcase'),
                                Infolists\Components\TextEntry::make('testimoniLowongan.lowongan.jenis_lowongan')
                                    ->label('Jenis Lowongan')
                                    ->icon('heroicon-o-tag'),
                            ]),
                    ])
                    ->visible(fn(Testimoni $record): bool => !is_null($record->id_testimoni_lowongan)),

                // Detail Testimoni Event
                Infolists\Components\Section::make('Detail Testimoni Event')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\ImageEntry::make('testimonial_photo')
                                    ->label('Foto Profil')
                                    ->getStateUsing(function (Testimoni $record): ?string {
                                        return $record->testimoniEvent && $record->testimoniEvent->user && $record->testimoniEvent->user->foto_profil
                                            ? $record->testimoniEvent->user->foto_profil
                                            : null;
                                    })
                                    ->circular()
                                    ->size(100)
                                    ->visible(function (Testimoni $record): bool {
                                        return $record->testimoniEvent && $record->testimoniEvent->user && $record->testimoniEvent->user->foto_profil;
                                    }),

                                Infolists\Components\TextEntry::make('testimonial_content')
                                    ->label('Isi Testimoni')
                                    ->getStateUsing(function (Testimoni $record): string {
                                        return $record->testimoniEvent ? ($record->testimoniEvent->isi_testimoni ?? 'Tidak ada konten') : 'Tidak ada konten';
                                    })
                                    ->html()
                                    ->columnSpan(2),
                            ]),

                        Infolists\Components\TextEntry::make('testimonial_rating')
                            ->label('Rating')
                            ->getStateUsing(function (Testimoni $record): string {
                                if ($record->testimoniEvent && isset($record->testimoniEvent->rating)) {
                                    return str_repeat('⭐', $record->testimoniEvent->rating) . " ({$record->testimoniEvent->rating}/5)";
                                }
                                return 'Tidak ada rating';
                            })
                            ->visible(fn(Testimoni $record): bool => $record->testimoniEvent && isset($record->testimoniEvent->rating)),

                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('testimoniEvent.event.nama_event')
                                    ->label('Nama Event')
                                    ->icon('heroicon-o-calendar-days'),
                            ]),
                    ])
                    ->visible(fn(Testimoni $record): bool => !is_null($record->id_testimoni_event)),

                // Detail Testimoni Artikel
                Infolists\Components\Section::make('Detail Testimoni Artikel')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\ImageEntry::make('testimonial_photo')
                                    ->label('Foto Profil')
                                    ->getStateUsing(function (Testimoni $record): ?string {
                                        return $record->testimoniArtikel && $record->testimoniArtikel->user && $record->testimoniArtikel->user->foto_profil
                                            ? $record->testimoniArtikel->user->foto_profil
                                            : null;
                                    })
                                    ->circular()
                                    ->size(100)
                                    ->visible(function (Testimoni $record): bool {
                                        return $record->testimoniArtikel && $record->testimoniArtikel->user && $record->testimoniArtikel->user->foto_profil;
                                    }),

                                Infolists\Components\TextEntry::make('testimonial_content')
                                    ->label('Isi Testimoni')
                                    ->getStateUsing(function (Testimoni $record): string {
                                        return $record->testimoniArtikel ? ($record->testimoniArtikel->isi_testimoni ?? 'Tidak ada konten') : 'Tidak ada konten';
                                    })
                                    ->html()
                                    ->columnSpan(2),
                            ]),

                        Infolists\Components\TextEntry::make('testimonial_rating')
                            ->label('Rating')
                            ->getStateUsing(function (Testimoni $record): string {
                                if ($record->testimoniArtikel && isset($record->testimoniArtikel->rating)) {
                                    return str_repeat('⭐', $record->testimoniArtikel->rating) . " ({$record->testimoniArtikel->rating}/5)";
                                }
                                return 'Tidak ada rating';
                            })
                            ->visible(fn(Testimoni $record): bool => $record->testimoniArtikel && isset($record->testimoniArtikel->rating)),

                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('testimoniArtikel.artikel.judul_artikel')
                                    ->label('Judul Artikel')
                                    ->icon('heroicon-o-document-text'),
                            ]),
                    ])
                    ->visible(fn(Testimoni $record): bool => !is_null($record->id_testimoni_artikel)),
            ]);
    }
}
