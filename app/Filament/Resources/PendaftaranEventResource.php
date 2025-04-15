<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendaftaranEventResource\Pages;
use App\Filament\Resources\PendaftaranEventResource\RelationManagers;
use App\Models\PendaftaranEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PendaftaranEventResource extends Resource
{
    protected static ?string $model = PendaftaranEvent::class;
    protected static ?string $navigationGroup = 'User Interactions';
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_event')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('id_users')
                    ->required()
                    ->numeric(),
                Forms\Components\DateTimePicker::make('tanggal_registrasi')
                    ->required(),
                Forms\Components\Toggle::make('status_registrasi')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_event')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('id_users')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_registrasi')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('status_registrasi')
                    ->boolean(),
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
            'index' => Pages\ListPendaftaranEvents::route('/'),
            'create' => Pages\CreatePendaftaranEvent::route('/create'),
            'view' => Pages\ViewPendaftaranEvent::route('/{record}'),
            'edit' => Pages\EditPendaftaranEvent::route('/{record}/edit'),
        ];
    }
}
