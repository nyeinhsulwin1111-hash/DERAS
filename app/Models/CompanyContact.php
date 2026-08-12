<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyContact extends Model
{
    protected $fillable = [
        'company_name',
        'lot',
        'responsible_name',
        'phone',
        'is_active',
    ];
}
