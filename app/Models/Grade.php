<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = ['name', 'is_active'];

    /**
     * Natural school order: KG → Grade-1 → … → Grade-12
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByRaw("CASE
            WHEN name = 'KG' THEN 0
            WHEN name LIKE 'Grade-%' THEN CAST(SUBSTRING(name, 7) AS UNSIGNED)
            WHEN name LIKE 'Grade %' THEN CAST(SUBSTRING(name, 7) AS UNSIGNED)
            ELSE 999
        END")->orderBy('name');
    }

    /**
     * Active grades in natural order (for all dropdowns).
     */
    public function scopeActiveOrdered(Builder $query): Builder
    {
        return $query->where('is_active', true)->ordered();
    }

    public static function dropdownOptions()
    {
        return static::activeOrdered()->get();
    }

    public function textbooks()
    {
        return $this->hasMany(Textbook::class);
    }

    public function bookNames()
    {
        return $this->belongsToMany(BookName::class, 'grade_book_names')
            ->withPivot('category_id')
            ->withTimestamps();
    }
}
