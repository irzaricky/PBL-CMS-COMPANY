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
use Illuminate\Database\Eloquent\Collection;

class LowonganResource extends Resource
{
    protected static ?string $model = Lowongan::class;
    protected static ?string $navigationGroup = 'Customer Service';
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
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
                            ->default(fn() => auth()->id())
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

                                Forms\Components\Select::make('status_lowongan')
                                    ->label('Status Lowongan')
                                    ->options([
                                        'dibuka' => 'Dibuka',
                                        'ditutup' => 'Ditutup',
                                    ])
                                    ->default('dibuka')
                                    ->required(),
                            ]),
                    ]),
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

                Tables\Columns\BadgeColumn::make('jenis_lowongan')
                    ->label('Jenis Pekerjaan')
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

                Tables\Columns\BadgeColumn::make('status_lowongan')
                    ->label('Status')
                    ->colors([
                        'success' => 'dibuka',
                        'danger' => 'ditutup',
                    ]),

                Tables\Columns\TextColumn::make('tenaga_dibutuhkan')
                    ->label('Posisi terbuka')
                    ->numeric()
                    ->sortable(),

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

                Tables\Filters\SelectFilter::make('status_lowongan')
                    ->label('Status')
                    ->options([
                        'dibuka' => 'Dibuka',
                        'ditutup' => 'Ditutup',
                    ]),

                Tables\Filters\Filter::make('active')
                    ->label('Aktif')
                    ->query(fn(Builder $query): Builder => $query
                        ->where('tanggal_dibuka', '<=', now())
                        ->where('tanggal_ditutup', '>=', now())
                        ->where('status_lowongan', 'dibuka')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('updateStatus')
                        ->label('Update Status')
                        ->icon('heroicon-o-check-circle')
                        ->form([
                            Forms\Components\Select::make('status_lowongan')
                                ->label('Status Baru')
                                ->options([
                                    'dibuka' => 'Dibuka',
                                    'ditutup' => 'Ditutup',
                                ])
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            foreach ($records as $record) {
                                $record->update([
                                    'status_lowongan' => $data['status_lowongan'],
                                ]);
                            }
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
