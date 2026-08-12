<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    public const TEXTBOOK = 'textbook';
    public const TEACHER_HANDBOOK = 'teacher_handbook'; // ဆရာကိုင်
    public const TEACHER_GUIDE = 'teacher_guide';       // ဆရာလမ်းညွှန်

    protected $fillable = [
        'slug',
        'name_en',
        'name_mm',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function bookNames(): BelongsToMany
    {
        return $this->belongsToMany(BookName::class, 'grade_book_names')
            ->withPivot('grade_id')
            ->withTimestamps();
    }

    public static function fieldMap(): array
    {
        return [
            self::TEXTBOOK => [
                'field' => 'textbook_book_name_ids',
                'label' => 'ပြဋ္ဌာန်းစာအုပ်',
                'icon' => 'fa-book',
            ],
            self::TEACHER_HANDBOOK => [
                'field' => 'teacher_handbook_book_name_ids',
                'label' => 'ဆရာကိုင်',
                'icon' => 'fa-chalkboard-teacher',
            ],
            self::TEACHER_GUIDE => [
                'field' => 'teacher_guide_book_name_ids',
                'label' => 'ဆရာလမ်းညွှန်',
                'icon' => 'fa-book-reader',
            ],
        ];
    }
}
