<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;
use App\Enums\ContentStatus;
use App\Models\KontenSlider;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use App\Helpers\FilamentGroupingHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\KontenSliderResource\Pages;
use App\Filament\Resources\KontenSliderResource\RelationManagers;
use Filament\Infolists\Components\Component;
use Illuminate\Database\Eloquent\Model;

class KontenSliderResource extends Resource
{
    protected static ?string $model = KontenSlider::class;
    protected static ?string $navigationIcon = 'heroicon-s-presentation-chart-line';

    public static function getNavigationGroup(): ?string
    {
        return FilamentGroupingHelper::getNavigationGroup('Content Management');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Konten Slider')
                    ->description('Pilih salah satu konten untuk slider ini')
                    ->schema([
                        Forms\Components\TextInput::make('durasi_slider')
                            ->label('Durasi Slider (detik)')
                            ->prefixIcon('heroicon-s-clock')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(60)
                            ->default(5)
                            ->helperText('Durasi tampilan setiap konten di slider'),
                        Forms\Components\Select::make('id_artikel')
                            ->label('Artikel')
                            ->relationship(
                                name: 'artikel',
                                titleAttribute: 'judul_artikel',
                                modifyQueryUsing: fn($query) => $query->where('status_artikel', ContentStatus::TERPUBLIKASI)
                            )
                            ->searchable()
                            ->native(false)
                            ->preload()
                            ->helperText('Pilih artikel untuk ditampilkan di slider'),
                        Forms\Components\Select::make('id_galeri')
                            ->label('Galeri')
                            ->relationship(
                                name: 'galeri',
                                titleAttribute: 'judul_galeri',
                                modifyQueryUsing: fn($query) => $query->where('status_galeri', ContentStatus::TERPUBLIKASI)
                            )
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->helperText('Pilih galeri untuk ditampilkan di slider'),

                        Forms\Components\Select::make('id_event')
                            ->label('Event')
                            ->relationship('event', 'nama_event')
                            ->searchable()
                            ->preload()
                            ->helperText('Pilih event untuk ditampilkan di slider'),

                        Forms\Components\Select::make('id_produk')
                            ->label('Produk')
                            ->relationship(
                                name: 'produk',
                                titleAttribute: 'nama_produk',
                                modifyQueryUsing: fn($query) => $query->where('status_produk', ContentStatus::TERPUBLIKASI)
                            )
                            ->native(false)
                            ->native(false)
                            ->searchable()
                            ->preload()
                            ->helperText('Pilih produk untuk ditampilkan di slider'),
                    ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Konten Slider Aktif')
                    ->description('Konten yang sedang ditampilkan di slider')
                    ->icon('heroicon-s-play')
                    ->schema([
                        Components\TextEntry::make('artikel.judul_artikel')
                            ->label('Artikel')
                            ->placeholder('Tidak ada artikel dipilih')
                            ->visible(fn($record) => $record->id_artikel !== null),

                        Components\TextEntry::make('galeri.judul_galeri')
                            ->label('Galeri')
                            ->placeholder('Tidak ada galeri dipilih')
                            ->visible(fn($record) => $record->id_galeri !== null),

                        Components\TextEntry::make('event.nama_event')
                            ->label('Event')
                            ->placeholder('Tidak ada event dipilih')
                            ->visible(fn($record) => $record->id_event !== null),

                        Components\TextEntry::make('produk.nama_produk')
                            ->label('Produk')
                            ->placeholder('Tidak ada produk dipilih')
                            ->visible(fn($record) => $record->id_produk !== null),
                    ])
                    ->columns(2),

                Components\Section::make('Durasi Slider')
                    ->schema([
                        Components\TextEntry::make('durasi_slider')
                            ->label('Durasi (detik)')
                            ->numeric()
                            ->suffix(' detik')
                            ->helperText('Durasi tampilan setiap konten di slider'),
                    ])
                    ->columns(1)
                    ->icon('heroicon-s-clock')
                    ->collapsible(),

                Components\Section::make('Informasi Waktu')
                    ->schema([
                        Components\TextEntry::make('created_at')
                            ->label('Dibuat Pada')
                            ->dateTime('d M Y H:i'),

                        Components\TextEntry::make('updated_at')
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
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('konten_type')
                    ->label('Tipe Konten')
                    ->options([
                        'artikel' => 'Artikel',
                        'galeri' => 'Galeri',
                        'event' => 'Event',
                        'produk' => 'Produk',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['value']) {
                            'artikel' => $query->whereNotNull('id_artikel'),
                            'galeri' => $query->whereNotNull('id_galeri'),
                            'event' => $query->whereNotNull('id_event'),
                            'produk' => $query->whereNotNull('id_produk'),
                            'lowongan' => $query->whereNotNull('id_lowongan'),
                            default => $query,
                        };
                    }),
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
            'index' => Pages\ViewKontenSlider::route('/'),
            'edit' => Pages\EditKontenSlider::route('/{record}/edit'),
        ];
    }
}
