<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationIcon = 'heroicon-s-calendar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar')
                    ->schema([
                        Forms\Components\TextInput::make('nama_event')
                            ->label('Nama Event')
                            ->required()
                            ->maxLength(100)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $state, callable $set) {
                                $set('slug', str($state)->slug());
                            }),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true)
                            ->dehydrated()
                            ->helperText('Akan terisi otomatis berdasarkan nama event'),

                        Forms\Components\RichEditor::make('deskripsi_event')
                            ->label('Deskripsi Event')
                            ->required()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('event-attachments')
                            ->placeholder('Deskripsikan detail acara')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Detail Event')
                    ->schema([
                        Forms\Components\FileUpload::make('thumbnail_event')
                            ->label('Thumbnail Event')
                            ->image()
                            ->multiple() // Enable multiple uploads
                            ->reorderable() // Allow reordering of images
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->directory('event-thumbnails')
                            ->maxFiles(5) // Optional: limit number of files
                            ->helperText('Deskripsikan eventmu, maksimal 5 gambar(format: jpg, png, webp)')
                            ->disk('public')
                            ->columnSpanFull(),


                        Forms\Components\TextInput::make('lokasi_event')
                            ->label('Nama Lokasi Event')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Masukkan nama atau alamat lokasi event')
                            ->helperText('Contoh: Gedung Serbaguna UGM, Yogyakarta')
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('link_lokasi_event')
                            ->label('Link Lokasi Event (Google Maps)')
                            ->required()
                            ->maxLength(200)
                            ->url()
                            ->placeholder('https://maps.google.com/?q=Your+Location')
                            ->helperText('Berikan URL Google Maps untuk lokasi event')
                            ->prefixIcon('heroicon-s-map-pin')
                            ->suffixAction(
                                Forms\Components\Actions\Action::make('open')
                                    ->icon('heroicon-o-arrow-top-right-on-square')
                                    ->tooltip('Open map in new tab')
                                    ->url(fn($get) => $get('link_lokasi_event'), true)
                                    ->visible(fn($get) => filled($get('link_lokasi_event')))
                            ),


                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\DateTimePicker::make('waktu_start_event')
                                    ->label('Waktu Mulai')
                                    ->required()
                                    ->seconds(false)
                                    ->displayFormat('d F Y - H:i')
                                    ->native(false)
                                    ->minDate(now())
                                    ->helperText('Waktu mulai harus diisi terlebih dahulu')
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $set('waktu_end_event', null);
                                    }),

                                Forms\Components\DateTimePicker::make('waktu_end_event')
                                    ->label('Waktu Selesai')
                                    ->required()
                                    ->seconds(false)
                                    ->displayFormat('d F Y - H:i')
                                    ->native(false)
                                    ->minDate(fn(callable $get) => $get('waktu_start_event') ?: now())
                                    ->helperText('Waktu selesai harus setelah waktu mulai')
                                    ->disabled(fn(callable $get) => !$get('waktu_start_event')),
                            ]),
                    ]),

                Forms\Components\Section::make('Informasi Pendaftaran')
                    ->schema([
                        Forms\Components\TextInput::make('link_daftar_event')
                            ->label('Link Pendaftaran')
                            ->placeholder('https://www.example.com/register')
                            ->url() // Add URL validation
                            ->helperText('Masukkan URL lengkap termasuk https://')
                            ->maxLength(200),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail_event')
                    ->label('Thumbnail')
                    ->circular()
                    ->stacked() // Display multiple images in a stack
                    ->limit(3) // Show only first 3 images
                    ->limitedRemainingText() // Shows "+X more" for remaining images
                    ->extraImgAttributes(['class' => 'object-cover']),

                Tables\Columns\TextColumn::make('nama_event')
                    ->label('Nama Event')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('lokasi_event')
                    ->label('Lokasi')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn($record) => $record->lokasi_event)
                    ->icon('heroicon-o-building-office'),

                Tables\Columns\TextColumn::make('link_lokasi_event')
                    ->label('Link Lokasi')
                    ->searchable()
                    ->limit(30)
                    ->url(fn($record) => $record->link_lokasi_event)
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-map-pin')
                    ->tooltip('Klik untuk melihat di Google Maps')
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('waktu_start_event')
                    ->label('Mulai')
                    ->dateTime('d M Y - H:i')
                    ->sortable()
                    ->icon('heroicon-o-calendar'),

                Tables\Columns\TextColumn::make('waktu_end_event')
                    ->label('Selesai')
                    ->dateTime('d M Y - H:i')
                    ->sortable()
                    ->icon('heroicon-o-clock'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(function (Event $record): string {
                        $now = now();

                        if ($now->lt($record->waktu_start_event)) {
                            return 'Akan datang';
                        }

                        if ($now->gt($record->waktu_end_event)) {
                            return 'Selesai';
                        }

                        return 'Sedang berlangsung';
                    })
                    ->colors([
                        'warning' => 'Akan datang',
                        'success' => 'Sedang berlangsung',
                        'danger' => 'Selesai',
                    ]),

                Tables\Columns\TextColumn::make('link_daftar_event')
                    ->label('Link Pendaftaran')
                    ->url(fn($record) => $record->link_daftar_event)
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-link')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'upcoming' => 'Upcoming',
                        'ongoing' => 'Ongoing',
                        'completed' => 'Completed',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['value']) {
                            'upcoming' => $query->where('waktu_start_event', '>', now()),
                            'ongoing' => $query->where('waktu_start_event', '<=', now())
                                ->where('waktu_end_event', '>=', now()),
                            'completed' => $query->where('waktu_end_event', '<', now()),
                            default => $query,
                        };
                    }),
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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
