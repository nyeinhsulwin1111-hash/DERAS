<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolSupplyItem extends Model
{
    protected $fillable = [
        'name',
        'rate',
        'is_active',
    ];

    public function allocations()
    {
        return $this->hasMany(SchoolSupplyAllocation::class);
    }

    /** Real supply items only (exclude school-count marker rows). */
    public function scopeSupplyItems($query)
    {
        return $query->where('is_active', true)
            ->where('name', 'not like', 'ကျောင်းအရေအတွက်%');
    }

    public static function dropdownOptions()
    {
        return static::supplyItems()->orderBy('name')->get();
    }
}
