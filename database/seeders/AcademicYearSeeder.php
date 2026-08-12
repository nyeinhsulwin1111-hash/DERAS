<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use Illuminate\Database\Seeder;

class AcademicYearSeeder extends Seeder
{
    public function run(): void
    {
        // Keep from 2024-2025 onward (older closed years are not seeded).
        for ($year = 2024; $year <= 2030; $year++) {
            $name = $year . '-' . ($year + 1);
            $isCurrent = $name === '2025-2026';

            AcademicYear::updateOrCreate(
                ['name' => $name],
                [
                    'start_year' => $year,
                    'end_year' => $year + 1,
                    'is_active' => true,
                    'is_current' => $isCurrent,
                    'status' => $year < 2025
                        ? AcademicYear::STATUS_CLOSED
                        : AcademicYear::STATUS_ACTIVE,
                ]
            );
        }

        // Ensure only one current
        AcademicYear::where('name', '!=', '2025-2026')->update(['is_current' => false]);
        AcademicYear::where('name', '2025-2026')->update([
            'is_current' => true,
            'status' => AcademicYear::STATUS_ACTIVE,
        ]);
    }
}
