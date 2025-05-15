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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->label('Feature')
                ,
                Tables\Columns\TextColumn::make('label')
                    ->label('Label'),
                Tables\Columns\ToggleColumn::make('status_aktif')
                    ->label('Status Aktif')
                    ->onColor('success')
                    ->offColor('danger'),
            ])
            ->filters([
                //
            ])
            ->actions([])
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
