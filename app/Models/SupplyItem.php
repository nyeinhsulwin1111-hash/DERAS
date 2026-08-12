<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyItem extends Model
{
    protected $fillable = [
        'name',
        'is_active',
    ];

    public function details()
    {
        return $this->hasMany(SupplyDetail::class);
    }
}
