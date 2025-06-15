<?php

namespace App\Filament\Clusters\TestimoniCluster\Resources;

use App\Filament\Clusters\TestimoniCluster;
use App\Models\TestimoniProduk;
use App\Models\Produk;
use App\Models\User;
use App\Enums\ContentStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Resources\Components\Tab;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\TestimoniCluster\Resources\TestimoniProdukResource\Pages;
use App\Filament\Clusters\TestimoniCluster\Resources\TestimoniProdukResource\RelationManagers;

class TestimoniProdukResource extends Resource
{
    protected static ?string $model = TestimoniProduk::class;

    protected static ?string $navigationIcon = 'heroicon-s-shopping-bag';

    protected static ?string $navigationLabel = 'Testimoni Produk';
    protected static ?string $slug = 'testimoni-produk';

    protected static ?string $cluster = TestimoniCluster::class;

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Testimoni')
                    ->schema([
                        Forms\Components\Select::make('id_user')
                            ->label('User')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('id_produk')
                            ->label('Produk')
                            ->relationship('produk', 'nama_produk')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Textarea::make('isi_testimoni')
                            ->label('Isi Testimoni')
                            ->rows(5)
                            ->required()
                            ->columnSpanFull()
                            ->maxLength(255)
                            ->helperText('Maksimal 5 kata')
                            ->rules([
                                'required',
                                function ($attribute, $value, $fail) {
                                    $wordCount = str_word_count($value);
                                    if ($wordCount > 5) {
                                        $fail('Isi testimoni tidak boleh lebih dari 5 kata.');
                                    }
                                },
                            ]),

                        Forms\Components\Select::make('rating')
                            ->label('Rating')
                            ->options([
                                1 => '1 ⭐',
                                2 => '2 ⭐⭐',
                                3 => '3 ⭐⭐⭐',
                                4 => '4 ⭐⭐⭐⭐',
                                5 => '5 ⭐⭐⭐⭐⭐',
                            ])
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'tidak terpublikasi' => 'Tidak Terpublikasi',
                                'terpublikasi' => 'Terpublikasi',
                            ])
                            ->default('tidak terpublikasi')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('produk.nama_produk')
                    ->label('Produk')
                    ->limit(30)
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('isi_testimoni')
                    ->label('Testimoni')
                    ->limit(30)
                    ->searchable(),

                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(fn(string $state): string => str_repeat('⭐', (int) $state))
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        ContentStatus::TERPUBLIKASI->value => ContentStatus::TERPUBLIKASI->label(),
                        ContentStatus::TIDAK_TERPUBLIKASI->value => ContentStatus::TIDAK_TERPUBLIKASI->label(),
                    ])
                    ->disabled(fn() => !auth()->user()->can('update_testimoni::produk', TestimoniProduk::class))
                    ->rules(['required']),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'tidak terpublikasi' => 'Tidak Terpublikasi',
                        'terpublikasi' => 'Terpublikasi',
                    ]),

                Tables\Filters\SelectFilter::make('rating')
                    ->options([
                        1 => '1 ⭐',
                        2 => '2 ⭐⭐',
                        3 => '3 ⭐⭐⭐',
                        4 => '4 ⭐⭐⭐⭐',
                        5 => '5 ⭐⭐⭐⭐⭐',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approve')
                        ->label('Setujui')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['status' => 'terpublikasi']);
                            });
                        })
                        ->requiresConfirmation(),

                    Tables\Actions\BulkAction::make('reject')
                        ->label('Tolak')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['status' => 'tidak terpublikasi']);
                            });
                        })
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListTestimoniProduk::route('/'),
            'view' => Pages\ViewTestimoniProduk::route('/{record}'),
            'edit' => Pages\EditTestimoniProduk::route('/{record}/edit'),
        ];
    }
    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_testimoni::produk') ?? false;
    }
    public static function canCreate(): bool
    {
        return false;
    }

    public static function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua'),
            'terpublikasi' => Tab::make('Terpublikasi')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', ContentStatus::TERPUBLIKASI)),
            'tidak_terpublikasi' => Tab::make('Tidak Terpublikasi')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', ContentStatus::TIDAK_TERPUBLIKASI)),
        ];
    }
}
