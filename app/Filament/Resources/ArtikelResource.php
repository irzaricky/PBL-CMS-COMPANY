<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArtikelResource\Pages;
use App\Filament\Resources\ArtikelResource\RelationManagers;
use App\Models\Artikel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ArtikelResource extends Resource
{
    protected static ?string $model = Artikel::class;

    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Artikel')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Informasi Dasar')
                            ->schema([
                                Forms\Components\Section::make('Detail Artikel')
                                    ->schema([
                                        Forms\Components\Select::make('id_users')
                                            ->label('Author')
                                            ->relationship('user', 'name')
                                            ->default(auth()->id())
                                            ->required(),

                                        Forms\Components\Select::make('id_kategori_artikel')
                                            ->label('Category')
                                            ->relationship('kategori', 'nama')
                                            ->required(),

                                        Forms\Components\TextInput::make('judul')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->label('Judul Artikel')
                                            ->afterStateUpdated(function (string $state, Forms\Set $set) {
                                                $set('slug', Str::slug($state));
                                            }),

                                        Forms\Components\TextInput::make('slug')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(255)
                                            ->helperText('URL-friendly version of the title'),
                                    ])->columns(2),

                                Forms\Components\Section::make('Visual')
                                    ->schema([
                                        Forms\Components\FileUpload::make('gambar_cover')
                                            ->label('Cover Image')
                                            ->image()
                                            ->directory('artikel-covers')
                                            ->maxSize(2048)
                                            ->helperText('Recommended size: 1200 x 630 pixels'),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Konten')
                            ->schema([
                                Forms\Components\RichEditor::make('konten')
                                    ->required()
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
                                    ->label('Isi Artikel')
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Publikasi')
                            ->schema([
                                Forms\Components\Section::make('Pengaturan Publikasi')
                                    ->schema([
                                        Forms\Components\DateTimePicker::make('tanggal_upload')
                                            ->label('Tanggal Publikasi')
                                            ->default(now())
                                            ->native(false)
                                            ->seconds(false)
                                            ->required(),

                                        Forms\Components\Select::make('status')
                                            ->label('Status Artikel')
                                            ->options([
                                                'dipublish' => 'Dipublish',
                                                'dipending' => 'Dipending',
                                                'draft' => 'Draft',
                                            ])
                                            ->default('dipending')
                                            ->required()
                                            ->helperText('Status publikasi artikel')
                                    ])->columns(2),
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
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Author')
                    ->sortable(),
                Tables\Columns\TextColumn::make('kategori.nama')
                    ->label('Category')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('gambar_cover')
                    ->label('Cover Image'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'dipublish' => 'success',
                        'dipending' => 'warning',
                        'draft' => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_upload')
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
            'index' => Pages\ListArtikels::route('/'),
            'create' => Pages\CreateArtikel::route('/create'),
            'view' => Pages\ViewArtikel::route('/{record}'),
            'edit' => Pages\EditArtikel::route('/{record}/edit'),
        ];
    }
}
