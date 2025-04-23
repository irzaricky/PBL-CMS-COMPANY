<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KontenSliderResource\Pages;
use App\Filament\Resources\KontenSliderResource\RelationManagers;
use App\Models\KontenSlider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class KontenSliderResource extends Resource
{
    protected static ?string $model = KontenSlider::class;
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationIcon = 'heroicon-s-presentation-chart-line';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Slider')
                    ->schema([
                        Forms\Components\TextInput::make('judul_header')
                            ->label('Judul Header')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Masukkan judul untuk slider'),

                        Forms\Components\Select::make('id_user')
                            ->label('Pembuat')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),

                Forms\Components\Section::make('Konten Slider')
                    ->description('Pilih salah satu konten untuk slider ini')
                    ->schema([
                        Forms\Components\Select::make('id_artikel')
                            ->label('Artikel')
                            ->relationship('artikel', 'judul_artikel')
                            ->searchable()
                            ->preload()
                            ->helperText('Pilih artikel untuk ditampilkan di slider'),

                        Forms\Components\Select::make('id_galeri')
                            ->label('Galeri')
                            ->relationship('galeri', 'judul_galeri')
                            ->searchable()
                            ->preload()
                            ->helperText('Pilih galeri untuk ditampilkan di slider'),

                        Forms\Components\Select::make('id_event')
                            ->label('Event')
                            ->relationship('event', 'nama_event')
                            ->searchable()
                            ->preload()
                            ->helperText('Pilih event untuk ditampilkan di slider'),

                        Forms\Components\Select::make('id_produk')
                            ->label('Produk')
                            ->relationship('produk', 'nama_produk')
                            ->searchable()
                            ->preload()
                            ->helperText('Pilih produk untuk ditampilkan di slider'),

                        Forms\Components\Select::make('id_lowongan')
                            ->label('Lowongan')
                            ->relationship('lowongan', 'judul_lowongan')
                            ->searchable()
                            ->preload()
                            ->helperText('Pilih lowongan untuk ditampilkan di slider'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul_header')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pembuat')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kontenType')
                    ->label('Tipe Konten')
                    ->getStateUsing(function (KontenSlider $record): string {
                        if ($record->id_artikel)
                            return 'Artikel';
                        if ($record->id_galeri)
                            return 'Galeri';
                        if ($record->id_event)
                            return 'Event';
                        if ($record->id_produk)
                            return 'Produk';
                        if ($record->id_lowongan)
                            return 'Lowongan';
                        return 'Tidak ada';
                    }),

                Tables\Columns\TextColumn::make('kontenJudul')
                    ->label('Judul Konten')
                    ->getStateUsing(function (KontenSlider $record): ?string {
                        if ($record->artikel)
                            return $record->artikel->judul_artikel;
                        if ($record->galeri)
                            return $record->galeri->judul_galeri;
                        if ($record->event)
                            return $record->event->nama_event;
                        if ($record->produk)
                            return $record->produk->nama_produk;
                        if ($record->lowongan)
                            return $record->lowongan->judul_lowongan;
                        return null;
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function (Builder $query) use ($search) {
                            $query->whereHas('artikel', fn($q) => $q->where('judul_artikel', 'like', "%{$search}%"))
                                ->orWhereHas('galeri', fn($q) => $q->where('judul_galeri', 'like', "%{$search}%"))
                                ->orWhereHas('event', fn($q) => $q->where('nama_event', 'like', "%{$search}%"))
                                ->orWhereHas('produk', fn($q) => $q->where('nama_produk', 'like', "%{$search}%"))
                                ->orWhereHas('lowongan', fn($q) => $q->where('judul_lowongan', 'like', "%{$search}%"));
                        });
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
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
                        'lowongan' => 'Lowongan',
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

                Tables\Filters\SelectFilter::make('id_user')
                    ->label('Pembuat')
                    ->relationship('user', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListKontenSliders::route('/'),
            'create' => Pages\CreateKontenSlider::route('/create'),
            'edit' => Pages\EditKontenSlider::route('/{record}/edit'),
        ];
    }
}
