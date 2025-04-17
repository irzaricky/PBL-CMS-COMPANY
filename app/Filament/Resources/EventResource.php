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
                            ->maxLength(255)
                            ->placeholder('Masukkan nama event'),
                            
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
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->directory('event-thumbnails')
                            ->disk('public'),
                            
                        Forms\Components\TextInput::make('lokasi_event')
                            ->label('Lokasi Event')
                            ->required()
                            ->maxLength(200)
                            ->placeholder('Masukkan lokasi event'),

                        Forms\Components\TextInput::make('lokasi_event')
                            ->label('Lokasi Event (Google Maps)')
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
                                    ->url(fn ($get) => $get('lokasi_event'), true)
                                    ->visible(fn ($get) => filled($get('lokasi_event')))
                            ),
                            // ->rules([
                            //     function() {
                            //         return function (string $attribute, $value, \Closure $fail) {
                            //             if (!preg_match('/maps\.google\.com|google\.com\/maps/i', $value)) {
                            //                 $fail("The {$attribute} must be a valid Google Maps URL.");
                            //             }
                            //         };
                            //     },
                            // ]),
                            
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\DateTimePicker::make('waktu_start_event')
                                    ->label('Waktu Mulai')
                                    ->required()
                                    ->seconds(false)
                                    ->displayFormat('d F Y - H:i')
                                    ->default(now()->startOfHour())
                                    ->native(false)
                                    ->minDate(now()),
                                    
                                Forms\Components\DateTimePicker::make('waktu_end_event')
                                    ->label('Waktu Selesai')
                                    ->required()
                                    ->seconds(false)
                                    ->displayFormat('d F Y - H:i')
                                    ->default(now()->startOfHour()->addHours(2))
                                    ->minDate(now())
                                    ->native(false)
                                    ->after('waktu_start_event'),
                            ]),
                    ]),
                    
                Forms\Components\Section::make('Informasi Pendaftaran')
                    ->schema([
                        Forms\Components\TextInput::make('link_daftar_event')
                            ->label('Link Pendaftaran')
                            ->prefix('https://')
                            ->placeholder('www.example.com/register')
                            ->maxLength(100),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail_event')
                    ->label('Thumbnail')
                    ->defaultImageUrl(url('/images/default-event.jpg'))
                    ->square(),
                    
                Tables\Columns\TextColumn::make('nama_event')
                    ->label('Nama Event')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                    
                Tables\Columns\TextColumn::make('lokasi_event')
                    ->label('Lokasi')
                    ->searchable()
                    ->limit(30)
                    ->url(fn ($record) => $record->lokasi_event)
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-map-pin'),
                    
                Tables\Columns\TextColumn::make('waktu_start_event')
                    ->label('Mulai')
                    ->dateTime('d M Y - H:i')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('waktu_end_event')
                    ->label('Selesai')
                    ->dateTime('d M Y - H:i')
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(function (Event $record): string {
                        $now = now();
                        
                        if ($now->lt($record->waktu_start_event)) {
                            return 'upcoming';
                        }
                        
                        if ($now->gt($record->waktu_end_event)) {
                            return 'completed';
                        }
                        
                        return 'ongoing';
                    })
                    ->colors([
                        'warning' => 'upcoming',
                        'success' => 'ongoing',
                        'danger' => 'completed',
                    ]),
                    
                Tables\Columns\TextColumn::make('link_daftar_event')
                    ->label('Link Pendaftaran')
                    ->url(fn ($record) => $record->link_daftar_event ? 'https://' . $record->link_daftar_event : null)
                    ->openUrlInNewTab()
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
