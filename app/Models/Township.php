<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Township extends Model
{
    /** Real townships only (not district totals / summary labels). */
    public const REAL_NAMES = [
        'မြန်အောင်',
        'ကြံခင်း',
        'အင်္ဂပူ',
    ];

    public const EXCLUDED_NAMES = [
        'ခရိုင်အားလုံးစုစုပေါင်း',
    ];

    protected $fillable = ['name', 'is_active'];

    public function quotas()
    {
        return $this->hasMany(Quota::class);
    }

    /**
     * Only real townships — exclude district total labels.
     */
    public function scopeReal(Builder $query): Builder
    {
        return $query->whereNotIn('name', self::EXCLUDED_NAMES);
    }

    public function scopeActiveOrdered(Builder $query): Builder
    {
        return $query->real()
            ->where('is_active', true)
            ->orderByRaw("FIELD(name, 'မြန်အောင်', 'ကြံခင်း', 'အင်္ဂပူ')")
            ->orderBy('name');
    }

    public static function dropdownOptions()
    {
        return static::activeOrdered()->get();
    }
}
