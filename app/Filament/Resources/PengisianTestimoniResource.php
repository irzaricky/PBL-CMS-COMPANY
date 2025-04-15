<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengisianTestimoniResource\Pages;
use App\Filament\Resources\PengisianTestimoniResource\RelationManagers;
use App\Models\PengisianTestimoni;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PengisianTestimoniResource extends Resource
{
    protected static ?string $model = PengisianTestimoni::class;
    protected static ?string $navigationGroup = 'Testimoni';
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_testimoni')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('id_users')
                    ->required()
                    ->numeric(),
                Forms\Components\DateTimePicker::make('tanggal_pengisian')
                    ->required(),
                Forms\Components\Toggle::make('status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_testimoni')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('id_users')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_pengisian')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
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
            'index' => Pages\ListPengisianTestimonis::route('/'),
            'create' => Pages\CreatePengisianTestimoni::route('/create'),
            'view' => Pages\ViewPengisianTestimoni::route('/{record}'),
            'edit' => Pages\EditPengisianTestimoni::route('/{record}/edit'),
        ];
    }
}
