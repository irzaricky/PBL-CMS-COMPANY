<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Feedback;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\FeedbackResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FeedbackResource\RelationManagers;
use App\Filament\Resources\FeedbackResource\Widgets\FeedbackStats;
use App\Helpers\FilamentGroupingHelper;

class FeedbackResource extends Resource
{
    protected static ?string $model = Feedback::class;
    protected static ?string $navigationIcon = 'heroicon-s-chat-bubble-left-right';

    public static function getNavigationGroup(): ?string
    {
        return FilamentGroupingHelper::getNavigationGroup('Customer Service');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Feedback')
                    ->schema([
                        Forms\Components\Select::make('id_user')
                            ->label('Pengguna')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->disabled(),

                        Forms\Components\TextInput::make('subjek_feedback')
                            ->label('Subjek')
                            ->required()
                            ->maxLength(200)
                            ->disabled(),

                        Forms\Components\DatePicker::make('created_at')
                            ->label('Tanggal Feedback')
                            ->required()
                            ->default(now())
                            ->displayFormat('d F Y')
                            ->disabled(),

                        Forms\Components\RichEditor::make('isi_feedback')
                            ->label('Isi Feedback')
                            ->required()
                            ->columnSpanFull()
                            ->disabled(),
                    ]),

                Forms\Components\Section::make('Tanggapan Admin')
                    ->schema([
                        Forms\Components\RichEditor::make('tanggapan_feedback')
                            ->label('Tanggapan')
                            ->placeholder('Masukkan tanggapan untuk feedback ini')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->searchable()
                ,

                Tables\Columns\TextColumn::make('subjek_feedback')
                    ->label('Subjek')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Feedback')
                    ->date('d F Y')
                ,

                Tables\Columns\IconColumn::make('tanggapan_feedback')
                    ->label('Ditanggapi')
                    ->alignCenter()
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->state(fn(Feedback $record): bool => !empty($record->tanggapan_feedback)),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_tanggapan')
                    ->label('Status Tanggapan')
                    ->options([
                        'responded' => 'Sudah Ditanggapi',
                        'pending' => 'Belum Ditanggapi',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['value']) {
                            'responded' => $query->whereNotNull('tanggapan_feedback'),
                            'pending' => $query->whereNull('tanggapan_feedback'),
                            default => $query,
                        };
                    }),

                Tables\Filters\SelectFilter::make('id_user')
                    ->label('Pengguna')
                    ->relationship('user', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            FeedbackStats::class,
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeedback::route('/'),
            'create' => Pages\CreateFeedback::route('/create'),
            'edit' => Pages\EditFeedback::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        /** @var class-string<Model> $modelClass */
        $modelClass = static::$model;

        return (string) $modelClass::whereNull('tanggapan_feedback')->count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        /** @var class-string<Model> $modelClass */
        $modelClass = static::$model;

        return (string) 'Belum Ditanggapi';
    }
}
