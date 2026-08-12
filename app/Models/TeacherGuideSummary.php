<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherGuideSummary extends Model
{
    protected $fillable = [
        'academic_year_id',
        'grade_id',
        'book_name_id',
        'group_no',
        'group_title',
        'guide_type',
        'sequence_no',
        'previous_balance',
        'fiscal_year_quota',
        'total_books',
        'distributed_books',
        'remaining_books',
        'remark',
    ];

    protected $casts = [
        'group_no' => 'integer',
        'sequence_no' => 'integer',
        'previous_balance' => 'integer',
        'fiscal_year_quota' => 'integer',
        'total_books' => 'integer',
        'distributed_books' => 'integer',
        'remaining_books' => 'integer',
    ];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
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
