<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    // Save JSON-able values consistently
    public static function set(string $key, $value): void
    {
        $payload = is_scalar($value) ? (string)$value : json_encode($value, JSON_UNESCAPED_UNICODE);
        static::updateOrCreate(['key' => $key], ['value' => $payload]);
        Cache::forget('setting_' . $key);
    }

    public static function get(string $key, $default = null)
    {
        return Cache::rememberForever('setting_' . $key, function () use ($key, $default) {
            $s = static::where('key', $key)->first();
            if (! $s || $s->value === null) return $default;
            $v = $s->value;
            // try to decode JSON; if fails, return string
            $decoded = json_decode($v, true);
            return json_last_error() === JSON_ERROR_NONE ? $decoded : $v;
        });
    }

    public static function forget(string $key): void
    {
        static::where('key', $key)->delete();
        Cache::forget('setting_' . $key);
    }
}