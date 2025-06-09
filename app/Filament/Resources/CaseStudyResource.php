<?php

namespace App\Filament\Resources;

use App\Enums\ContentStatus;
use App\Filament\Resources\CaseStudyResource\Pages;
use App\Filament\Resources\CaseStudyResource\RelationManagers;
use App\Filament\Resources\CaseStudyResource\Widgets\CaseStudyStats;
use App\Models\CaseStudy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use App\Services\FileHandlers\MultipleFileHandler;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use App\Helpers\FilamentGroupingHelper;

class CaseStudyResource extends Resource
{
    protected static ?string $model = CaseStudy::class;
    protected static ?string $navigationIcon = 'heroicon-s-document';

    public static function getNavigationGroup(): ?string
    {
        return FilamentGroupingHelper::getNavigationGroup('Content Management');
    }

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
                Forms\Components\Section::make('Informasi Case Study')
                    ->schema([
                        Forms\Components\TextInput::make('judul_case_study')
                            ->label('Judul Case Study')
                            ->required()
                            ->maxLength(100)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (!empty($state)) {
                                    $baseSlug = str($state)->slug();
                                    $dateSlug = now()->format('Y-m-d');
                                    $set('slug_case_study', $baseSlug . '-' . $dateSlug);
                                } else {
                                    $set('slug_case_study', null);
                                }
                            }),

                        Forms\Components\Select::make('id_mitra')
                            ->label('Mitra')
                            ->relationship('mitra', 'nama')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->required(),

                        Forms\Components\TextInput::make('slug_case_study')
                            ->required()
                            ->maxLength(100)
                            ->unique(CaseStudy::class, 'slug_case_study', ignoreRecord: true)
                            ->dehydrated()
                            ->helperText('Akan terisi otomatis berdasarkan judul')
                            ->validationMessages([
                                'unique' => 'Slug sudah terpakai. Silakan gunakan slug lain.',
                            ]),

                        Forms\Components\Select::make('status_case_study')
                            ->label('Status')
                            ->options([
                                ContentStatus::TIDAK_TERPUBLIKASI->value => ContentStatus::TIDAK_TERPUBLIKASI->label(),
                                ContentStatus::TERPUBLIKASI->value => ContentStatus::TERPUBLIKASI->label(),
                            ])
                            ->default(ContentStatus::TIDAK_TERPUBLIKASI)
                            ->native(false)
                            ->required(),

                        Forms\Components\Textarea::make('deskripsi_case_study')
                            ->label('Deskripsi Singkat')
                            ->required()
                            ->maxLength(500)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Media & Konten')
                    ->schema([
                        Forms\Components\FileUpload::make('thumbnail_case_study')
                            ->label('Galeri Gambar Case Study')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->directory('case-study-thumbnails')
                            ->maxFiles(5)
                            ->helperText('Upload hingga 5 gambar untuk case study (format: jpg, png, webp)')
                            ->disk('public')
                            ->columnSpanFull()
                            ->imageEditor()
                            ->imageResizeMode('cover')
                            ->imageResizeTargetWidth(width: 1280)
                            ->imageResizeTargetHeight(720)
                            ->optimize('webp'),

                        Forms\Components\RichEditor::make('isi_case_study')
                            ->label('Konten Case Study')
                            ->required()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('case-study-attachments')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('thumbnail_case_study')
                    ->label('Thumbnail')
                    ->formatStateUsing(function ($record) {
                        $images = [];
                        $totalImages = 0;

                        if (is_array($record->thumbnail_case_study) && !empty($record->thumbnail_case_study)) {
                            $totalImages = count($record->thumbnail_case_study);

                            // Ambil maksimal 3 gambar untuk stack effect
                            $imagesToShow = array_slice($record->thumbnail_case_study, 0, 3);

                            foreach ($imagesToShow as $imagePath) {
                                $images[] = route('thumbnail', [
                                    'path' => base64_encode($imagePath),
                                    'w' => 80,
                                    'h' => 80,
                                    'q' => 80
                                ]);
                            }
                        }

                        return view('filament.tables.columns.image-stack-advanced', [
                            'images' => $images,
                            'total_images' => $totalImages,
                            'remaining_count' => max(0, $totalImages - 1)
                        ])->render();
                    })
                    ->html(),

                Tables\Columns\TextColumn::make('judul_case_study')
                    ->label('Judul')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('mitra.nama')
                    ->label('Mitra')
                    ->searchable(),

                Tables\Columns\SelectColumn::make('status_case_study')
                    ->label('Status')
                    ->options([
                        ContentStatus::TERPUBLIKASI->value => ContentStatus::TERPUBLIKASI->label(),
                        ContentStatus::TIDAK_TERPUBLIKASI->value => ContentStatus::TIDAK_TERPUBLIKASI->label(),
                    ])
                    ->rules(['required']),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                ,

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
                Tables\Filters\SelectFilter::make('id_mitra')
                    ->label('Mitra')
                    ->relationship('mitra', 'nama'),

                Tables\Filters\SelectFilter::make('status_case_study')
                    ->label('Status')
                    ->options([
                        ContentStatus::TERPUBLIKASI->value => ContentStatus::TERPUBLIKASI->label(),
                        ContentStatus::TIDAK_TERPUBLIKASI->value => ContentStatus::TIDAK_TERPUBLIKASI->label(),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('Arsipkan')
                    ->modalHeading('Arsipkan Case Study')
                    ->icon('heroicon-s-archive-box-arrow-down')
                    ->color('warning')
                    ->successNotificationTitle('Case study berhasil diarsipkan'),
                Tables\Actions\RestoreAction::make()
                    ->modalHeading('Pulihkan Case Study')
                    ->successNotificationTitle('Case study berhasil dipulihkan'),
                Tables\Actions\ForceDeleteAction::make()
                    ->label('hapus permanen')
                    ->modalHeading('Hapus Permanen Case Study')
                    ->successNotificationTitle('Case study berhasil dihapus permanen')
                    ->before(function ($record) {
                        MultipleFileHandler::deleteFiles($record, 'thumbnail_case_study');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotificationTitle('Case study berhasil diarsipkan'),
                    RestoreBulkAction::make()
                        ->successNotificationTitle('Case study berhasil dipulihkan'),
                    ForceDeleteBulkAction::make()
                        ->successNotificationTitle('Case study berhasil dihapus permanen')
                        ->before(function (Collection $records) {
                            MultipleFileHandler::deleteBulkFiles($records, 'thumbnail_case_study');
                        }),
                ]),
            ]);
    }


    public static function getWidgets(): array
    {
        return [
            CaseStudyStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCaseStudies::route('/'),
            'create' => Pages\CreateCaseStudy::route('/create'),
            'edit' => Pages\EditCaseStudy::route('/{record}/edit'),
        ];
    }
}
