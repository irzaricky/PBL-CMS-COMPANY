<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Lowongan;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\LowonganResource\Pages;
use App\Services\FileHandlers\MultipleFileHandler;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LowonganResource\RelationManagers;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Filters\TrashedFilter;

class LowonganResource extends Resource
{
    protected static ?string $model = Lowongan::class;
    protected static ?string $navigationGroup = 'Customer Service';
    protected static ?string $navigationIcon = 'heroicon-s-briefcase';
    protected static ?string $recordTitleAttribute = 'judul_lowongan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Lowongan')
                    ->schema([
                        Forms\Components\TextInput::make('judul_lowongan')
                            ->label('Judul Lowongan')
                            ->required()
                            ->maxLength(200)
                            ->placeholder('Masukkan judul lowongan pekerjaan'),

                        Forms\Components\Select::make('id_user')
                            ->label('Pembuat')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->default(fn() => Auth::id())
                            ->required(),

                        Forms\Components\Select::make('jenis_lowongan')
                            ->label('Jenis Pekerjaan')
                            ->options([
                                'Full-time' => 'Full-time',
                                'Part-time' => 'Part-time',
                                'Freelance' => 'Freelance',
                                'Internship' => 'Internship',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('gaji')
                            ->label('Gaji')
                            ->prefix('Rp')
                            ->numeric()
                            ->placeholder('Gaji/tunjangan yang ditawarkan')
                            ->helperText('Nominal'),

                        Forms\Components\TextInput::make('tenaga_dibutuhkan')
                            ->label('Jumlah Posisi')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->required()
                            ->helperText('Jumlah orang yang dibutuhkan untuk posisi ini'),
                    ]),

                Forms\Components\Section::make('Detail Lowongan')
                    ->schema([
                        Forms\Components\RichEditor::make('deskripsi_pekerjaan')
                            ->label('Deskripsi Pekerjaan')
                            ->required()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('lowongan-attachments')
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('thumbnail_lowongan')
                            ->label('Gambar Lowongan')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->directory('lowongan-images')
                            ->maxFiles(5)
                            ->helperText('Upload hingga 5 gambar untuk artikel (format: jpg, png, webp)')
                            ->disk('public')
                            ->columnSpanFull()
                            ->optimize('webp'),
                    ]),

                Forms\Components\Section::make('Periode Lowongan')
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\DatePicker::make('tanggal_dibuka')
                                    ->label('Tanggal Dibuka')
                                    ->required()
                                    ->default(now())
                                    ->displayFormat('d F Y')
                                    ->native(false)
                                    ->minDate(fn($record) => $record ? null : now())
                                    ->validationMessages([
                                        'after_or_equal' => 'Tanggal dibuka harus sebelum tanggal ditutup.',
                                    ]),

                                Forms\Components\DatePicker::make('tanggal_ditutup')
                                    ->label('Tanggal Ditutup')
                                    ->required()
                                    ->displayFormat('d F Y')
                                    ->native(false)
                                    ->afterOrEqual('tanggal_dibuka')
                                    ->validationMessages([
                                        'after_or_equal' => 'Tanggal ditutup tidak boleh sebelum tanggal dibuka.',
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail_lowongan')
                    ->label('Gambar')
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText()
                    ->extraImgAttributes(['class' => 'object-cover']),

                Tables\Columns\TextColumn::make('judul_lowongan')
                    ->label('Judul Lowongan')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('jenis_lowongan')
                    ->label('Jenis Pekerjaan')
                    ->badge()
                    ->colors([
                        'primary' => 'Full-time',
                        'secondary' => 'Part-time',
                        'warning' => 'Freelance',
                        'success' => 'Internship',
                    ]),

                Tables\Columns\TextColumn::make('gaji')
                    ->label('Gaji')
                    ->money('Rp.')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_dibuka')
                    ->label('Tanggal Dibuka')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_ditutup')
                    ->label('Tanggal Ditutup')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Dipublikasi' => 'success',
                        'Ditutup' => 'danger',
                        default => 'secondary',
                    })
                    ->getStateUsing(function ($record): string {
                        $now = now();

                        if ($now->between($record->tanggal_dibuka, $record->tanggal_ditutup)) {
                            return 'Dipublikasi';
                        }

                        if ($now->isAfter($record->tanggal_ditutup)) {
                            return 'Ditutup';
                        }

                        return 'Belum Dibuka';
                    })
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('tenaga_dibutuhkan')
                    ->label('Posisi terbuka')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pembuat')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Dihapus Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis_lowongan')
                    ->label('Jenis Pekerjaan')
                    ->options([
                        'Full-time' => 'Full-time',
                        'Part-time' => 'Part-time',
                        'Freelance' => 'Freelance',
                        'Internship' => 'Internship',
                    ]),

                Tables\Filters\Filter::make('active')
                    ->label('Dipublikasi')
                    ->query(fn(Builder $query): Builder => $query
                        ->where('tanggal_dibuka', '<=', now())
                        ->where('tanggal_ditutup', '>=', now())),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotificationTitle('Lowongan berhasil diarsipkan')
                    ->before(function ($record) {
                        MultipleFileHandler::deleteFiles($record, 'thumbnail_lowongan');
                    }),
                Tables\Actions\RestoreAction::make()
                    ->successNotificationTitle('Lowongan berhasil dipulihkan'),
                Tables\Actions\ForceDeleteAction::make()
                    ->successNotificationTitle('Lowongan berhasil dihapus permanen')
                    ->before(function ($record) {
                        MultipleFileHandler::deleteFiles($record, 'thumbnail_lowongan');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotificationTitle('Lowongan berhasil diarsipkan')
                        ->before(function (Collection $records) {
                            MultipleFileHandler::deleteBulkFiles($records, 'thumbnail_lowongan');
                        }),
                    RestoreBulkAction::make()
                        ->successNotificationTitle('Lowongan berhasil dipulihkan'),
                    ForceDeleteBulkAction::make()
                        ->successNotificationTitle('Lowongan berhasil dihapus permanen')
                        ->before(function (Collection $records) {
                            MultipleFileHandler::deleteBulkFiles($records, 'thumbnail_lowongan');
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
            'index' => Pages\ListLowongans::route('/'),
            'create' => Pages\CreateLowongan::route('/create'),
            'edit' => Pages\EditLowongan::route('/{record}/edit'),
        ];
    }
}
