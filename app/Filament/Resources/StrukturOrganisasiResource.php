<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StrukturOrganisasiResource\Pages;
use App\Filament\Resources\StrukturOrganisasiResource\RelationManagers;
use App\Filament\Resources\StrukturOrganisasiResource\Widgets\StrukturOrganisasiStats;
use App\Models\StrukturOrganisasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;

class StrukturOrganisasiResource extends Resource
{
    protected static ?string $model = StrukturOrganisasi::class;
    protected static ?string $navigationGroup = 'Company Owner';
    protected static ?string $navigationIcon = 'heroicon-s-users';
    protected static ?string $recordTitleAttribute = 'name';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Posisi')
                    ->schema([
                        Forms\Components\Select::make('id_user')
                            ->label('Pengguna')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('Pilih pengguna yang menempati posisi ini. Status posisi akan mengikuti status pengguna'),

                        Forms\Components\TextInput::make('jabatan')
                            ->label('Posisi/Jabatan')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Direktur Utama, Manager, dsb'),

                        Forms\Components\TextInput::make('deskripsi')
                            ->label('Deskripsi Posisi/Jabatan')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Bertanggung jawab atas pengelolaan perusahaan, dsb'),

                        Forms\Components\DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai Jabatan')
                            ->default(now())
                            ->seconds(false)
                            ->displayFormat('d F Y')
                            ->native(false)
                            ->required(),

                        Forms\Components\DatePicker::make('tanggal_selesai')
                            ->label('Tanggal Selesai Jabatan')
                            ->displayFormat('d F Y')
                            ->native(false)
                            ->afterOrEqual('tanggal_mulai')
                            ->helperText('Kosongkan jika masih aktif')
                            ->validationMessages([
                                'after_or_equal' => 'Tanggal selesai jabatan harus setelah tanggal mulai jabatan',
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama')
                    ->searchable()
                ,

                Tables\Columns\TextColumn::make('jabatan')
                    ->label('Posisi/Jabatan')
                    ->searchable()
                ,

                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi Posisi/Jabatan')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(fn(StrukturOrganisasi $record): string => $record->deskripsi)
                ,

                Tables\Columns\SelectColumn::make('user.status')
                    ->label('Status Pengguna')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Nonaktif',
                    ])
                    ->rules(['required']),

                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->date('d M Y'),

                Tables\Columns\TextColumn::make('tanggal_selesai')
                    ->label('Tanggal Selesai')
                    ->date('d M Y')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Dihapus Pada')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('tanggal_mulai', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('user.status')
                    ->label('Status Pengguna')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Nonaktif',
                    ]),

                Tables\Filters\Filter::make('tanggal_mulai')
                    ->form([
                        Forms\Components\DatePicker::make('from_date')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('to_date')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from_date'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal_mulai', '>=', $date),
                            )
                            ->when(
                                $data['to_date'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal_mulai', '<=', $date),
                            );
                    }),

                Tables\Filters\Filter::make('active_positions')
                    ->label('Posisi Aktif')
                    ->query(fn(Builder $query): Builder => $query
                        ->whereHas('user', function (Builder $query) {
                            $query->where('status', 'aktif');
                        })
                        ->where(function (Builder $query) {
                            $query->whereNull('tanggal_selesai')
                                ->orWhere('tanggal_selesai', '>=', now());
                        })),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('Arsipkan')
                    ->icon('heroicon-s-archive-box-arrow-down')
                    ->color('warning')
                    ->successNotificationTitle('Struktur organisasi berhasil diarsipkan'),
                Tables\Actions\RestoreAction::make()
                    ->successNotificationTitle('Struktur organisasi berhasil dipulihkan'),
                Tables\Actions\ForceDeleteAction::make()
                    ->label('hapus permanen')
                    ->successNotificationTitle('Struktur organisasi berhasil dihapus permanen'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotificationTitle('Struktur organisasi berhasil diarsipkan'),
                    RestoreBulkAction::make()
                        ->successNotificationTitle('Struktur organisasi berhasil dipulihkan'),
                    ForceDeleteBulkAction::make()
                        ->successNotificationTitle('Struktur organisasi berhasil dihapus permanen'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            StrukturOrganisasiStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStrukturOrganisasis::route('/'),
            'create' => Pages\CreateStrukturOrganisasi::route('/create'),
            'edit' => Pages\EditStrukturOrganisasi::route('/{record}/edit'),
        ];
    }
}
