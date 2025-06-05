<?php

namespace App\Enums;

enum ContentStatus: string
{
    case TERPUBLIKASI = 'terpublikasi';
    case TIDAK_TERPUBLIKASI = 'tidak terpublikasi';

    public function label(): string
    {
        return match ($this) {
            self::TERPUBLIKASI => 'Terpublikasi',
            self::TIDAK_TERPUBLIKASI => 'Tidak Terpublikasi',
        };
    }
}