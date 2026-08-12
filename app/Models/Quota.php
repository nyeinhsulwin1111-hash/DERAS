<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quota extends Model
{
    protected $fillable = [
        'academic_year_id',
        'township_id',

        'primary_public',
        'primary_monk',
        'primary_private',
        'primary_total',

        'middle_public',
        'middle_monk',
        'middle_private',
        'middle_total',

        'high_public',
        'high_monk',
        'high_private',
        'high_total',

        'grand_public',
        'grand_monk',
        'grand_private',
        'grand_total',

        'agriculture',
        'total_with_agriculture',
        'distribution_total',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function township()
    {
        return $this->belongsTo(Township::class);
    }
}
