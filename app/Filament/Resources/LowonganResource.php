<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LowonganResource\Pages;
use App\Filament\Resources\LowonganResource\RelationManagers;
use App\Models\Lowongan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LowonganResource extends Resource
{
    protected static ?string $model = Lowongan::class;
    protected static ?string $navigationGroup = 'Customer Service';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_user')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('judul_lowongan')
                    ->required()
                    ->maxLength(200),
                Forms\Components\Textarea::make('deskripsi_pekerjaan')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('jenis_lowongan')
                    ->required(),
                Forms\Components\TextInput::make('gaji')
                    ->numeric(),
                Forms\Components\DatePicker::make('tanggal_dibuka')
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_ditutup')
                    ->required(),
                Forms\Components\TextInput::make('status_lowongan')
                    ->required(),
                Forms\Components\TextInput::make('tenaga_dibutuhkan')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_user')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('judul_lowongan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_lowongan'),
                Tables\Columns\TextColumn::make('gaji')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_dibuka')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_ditutup')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_lowongan'),
                Tables\Columns\TextColumn::make('tenaga_dibutuhkan')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListLowongans::route('/'),
            'create' => Pages\CreateLowongan::route('/create'),
            'edit' => Pages\EditLowongan::route('/{record}/edit'),
        ];
    }
}
