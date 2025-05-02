<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimoniResource\Pages;
use App\Filament\Resources\TestimoniResource\RelationManagers;
use App\Models\Testimoni;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TestimoniResource extends Resource
{
    protected static ?string $model = Testimoni::class;
    protected static ?string $navigationGroup = 'Customer Service';
    protected static ?string $navigationIcon = 'heroicon-s-chat-bubble-bottom-center-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Testimoni')
                    ->schema([
                        Forms\Components\Select::make('id_user')
                            ->label('Pengguna')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\RichEditor::make('isi_testimoni')
                            ->label('Isi Testimoni')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('thumbnail_testimoni')
                            ->label('Foto Testimoni')
                            ->image()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1')
                            ->directory('testimoni-images')
                            ->disk('public')
                            ->optimize('webp')
                            ->required(),

                        Forms\Components\Select::make('rating')
                            ->label('Rating')
                            ->options([
                                1 => '⭐ (1) Sangat Buruk',
                                2 => '⭐⭐ (2) Buruk',
                                3 => '⭐⭐⭐ (3) Cukup',
                                4 => '⭐⭐⭐⭐ (4) Baik',
                                5 => '⭐⭐⭐⭐⭐ (5) Sangat Baik',
                            ])
                            ->required()
                            ->default(5),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail_testimoni')
                    ->label('Foto')
                    ->circular()
                    ->disk('public'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('isi_testimoni')
                    ->label('Testimoni')
                    ->html()
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(fn(int $state): string => str_repeat('⭐', $state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('rating')
                    ->label('Rating')
                    ->options([
                        1 => '⭐ (1) Sangat Buruk',
                        2 => '⭐⭐ (2) Buruk',
                        3 => '⭐⭐⭐ (3) Cukup',
                        4 => '⭐⭐⭐⭐ (4) Baik',
                        5 => '⭐⭐⭐⭐⭐ (5) Sangat Baik',
                    ]),

                Tables\Filters\SelectFilter::make('id_user')
                    ->label('Pengguna')
                    ->relationship('user', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListTestimonis::route('/'),
            'create' => Pages\CreateTestimoni::route('/create'),
            // 'edit' => Pages\EditTestimoni::route('/{record}/edit'),
        ];
    }
}
