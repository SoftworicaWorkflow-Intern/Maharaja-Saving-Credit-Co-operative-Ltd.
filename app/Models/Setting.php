<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    public $timestamps = false;

    public static function value(string $key, mixed $default = null): mixed
    {
        if (! Schema::hasTable((new static)->getTable())) {
            return $default;
        }

        return static::query()->where('key', $key)->value('value') ?? $default;
    }

    public static function values(): array
    {
        if (! Schema::hasTable((new static)->getTable())) {
            return [];
        }

        return static::query()->pluck('value', 'key')->all();
    }
}
