<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'academic_year_id',
        'township_id',
        'grade_id',
        'book_name_id',
        'previous_balance',
        'transferred',
        'enrolled_need',
        'required_qty',
        'remark',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
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
