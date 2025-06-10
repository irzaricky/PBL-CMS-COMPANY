<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\MediaSosial;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\MediaSosialResource\Pages;
use App\Helpers\FilamentGroupingHelper;
use Illuminate\Database\Eloquent\Model;

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
                        Forms\Components\TextInput::make('link')
                            ->label('Link')
                            ->required()
                            ->url()
                            ->maxLength(200)
                            ->placeholder('https://www.example.com/username')
                            ->helperText('Masukkan URL lengkap termasuk https://'),

                        Forms\Components\Toggle::make('status_aktif')
                            ->label('Status')
                            ->onColor('success')
                            ->offColor('danger')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_media_sosial')
                    ->label('Platform Media Sosial')
                    ->searchable(),

                Tables\Columns\TextColumn::make('link')
                    ->label('Link')
                    ->searchable()
                    ->url(fn(MediaSosial $record): string => $record->link)
                    ->openUrlInNewTab()
                    ->limit(30)
                    ->tooltip(fn(MediaSosial $record): string => $record->link),

                Tables\Columns\TextColumn::make('status_aktif')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(bool $state): string => $state ? 'Aktif' : 'Nonaktif')
                    ->color(fn(bool $state): string => $state ? 'success' : 'danger')
                    ->icon(fn(bool $state): string => $state ? 'heroicon-s-check-circle' : 'heroicon-s-x-circle'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_aktif')
                    ->options([
                        '1' => 'Aktif',
                        '0' => 'Nonaktif',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('show')
                    ->label('Perlihatkan')
                    ->icon('heroicon-s-eye')
                    ->color('success')
                    ->action(fn(MediaSosial $record) => $record->update(['status_aktif' => true]))
                    ->visible(fn(MediaSosial $record) => !$record->status_aktif),

                Tables\Actions\Action::make('hide')
                    ->label('Sembunyikan')
                    ->icon('heroicon-s-eye-slash')
                    ->color('danger')
                    ->action(fn(MediaSosial $record) => $record->update(['status_aktif' => false]))
                    ->visible(fn(MediaSosial $record) => $record->status_aktif),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
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

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMediaSosials::route('/'),
            'edit' => Pages\EditMediaSosial::route('/{record}/edit'),
        ];
    }
}
