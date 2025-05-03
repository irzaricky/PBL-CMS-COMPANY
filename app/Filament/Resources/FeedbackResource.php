<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeedbackResource\Pages;
use App\Filament\Resources\FeedbackResource\RelationManagers;
use App\Models\Feedback;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FeedbackResource extends Resource
{
    protected static ?string $model = Feedback::class;
    protected static ?string $navigationGroup = 'Customer Service';
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

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

                        Forms\Components\DatePicker::make('tanggal_feedback')
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
                    ->sortable(),

                Tables\Columns\TextColumn::make('subjek_feedback')
                    ->label('Subjek')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('tanggal_feedback')
                    ->label('Tanggal')
                    ->date('d F Y')
                    ->sortable(),

                Tables\Columns\IconColumn::make('tanggapan_feedback')
                    ->label('Ditanggapi')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->state(fn(Feedback $record): bool => !empty($record->tanggapan_feedback)),

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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListFeedback::route('/'),
            'create' => Pages\CreateFeedback::route('/create'),
            'edit' => Pages\EditFeedback::route('/{record}/edit'),
        ];
    }
}
