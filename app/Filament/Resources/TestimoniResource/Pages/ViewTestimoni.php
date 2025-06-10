<?php

namespace App\Filament\Resources\TestimoniResource\Pages;

use App\Models\Testimoni;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\TestimoniResource;

class ViewTestimoni extends ViewRecord
{
    protected static string $resource = TestimoniResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Edit Testimoni'),
        ];
    }

    public function mount(int|string $record = null): void
    {
        // Ambil record pertama jika tidak ada ID yang diberikan
        if (!$record) {
            $firstRecord = Testimoni::first();
            if ($firstRecord) {
                $record = $firstRecord->getKey();
            }
        }

        parent::mount($record);
    }

    protected function resolveRecord(int|string $key): \Illuminate\Database\Eloquent\Model
    {
        // Jika tidak ada key, ambil record pertama
        if (!$key) {
            return Testimoni::firstOrFail();
        }

        return parent::resolveRecord($key);
    }
}