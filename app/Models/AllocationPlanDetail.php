<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllocationPlanDetail extends Model
{
    protected $fillable = [
        'allocation_plan_id',

        'myanaung_students',
        'kyankhin_students',
        'ingapu_students',

        'myanaung_allocation',
        'kyankhin_allocation',
        'ingapu_allocation',

        'myanaung_package',
        'myanaung_loose',

        'kyankhin_package',
        'kyankhin_loose',

        'ingapu_package',
        'ingapu_loose',

        'myanaung_previous',
        'kyankhin_previous',
        'ingapu_previous',

        'myanaung_transferable',
        'kyankhin_transferable',
        'ingapu_transferable',

        'myanaung_total_students',
        'kyankhin_total_students',
        'ingapu_total_students',

        'myanaung_final',
        'kyankhin_final',
        'ingapu_final',

        'myanaung_difference',
        'kyankhin_difference',
        'ingapu_difference',

        'total_difference',
    ];

    public function allocationPlan()
    {
        return $this->belongsTo(
            AllocationPlan::class
        );
    }
}
