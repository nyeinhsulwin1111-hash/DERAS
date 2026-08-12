<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyDetail extends Model
{
    protected $fillable = [
        'academic_year_id',
        'township_id',
        'grade_id',
        'supply_item_id',
        'sequence_no',
        'unit',
        'issued_total',
        'package_count',
        'loose_count',
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

    public function item()
    {
        return $this->belongsTo(SupplyItem::class, 'supply_item_id');
    }
}
