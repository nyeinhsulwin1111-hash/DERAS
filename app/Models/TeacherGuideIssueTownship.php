<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherGuideIssueTownship extends Model
{
    protected $fillable = [
        'teacher_guide_issue_id','township_id','issued_quantity',
        'full_package_count','loose_book_count',
    ];

    protected $casts = [
        'issued_quantity' => 'integer','full_package_count' => 'integer',
        'loose_book_count' => 'integer',
    ];

    public function issue(): BelongsTo { return $this->belongsTo(TeacherGuideIssue::class, 'teacher_guide_issue_id'); }
    public function township(): BelongsTo { return $this->belongsTo(Township::class); }
}
