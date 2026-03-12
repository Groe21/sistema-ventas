<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessSetting extends Model
{
    protected $fillable = ['business_id', 'key', 'value'];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public static function getValue(int $businessId, string $key, $default = null): ?string
    {
        return static::where('business_id', $businessId)
            ->where('key', $key)
            ->value('value') ?? $default;
    }

    public static function setValue(int $businessId, string $key, ?string $value): void
    {
        static::updateOrCreate(
            ['business_id' => $businessId, 'key' => $key],
            ['value' => $value]
        );
    }

    public static function getMany(int $businessId, array $keys): array
    {
        $settings = static::where('business_id', $businessId)
            ->whereIn('key', $keys)
            ->pluck('value', 'key')
            ->toArray();

        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $settings[$key] ?? null;
        }
        return $result;
    }
}
