<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherGuide extends Model
{
    protected $fillable = [
        'academic_year_id',
        'grade_id',
        'book_name_id',

        'group_no',
        'group_title',
        'guide_type',
        'sequence_no',

        'kg_to_g12_quota',
        'g1_to_g5_quota',
        'total_quota',

        'kg_g12_myanaung_qty',
        'kg_g12_kyankhin_qty',
        'kg_g12_ingapu_qty',

        'g1_g5_myanaung_qty',
        'g1_g5_kyankhin_qty',
        'g1_g5_ingapu_qty',

        'total_myanaung_qty',
        'total_kyankhin_qty',
        'total_ingapu_qty',

        'distributed_total',
        'remaining_total',

        'remark',
    ];

    protected $casts = [
        'kg_to_g12_quota' => 'integer',
        'g1_to_g5_quota' => 'integer',
        'total_quota' => 'integer',

        'kg_g12_myanaung_qty' => 'integer',
        'kg_g12_kyankhin_qty' => 'integer',
        'kg_g12_ingapu_qty' => 'integer',

        'g1_g5_myanaung_qty' => 'integer',
        'g1_g5_kyankhin_qty' => 'integer',
        'g1_g5_ingapu_qty' => 'integer',

        'total_myanaung_qty' => 'integer',
        'total_kyankhin_qty' => 'integer',
        'total_ingapu_qty' => 'integer',

        'distributed_total' => 'integer',
        'remaining_total' => 'integer',
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
}
