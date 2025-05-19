<?php

namespace App\Services\FileHandlers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class SingleFileHandler
{
    /**
     * Delete a single file from a record attribute
     */
    public static function deleteFile($record, string $attribute): void
    {
        $file = $record->$attribute;

        if (!empty($file) && Storage::disk('public')->exists($file)) {
            Storage::disk('public')->delete($file);
        }
    }

    /**
     * Delete files from multiple records
     */
    public static function deleteBulkFiles(Collection $records, string $attribute): void
    {
        foreach ($records as $record) {
            self::deleteFile($record, $attribute);
        }
    }

    /**
     * Handle removed file during update
     */
    public static function handleRemovedFile($record, array $formData, string $attribute): void
    {
        if (!$record) {
            return;
        }

        $oldFile = $record->getOriginal($attribute);
        $newFile = $formData[$attribute] ?? $record->$attribute;

        // Jika file baru secara eksplisit kosong, berarti user menghapus
        $isFileRemoved = array_key_exists($attribute, $formData) && empty($newFile);

        if (!empty($oldFile) && $isFileRemoved && Storage::disk('public')->exists($oldFile)) {
            Storage::disk('public')->delete($oldFile);
        }
    }


    /**
     * Format file data before save
     */
    public static function formatFileData(array $data, string $attribute): array
    {
        // No special formatting needed for single files, just return data
        return $data;
    }
}