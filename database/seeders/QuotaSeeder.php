<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Quota;
use App\Models\Township;
use Illuminate\Database\Seeder;

class QuotaSeeder extends Seeder
{
    public function run(): void
    {
        $academicYear = AcademicYear::where('name', '2025-2026')->firstOrFail();

        $rows = [
            [
                'township' => 'မြန်အောင်',
                'primary_public' => 23368,
                'primary_monk' => 206,
                'primary_private' => 0,
                'primary_total' => 23574,
                'middle_public' => 7261,
                'middle_monk' => 58,
                'middle_private' => 0,
                'middle_total' => 7319,
                'high_public' => 3294,
                'high_monk' => 0,
                'high_private' => 0,
                'high_total' => 3294,
                'grand_public' => 33923,
                'grand_monk' => 264,
                'grand_private' => 0,
                'grand_total' => 34187,
                'agriculture' => 0,
                'total_with_agriculture' => 34187,
                'distribution_total' => 34187,
            ],
            [
                'township' => 'ကြံခင်း',
                'primary_public' => 8666,
                'primary_monk' => 122,
                'primary_private' => 0,
                'primary_total' => 8788,
                'middle_public' => 3218,
                'middle_monk' => 0,
                'middle_private' => 0,
                'middle_total' => 3218,
                'high_public' => 1526,
                'high_monk' => 0,
                'high_private' => 95,
                'high_total' => 1621,
                'grand_public' => 13410,
                'grand_monk' => 122,
                'grand_private' => 95,
                'grand_total' => 13627,
                'agriculture' => 128,
                'total_with_agriculture' => 13755,
                'distribution_total' => 13660,
            ],
            [
                'township' => 'အင်္ဂပူ',
                'primary_public' => 21680,
                'primary_monk' => 218,
                'primary_private' => 0,
                'primary_total' => 21898,
                'middle_public' => 7155,
                'middle_monk' => 3,
                'middle_private' => 15,
                'middle_total' => 7173,
                'high_public' => 3794,
                'high_monk' => 0,
                'high_private' => 24,
                'high_total' => 3818,
                'grand_public' => 32629,
                'grand_monk' => 221,
                'grand_private' => 39,
                'grand_total' => 32889,
                'agriculture' => 0,
                'total_with_agriculture' => 32889,
                'distribution_total' => 32850,
            ],
        ];

        foreach ($rows as $row) {
            $township = Township::where('name', $row['township'])->firstOrFail();

            unset($row['township']);

            Quota::updateOrCreate(
                [
                    'academic_year_id' => $academicYear->id,
                    'township_id' => $township->id,
                ],
                $row
            );
        }
    }
}
