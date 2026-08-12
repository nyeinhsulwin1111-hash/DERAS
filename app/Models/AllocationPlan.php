<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllocationPlan extends Model
{
    protected $fillable = [
        'academic_year_id',
        'grade_id',
        'book_name_id',

        'sequence_no',

        'received_books',
        'books_per_package',
        'ratio',

        'eligible_students_total',
        'allocated_books_total',
        'student_count_total',
        'transferable_books_total',
        'available_total',
        'surplus_shortage_total',

        'remark',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function bookName()
    {
        return $this->belongsTo(BookName::class);
    }

    public function detail()
    {
        return $this->hasOne(
            AllocationPlanDetail::class
        );
    }
}
