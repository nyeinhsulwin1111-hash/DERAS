<?php

namespace App\Models;

use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\SchoolSupplyItem;
use App\Models\Township;
use Illuminate\Database\Eloquent\Model;

class SchoolSupplyAllocation extends Model
{
    protected $fillable = [
        'academic_year_id',
        'grade_id',
        'township_id',
        'school_supply_item_id',
        'region',
        'row_type',
        'row_label',
        'school_count',
        'quantity',
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

    public function township()
    {
        return $this->belongsTo(Township::class);
    }

    public function item()
    {
        return $this->belongsTo(SchoolSupplyItem::class, 'school_supply_item_id');
    }
}
