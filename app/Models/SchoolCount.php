<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolCount extends Model
{
    protected $fillable = [
        'academic_year_id',
        'grade_id',
        'township_id',
        'school_count',
    ];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    public function township(): BelongsTo
    {
        return $this->belongsTo(Township::class);
    }
}
