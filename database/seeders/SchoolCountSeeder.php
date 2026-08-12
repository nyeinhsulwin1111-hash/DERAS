<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\SchoolCount;
use App\Models\SchoolSupplyItem;
use App\Models\Township;
use Illuminate\Database\Seeder;

/**
 * School Count (ကျောင်းအရေအတွက်) — dedicated table, not supply items.
 */
class SchoolCountSeeder extends Seeder
{
    public function run(): void
    {
        // Hide old fake items that polluted the ပစ္စည်းများ dropdown
        SchoolSupplyItem::where('name', 'like', 'ကျောင်းအရေအတွက်%')
            ->update(['is_active' => false]);

        $yearNames = ['2024-2025', '2025-2026'];

        $counts = [
            'KG' => ['မြန်အောင်' => 272, 'ကြံခင်း' => 124, 'အင်္ဂပူ' => 279],
            'Grade-1' => ['မြန်အောင်' => 272, 'ကြံခင်း' => 124, 'အင်္ဂပူ' => 279],
            'Grade-2' => ['မြန်အောင်' => 272, 'ကြံခင်း' => 124, 'အင်္ဂပူ' => 279],
            'Grade-3' => ['မြန်အောင်' => 272, 'ကြံခင်း' => 124, 'အင်္ဂပူ' => 279],
            'Grade-4' => ['မြန်အောင်' => 272, 'ကြံခင်း' => 124, 'အင်္ဂပူ' => 279],
            'Grade-5' => ['မြန်အောင်' => 272, 'ကြံခင်း' => 124, 'အင်္ဂပူ' => 279],
        ];

        foreach ($yearNames as $yearName) {
            $year = AcademicYear::firstOrCreate(
                ['name' => $yearName],
                [
                    'is_active' => true,
                    'start_year' => (int) substr($yearName, 0, 4),
                    'end_year' => (int) substr($yearName, 5, 4),
                ]
            );

            foreach ($counts as $gradeName => $townships) {
                $grade = Grade::firstOrCreate(
                    ['name' => $gradeName],
                    ['is_active' => true]
                );

                $districtTotal = 0;

                foreach ($townships as $townshipName => $schoolCount) {
                    $township = Township::firstOrCreate(
                        ['name' => $townshipName],
                        ['is_active' => true]
                    );

                    SchoolCount::updateOrCreate(
                        [
                            'academic_year_id' => $year->id,
                            'grade_id' => $grade->id,
                            'township_id' => $township->id,
                        ],
                        ['school_count' => $schoolCount]
                    );

                    $districtTotal += $schoolCount;
                }

                // မြို့နယ်အားလုံး / ခရိုင်စုစုပေါင်း
                SchoolCount::updateOrCreate(
                    [
                        'academic_year_id' => $year->id,
                        'grade_id' => $grade->id,
                        'township_id' => null,
                    ],
                    ['school_count' => $districtTotal]
                );
            }
        }
    }
}
