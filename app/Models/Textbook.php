<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Textbook extends Model
{
    protected $fillable = [
        'academic_year_id',
        'township_id',
        'grade_id',
        'book_name_id',
        'books_per_set',
        'student_count',
        'book_count',
        'remark',
    ];

    public function year()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function township()
    {
        return $this->belongsTo(Township::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function bookName()
    {
        return $this->belongsTo(BookName::class);
    }
}
