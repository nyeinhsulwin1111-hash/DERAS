<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\BookName;
use App\Models\Grade;
use App\Models\Stock;
use App\Models\Township;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    public function run(): void
    {
        $year = AcademicYear::firstOrCreate(
            ['name' => '2025-2026'],
            ['is_active' => true]
        );

        $township = Township::firstOrCreate(
            ['name' => 'မြန်အောင်'],
            ['is_active' => true]
        );

        $rows = [
            ['Grade-10', 'အင်္ဂလိပ်စာ', 89, 176, 0, 89],
            ['Grade-10', 'ပထဝီဝင်', 176, 176, 0, 176],
            ['Grade-10', 'သမိုင်း', 176, 176, 0, 176],
            ['Grade-10', 'ဘောဂဗေဒ', 15, 0, 0, 15],
            ['Grade-11', 'ပထဝီဝင်', 18, 0, 0, 18],
            ['Grade-11', 'သမိုင်း', 18, 0, 0, 18],
            ['Grade-12', 'ပထဝီဝင်', 4, 0, 0, 4],
            ['Grade-12', 'သမိုင်း', 4, 0, 0, 4],
            ['Grade-12', 'သိပ္ပံ(ဇီဝ+ရူပ+ဓာ)', 12, 0, 0, 12],
        ];

        foreach ($rows as $row) {
            $grade = Grade::firstOrCreate(
                ['name' => $row[0]],
                ['is_active' => true]
            );

            $bookName = BookName::firstOrCreate(
                ['name' => $row[1]],
                ['is_active' => true]
            );

            Stock::updateOrCreate(
                [
                    'academic_year_id' => $year->id,
                    'township_id' => $township->id,
                    'grade_id' => $grade->id,
                    'book_name_id' => $bookName->id,
                ],
                [
                    'previous_balance' => $row[2],
                    'transferred' => $row[3],
                    'enrolled_need' => $row[4],
                    'required_qty' => $row[5],
                    'remark' => null,
                ]
            );
        }
    }
}
