<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreviousYearBalance extends Model
{
    protected $fillable = [
        'academic_year_id',
        'township_id',
        'grade_id',
        'book_name_id',
        'balance',
    ];

    protected $casts = [
        'balance' => 'integer',
    ];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function township(): BelongsTo
    {
        return $this->belongsTo(Township::class);
    }

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    public function bookName(): BelongsTo
    {
        return $this->belongsTo(BookName::class);
    }
}
