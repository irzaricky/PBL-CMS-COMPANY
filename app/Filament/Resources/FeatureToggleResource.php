<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\FeatureToggle;
use Filament\Resources\Resource;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FeatureToggleResource\Pages;
use App\Filament\Resources\FeatureToggleResource\RelationManagers;
use App\Filament\Resources\FeatureToggleResource\Pages\EditFeatureToggle;
use App\Filament\Resources\FeatureToggleResource\Pages\ListFeatureToggles;
use App\Filament\Resources\FeatureToggleResource\Pages\CreateFeatureToggle;

class FeatureToggleResource extends Resource
{
    protected static ?string $model = FeatureToggle::class;

    protected static ?string $navigationIcon = 'heroicon-s-adjustments-horizontal';
    protected static ?string $label = 'Fitur';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('key')
                //     ->label('Fitur yang ditampilkan')
                // ,
                Tables\Columns\TextColumn::make('label')
                    ->label('Daftar Fitur'),
                Tables\Columns\TextColumn::make('status_aktif')
                    ->label('Status Aktif')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Aktif' : 'Nonaktif')
                    ->color(fn (bool $state): string => $state ? 'success' : 'danger')
                    ->icon(fn (bool $state): string => $state ? 'heroicon-s-check-circle' : 'heroicon-s-x-circle'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('show')
                    ->label('Perlihatkan')
                    ->icon('heroicon-s-eye')
                    ->color('success')
                    ->action(fn (FeatureToggle $record) => $record->update(['status_aktif' => true]))
                    ->visible(fn (FeatureToggle $record) => !$record->status_aktif),
                    
                Tables\Actions\Action::make('hide')
                    ->label('Sembunyikan')
                    ->icon('heroicon-s-eye-slash')
                    ->color('danger')
                    ->action(fn (FeatureToggle $record) => $record->update(['status_aktif' => false]))
                    ->visible(fn (FeatureToggle $record) => $record->status_aktif),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('activate_all') 
                    ->label('Aktifkan semua')
                    ->action(function (Collection $records) {
                        $records->each(function ($record) {
                            $record->update(['status_aktif' => true]);
                        });
                    })
                    ->icon('heroicon-o-check-circle')
                    ->color('success'),

                Tables\Actions\BulkAction::make('deactivate_all')
                    ->label('Nonaktifkan semua')
                    ->action(function (Collection $records) {
                        $records->each(function ($record) {
                            $record->update(['status_aktif' => false]);
                        });
                    })
                    ->icon('heroicon-o-x-circle')
                    ->color('danger'),
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
            'index' => Pages\ListFeatureToggles::route('/'),
            // 'create' => Pages\CreateFeatureToggle::route('/create'),
            'edit' => Pages\EditFeatureToggle::route('/{record}/edit'),
        ];
    }

}
