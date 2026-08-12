<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookName extends Model
{
    protected $fillable = ['name', 'is_active'];

    public function textbooks()
    {
        return $this->hasMany(Textbook::class);
    }

    public function grades()
    {
        return $this->belongsToMany(Grade::class, 'grade_book_names')
            ->withPivot('category_id')
            ->withTimestamps();
    }
}
