<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Unduhan;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Actions\RestoreBulkAction;
use App\Services\FileHandlers\SingleFileHandler;
use App\Filament\Resources\UnduhanResource\Pages;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UnduhanResource\RelationManagers;
use App\Filament\Resources\UnduhanResource\Widgets\UnduhanStats;

class UnduhanResource extends Resource
{
    protected static ?string $model = Unduhan::class;
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationIcon = 'heroicon-s-document-arrow-down';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Unduhan')
                    ->schema([
                        Forms\Components\TextInput::make('nama_unduhan')
                            ->label('Nama Unduhan')
                            ->required()
                            ->maxLength(100)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (!empty($state)) {
                                    $set('slug', str($state)->slug());
                                } else {
                                    $set('slug', null);
                                }
                            }),

                        Forms\Components\Select::make('id_kategori_unduhan')
                            ->label('Kategori Unduhan')
                            ->relationship('kategoriUnduhan', 'nama_kategori_unduhan')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('nama_kategori_unduhan')
                                    ->label('Nama Kategori')
                                    ->required()
                                    ->maxLength(50),
                            ])
                            ->editOptionForm([
                                Forms\Components\TextInput::make('nama_kategori_unduhan')
                                    ->label('Nama Kategori')
                                    ->required()
                                    ->maxLength(50),
                            ])
                            ->manageOptionForm([
                                Forms\Components\TextInput::make('nama_kategori_unduhan')
                                    ->label('Nama Kategori')
                                    ->required()
                                    ->maxLength(50),
                            ]),

                        Forms\Components\Select::make('id_user')
                            ->label('Pengunggah')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true)
                            ->dehydrated()
                            ->helperText('Akan terisi otomatis berdasarkan nama unduhan'),
                    ]),

                Forms\Components\Section::make('File & Konten')
                    ->schema([
                        Forms\Components\FileUpload::make('lokasi_file')
                            ->label('File Unduhan')
                            ->directory('unduhan-files')
                            ->acceptedFileTypes(['application/pdf', 'application/zip', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'])
                            ->maxSize(10240) // 10MB
                            ->required()
                            ->disk('public')
                            ->downloadable()
                            ->helperText('Upload file untuk diunduh (format: pdf, doc, docx, xls, xlsx, ppt, pptx, zip)'),

                        Forms\Components\RichEditor::make('deskripsi')
                            ->label('Deskripsi Unduhan')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('unduhan-attachments')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('jumlah_unduhan')
                            ->label('Jumlah Unduhan')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Jumlah ini akan bertambah otomatis ketika file diunduh'),
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
                Tables\Columns\TextColumn::make('nama_unduhan')
                    ->label('Nama Unduhan')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('kategoriUnduhan.nama_kategori_unduhan')
                    ->label('Kategori')
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengunggah')
                    ->searchable(),

                Tables\Columns\TextColumn::make('lokasi_file')
                    ->label('File')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('jumlah_unduhan')
                    ->label('Jumlah Unduhan')
                    ->numeric()
                ,

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
            ->filters([
                Tables\Filters\SelectFilter::make('id_kategori_unduhan')
                    ->label('Kategori')
                    ->relationship('kategoriUnduhan', 'nama_kategori_unduhan'),

                Tables\Filters\SelectFilter::make('id_user')
                    ->label('Pengunggah')
                    ->relationship('user', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('arsipkan')
                    ->icon('heroicon-s-archive-box-arrow-down')
                    ->color('warning')
                    ->successNotificationTitle('Unduhan berhasil diarsipkan'),
                Tables\Actions\RestoreAction::make()
                    ->successNotificationTitle('Unduhan berhasil dipulihkan'),
                Tables\Actions\ForceDeleteAction::make()
                    ->label('hapus permanen')
                    ->successNotificationTitle('Unduhan berhasil dihapus permanen')
                    ->before(function ($record) {
                        SingleFileHandler::deleteFile($record, 'lokasi_file');
                    }),
                Tables\Actions\Action::make('download')
                    ->label('Unduh')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn(Unduhan $record) => url('storage/' . $record->lokasi_file))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotificationTitle('Unduhan berhasil diarsipkan')
                        ->before(function (Collection $records) {
                            foreach ($records as $record) {
                                SingleFileHandler::deleteFile($record, 'lokasi_file');
                            }
                        }),
                    RestoreBulkAction::make()
                        ->successNotificationTitle('Unduhan berhasil dipulihkan'),
                    ForceDeleteBulkAction::make()
                        ->successNotificationTitle('Unduhan berhasil dihapus permanen')
                        ->before(function (Collection $records) {
                            foreach ($records as $record) {
                                SingleFileHandler::deleteFile($record, 'lokasi_file');
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

    public static function getWidgets(): array
    {
        return [
            UnduhanStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUnduhans::route('/'),
            'create' => Pages\CreateUnduhan::route('/create'),
            'edit' => Pages\EditUnduhan::route('/{record}/edit'),
        ];
    }
}
