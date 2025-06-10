<?php

namespace App\Helpers;

use App\Models\ProfilPerusahaan;
use Illuminate\Support\Facades\Cache;

class ThemeHelper
{
    /**
     * Get current company theme color
     */
    public static function getCompanyThemeColor(): string
    {
        return Cache::remember('company_theme_color', 3600, function () {
            $profil = ProfilPerusahaan::first();
            return $profil?->tema_perusahaan ?? '#31487A'; // Default to YlnMn Blue
        });
    }

    /**
     * Get Filament theme configuration
     */
    public static function getFilamentTheme(): array
    {
        $primaryColor = self::getCompanyThemeColor();
        
        return [
            'primary' => self::generateColorPalette($primaryColor),
        ];
    }

    /**
     * Generate color palette from hex color
     */
    private static function generateColorPalette(string $hexColor): array
    {
        // Remove # if present
        $hex = ltrim($hexColor, '#');
        
        // Convert hex to RGB
        $rgb = [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2))
        ];

        return [
            50 => self::lighten($rgb, 0.95),
            100 => self::lighten($rgb, 0.9),
            200 => self::lighten($rgb, 0.8),
            300 => self::lighten($rgb, 0.6),
            400 => self::lighten($rgb, 0.3),
            500 => $hexColor, // Base color
            600 => self::darken($rgb, 0.1),
            700 => self::darken($rgb, 0.2),
            800 => self::darken($rgb, 0.3),
            900 => self::darken($rgb, 0.4),
            950 => self::darken($rgb, 0.5),
        ];
    }

    /**
     * Lighten a color
     */
    private static function lighten(array $rgb, float $amount): string
    {
        $r = min(255, $rgb['r'] + (255 - $rgb['r']) * $amount);
        $g = min(255, $rgb['g'] + (255 - $rgb['g']) * $amount);
        $b = min(255, $rgb['b'] + (255 - $rgb['b']) * $amount);
        
        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }

    /**
     * Darken a color
     */
    private static function darken(array $rgb, float $amount): string
    {
        $r = max(0, $rgb['r'] * (1 - $amount));
        $g = max(0, $rgb['g'] * (1 - $amount));
        $b = max(0, $rgb['b'] * (1 - $amount));
        
        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }

    /**
     * Clear theme cache
     */
    public static function clearThemeCache(): void
    {
        Cache::forget('company_theme_color');
    }
}