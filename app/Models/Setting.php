<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $guarded = [];

    public const CACHE_KEY = 'settings.all';

    /**
     * Memo untuk satu request. Satu halaman bisa memanggil setting() puluhan kali
     * (tiap tombol WhatsApp, link sosial media, teks hero), dan tanpa memo ini
     * setiap panggilan menembak cache store — yang pada driver database berarti
     * satu query MySQL per panggilan.
     *
     * @var array<string, string|null>|null
     */
    protected static ?array $memo = null;

    protected static function booted(): void
    {
        static::saved(fn () => static::flushCache());
        static::deleted(fn () => static::flushCache());
    }

    public static function flushCache(): void
    {
        static::$memo = null;
        Cache::forget(self::CACHE_KEY);
    }

    /** @return array<string, string|null> */
    public static function all_values(): array
    {
        return static::$memo ??= Cache::rememberForever(
            self::CACHE_KEY,
            fn () => static::query()->pluck('value', 'key')->all(),
        );
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $value = static::all_values()[$key] ?? null;

        return ($value === null || $value === '') ? $default : $value;
    }

    public static function put(string $key, mixed $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        static::flushCache();
    }

    /** @param  array<string, mixed>  $values */
    public static function putMany(array $values): void
    {
        foreach ($values as $key => $value) {
            static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }

        static::flushCache();
    }
}
