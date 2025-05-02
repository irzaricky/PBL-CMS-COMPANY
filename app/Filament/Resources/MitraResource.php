<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MitraResource\Pages;
use App\Filament\Resources\MitraResource\RelationManagers;
use App\Models\Mitra;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;
use App\Services\FileHandlers\SingleFileHandler;


class MitraResource extends Resource
{
    protected static ?string $model = Mitra::class;
    protected static ?string $navigationGroup = 'Company Management';
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Mitra')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Mitra/Perusahaan')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Masukkan nama mitra perusahaan'),

                        Forms\Components\FileUpload::make('logo')
                            ->label('Logo Perusahaan')
                            ->image()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1')
                            ->directory('mitra-logos')
                            ->disk('public')
                            ->helperText('Upload logo perusahaan (format: jpg, png, svg)')
                            ->optimize('webp'),

                        Forms\Components\Textarea::make('alamat_mitra')
                            ->label('Alamat Mitra')
                            ->rows(3)
                            ->maxLength(200)
                            ->placeholder('Masukkan alamat lengkap mitra'),

                        Forms\Components\DatePicker::make('tanggal_kemitraan')
                            ->label('Tanggal Kemitraan')
                            ->displayFormat('d F Y')
                            ->default(now())
                            ->native(false),
                    ]),

                Forms\Components\Section::make('Dokumen Legal')
                    ->schema([
                        Forms\Components\FileUpload::make('dok_siup')
                            ->label('Dokumen SIUP')
                            ->directory('mitra-dokumen/siup')
                            ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                            ->maxSize(5120) // 5MB
                            ->disk('public')
                            ->downloadable(),

                        Forms\Components\FileUpload::make('dok_npwp')
                            ->label('Dokumen NPWP')
                            ->directory('mitra-dokumen/npwp')
                            ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                            ->maxSize(5120) // 5MB
                            ->disk('public')
                            ->downloadable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label('Logo')
                    ->circular()
                    ->disk('public'),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Mitra')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('alamat_mitra')
                    ->label('Alamat')
                    ->limit(30)
                    ->tooltip(fn(Mitra $record): string => $record->alamat_mitra ?? '')
                    ->searchable(),

                Tables\Columns\IconColumn::make('dok_siup')
                    ->label('SIUP')
                    ->boolean()
                    ->trueIcon('heroicon-o-document-text')
                    ->falseIcon('heroicon-o-x-circle')
                    ->state(fn(Mitra $record): bool => !empty($record->dok_siup)),

                Tables\Columns\IconColumn::make('dok_npwp')
                    ->label('NPWP')
                    ->boolean()
                    ->trueIcon('heroicon-o-document-text')
                    ->falseIcon('heroicon-o-x-circle')
                    ->state(fn(Mitra $record): bool => !empty($record->dok_npwp)),

                Tables\Columns\TextColumn::make('tanggal_kemitraan')
                    ->label('Tanggal Kemitraan')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('has_documents')
                    ->label('Dengan Dokumen Lengkap')
                    ->query(fn(Builder $query): Builder => $query->whereNotNull('dok_siup')->whereNotNull('dok_npwp')),

                Tables\Filters\Filter::make('recent_partners')
                    ->label('Mitra Baru')
                    ->query(fn(Builder $query): Builder => $query->where('tanggal_kemitraan', '>=', now()->subMonths(3))),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function (Collection $records) {
                            SingleFileHandler::deleteBulkFiles($records, 'logo');
                        }),
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
            'index' => Pages\ListMitras::route('/'),
            'create' => Pages\CreateMitra::route('/create'),
            'edit' => Pages\EditMitra::route('/{record}/edit'),
        ];
    }
}
