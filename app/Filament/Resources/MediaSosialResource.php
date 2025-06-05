<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\MediaSosial;
use App\Enums\ContentStatus;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Services\FileHandlers\SingleFileHandler;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MediaSosialResource\Pages;
use App\Filament\Resources\MediaSosialResource\RelationManagers;
use App\Helpers\FilamentGroupingHelper;

class MediaSosialResource extends Resource
{
    protected static ?string $model = MediaSosial::class;
    protected static ?string $navigationIcon = 'heroicon-s-share';

    public static function getNavigationGroup(): ?string
    {
        return FilamentGroupingHelper::getNavigationGroup('Content Management');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Media Sosial')
                    ->schema([
                        Forms\Components\TextInput::make('nama_media_sosial')
                            ->label('Nama Media Sosial')
                            ->required()
                            ->maxLength(50)
                            ->placeholder('Contoh: Instagram, Facebook, Twitter, dll'),

                        Forms\Components\FileUpload::make('icon')
                            ->label('Icon')
                            ->image()
                            ->directory('media-sosial-icons')
                            ->disk('public')
                            ->required()

                            ->helperText('Upload icon media sosial (format: png, jpg, svg). Disarankan ukuran 64x64px.')
                            ->imageResizeMode('cover')
                            ->imageResizeTargetWidth(64)
                            ->imageResizeTargetHeight(64)
                            ->optimize('webp'),

                        Forms\Components\TextInput::make('link')
                            ->label('Link')
                            ->required()
                            ->url()
                            ->maxLength(200)
                            ->placeholder('https://www.example.com/username')
                            ->helperText('Masukkan URL lengkap termasuk https://'),

                        Forms\Components\Select::make('status')
                            ->label('Status Media Sosial')
                            ->options([
                                ContentStatus::TERPUBLIKASI->value => ContentStatus::TERPUBLIKASI->label(),
                                ContentStatus::TIDAK_TERPUBLIKASI->value => ContentStatus::TIDAK_TERPUBLIKASI->label()
                            ])
                            ->default(ContentStatus::TIDAK_TERPUBLIKASI)
                            ->native(false)
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_media_sosial')
                    ->label('Nama Media Sosial')
                    ->searchable()
                ,

                Tables\Columns\ImageColumn::make('icon')
                    ->label('Icon')
                    ->disk('public'),

                Tables\Columns\TextColumn::make('link')
                    ->label('Link')
                    ->searchable()
                    ->url(fn(MediaSosial $record): string => $record->link)
                    ->openUrlInNewTab()
                    ->limit(30)
                    ->tooltip(fn(MediaSosial $record): string => $record->link),

                Tables\Columns\SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        ContentStatus::TERPUBLIKASI->value => ContentStatus::TERPUBLIKASI->label(),
                        ContentStatus::TIDAK_TERPUBLIKASI->value => ContentStatus::TIDAK_TERPUBLIKASI->label(),
                    ])
                    ->rules(['required']),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d M Y H:i')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Nonaktif',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (MediaSosial $record) {
                        SingleFileHandler::deleteFile($record->icon, 'icon');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('updateStatus')
                        ->label('Update Status')
                        ->icon('heroicon-o-check-circle')
                        ->action(function (Collection $records, array $data): void {
                            foreach ($records as $record) {
                                $record->update([
                                    'status' => $data['status'],
                                ]);
                            }
                        })
                        ->form([
                            Forms\Components\Select::make('status')
                                ->label('Status')
                                ->options([
                                    'aktif' => 'Aktif',
                                    'nonaktif' => 'Nonaktif',
                                ])
                                ->native(false)
                                ->required(),
                        ]),
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function (Collection $records) {
                            SingleFileHandler::deleteBulkFiles($records, 'icon');
                        }),
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
            'index' => Pages\ListMediaSosials::route('/'),
            'create' => Pages\CreateMediaSosial::route('/create'),
            // ada tambahan validasi pada edit event
            'edit' => Pages\EditMediaSosial::route('/{record}/edit'),
        ];
    }
}
