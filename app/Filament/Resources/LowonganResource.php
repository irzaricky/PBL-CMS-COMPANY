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
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Lowongan')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Informasi Dasar')
                            ->schema([
                                Forms\Components\Section::make('Detail Lowongan')
                                    ->schema([
                                        Forms\Components\TextInput::make('judul')
                                            ->required()
                                            ->maxLength(255)
                                            ->label('Judul Lowongan'),
                                        Forms\Components\RichEditor::make('deskripsi')
                                            ->required()
                                            ->label('Deskripsi Lowongan')
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
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Detail Tambahan')
                            ->schema([
                                Forms\Components\Section::make('Manfaat & Persyaratan')
                                    ->schema([
                                        Forms\Components\RichEditor::make('manfaat')
                                            ->required()
                                            ->label('Manfaat')
                                            ->toolbarButtons([
                                                'bold',
                                                'bulletList',
                                                'italic',
                                                'orderedList',
                                            ])
                                            ->columnSpan(1),
                                        Forms\Components\RichEditor::make('persyaratan')
                                            ->required()
                                            ->label('Persyaratan')
                                            ->toolbarButtons([
                                                'bold',
                                                'bulletList',
                                                'italic',
                                                'orderedList',
                                            ])
                                            ->columnSpan(1),
                                    ])->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make('Periode')
                            ->schema([
                                Forms\Components\Section::make('Informasi Waktu')
                                    ->schema([
                                        Forms\Components\TextInput::make('durasi_lowongan')
                                            ->required()
                                            ->numeric()
                                            ->label('Durasi (dalam hari)')
                                            ->suffix('hari')
                                            ->minValue(1)
                                            ->helperText('Lama waktu magang/kerja dalam hari'),
                                        Forms\Components\DateTimePicker::make('waktu_dibuka')
                                            ->required()
                                            ->native(false)
                                            ->label('Waktu Pendaftaran Dibuka'),
                                        Forms\Components\DateTimePicker::make('waktu_ditutup')
                                            ->required()
                                            ->native(false)
                                            ->label('Waktu Pendaftaran Ditutup')
                                            ->after('waktu_dibuka'),
                                    ])->columns(3),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->searchable(),
                Tables\Columns\TextColumn::make('durasi_lowongan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('waktu_dibuka')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('waktu_ditutup')
                    ->dateTime()
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
            'index' => Pages\ListLowongans::route('/'),
            'create' => Pages\CreateLowongan::route('/create'),
            'view' => Pages\ViewLowongan::route('/{record}'),
            'edit' => Pages\EditLowongan::route('/{record}/edit'),
        ];
    }
}
