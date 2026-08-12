<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'slug' => Category::TEXTBOOK,
                'name_en' => 'Textbooks',
                'name_mm' => 'ပြဋ္ဌာန်းစာအုပ်',
            ],
            [
                'slug' => Category::TEACHER_HANDBOOK,
                'name_en' => 'Teacher Handbook',
                'name_mm' => 'ဆရာကိုင်',
            ],
            [
                'slug' => Category::TEACHER_GUIDE,
                'name_en' => 'Teacher Guide',
                'name_mm' => 'ဆရာလမ်းညွှန်',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                [
                    'name_en' => $category['name_en'],
                    'name_mm' => $category['name_mm'],
                    'is_active' => true,
                ]
            );
        }
    }
}
