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
use Illuminate\Support\Str;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Event')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Basic Information')
                            ->schema([
                                Forms\Components\Section::make('Event Details')
                                    ->schema([
                                        Forms\Components\TextInput::make('nama')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->label('Event Name')
                                            ->afterStateUpdated(function (string $state, Forms\Set $set) {
                                                $set('slug', Str::slug($state));
                                            }),
                                        Forms\Components\TextInput::make('slug')
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(ignoreRecord: true),
                                        Forms\Components\FileUpload::make('gambar_cover')
                                            ->label('Cover Image')
                                            ->image()
                                            ->directory('event-covers')
                                            ->maxSize(2048),
                                    ])->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make('Description')
                            ->schema([
                                Forms\Components\RichEditor::make('deskripsi')
                                    ->label('Event Description')
                                    ->toolbarButtons([
                                        'blockquote',
                                        'bold',
                                        'bulletList',
                                        'h2',
                                        'h3',
                                        'italic',
                                        'link',
                                        'orderedList',
                                        'redo',
                                        'strike',
                                        'undo',
                                    ])
                                    ->required(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Schedule & Location')
                            ->schema([
                                Forms\Components\Section::make('Event Schedule')
                                    ->schema([
                                        Forms\Components\DateTimePicker::make('waktu_mulai')
                                            ->label('Start Time')
                                            ->required(),
                                        Forms\Components\DateTimePicker::make('waktu_akhir')
                                            ->label('End Time')
                                            ->required(),
                                    ])->columns(2),

                                Forms\Components\Section::make('Event Location')
                                    ->schema([
                                        Forms\Components\TextInput::make('lokasi')
                                            ->label('Location')
                                            ->placeholder('Venue name, address, or online')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('link_pendaftaran')
                                            ->label('Registration Link')
                                            ->url()
                                            ->placeholder('https://example.com/register')
                                            ->maxLength(255),
                                    ])->columns(2),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('gambar_cover')
                    ->label('Cover Image'),
                Tables\Columns\TextColumn::make('waktu_mulai')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('waktu_akhir')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lokasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('link_pendaftaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            'view' => Pages\ViewEvent::route('/{record}'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
