<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MediaSosialResource\Pages;
use App\Filament\Resources\MediaSosialResource\RelationManagers;
use App\Models\MediaSosial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MediaSosialResource extends Resource
{
    protected static ?string $model = MediaSosial::class;
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationIcon = 'heroicon-s-share';

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
                            ->imageResizeTargetWidth('64')
                            ->imageResizeTargetHeight('64')
                            ->helperText('Upload icon media sosial (format: png, jpg, svg). Disarankan ukuran 64x64px.'),

                        Forms\Components\TextInput::make('link')
                            ->label('Link')
                            ->required()
                            ->url()
                            ->maxLength(200)
                            ->placeholder('https://www.example.com/username')
                            ->helperText('Masukkan URL lengkap termasuk https://'),
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
                    ->sortable(),

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

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                // No filters needed
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
            'index' => Pages\ListMediaSosials::route('/'),
            'create' => Pages\CreateMediaSosial::route('/create'),
            'edit' => Pages\EditMediaSosial::route('/{record}/edit'),
        ];
    }
}
