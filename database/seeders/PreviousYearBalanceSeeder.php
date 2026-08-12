<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\BookName;
use App\Models\Grade;
use App\Models\PreviousYearBalance;
use App\Models\Township;
use Illuminate\Database\Seeder;

/**
 * ယခင်နှစ်လက်ကျန် — seeded separately for ခွဲတမ်းတွက်ချက်မှု Auto-fill.
 * Values = township remaining balances used by allocation plans.
 */
class PreviousYearBalanceSeeder extends Seeder
{
    public function run(): void
    {
        $year = AcademicYear::firstOrCreate(
            ['name' => '2025-2026'],
            [
                'start_year' => 2025,
                'end_year' => 2026,
                'is_active' => true,
                'is_current' => true,
                'status' => AcademicYear::STATUS_ACTIVE,
            ]
        );

        // Also ensure previous closed year exists for rollover demos
        AcademicYear::firstOrCreate(
            ['name' => '2024-2025'],
            [
                'start_year' => 2024,
                'end_year' => 2025,
                'is_active' => true,
                'is_current' => false,
                'status' => AcademicYear::STATUS_CLOSED,
            ]
        );

        $townships = [
            'မြန်အောင်' => Township::firstOrCreate(['name' => 'မြန်အောင်'], ['is_active' => true]),
            'ကြံခင်း' => Township::firstOrCreate(['name' => 'ကြံခင်း'], ['is_active' => true]),
            'အင်္ဂပူ' => Township::firstOrCreate(['name' => 'အင်္ဂပူ'], ['is_active' => true]),
        ];

        // [grade, subject, myanaung, kyankhin, ingapu]
        $rows = [
            ['KG', 'သင်္ချာအခြေခံ', 1881, 814, 1954],
            ['KG', 'ပုံဆွဲခြင်းနှင့် ဆေးခြယ်ခြင်း', 1881, 814, 1954],
            ['KG', 'သင်ယူမှုရလဒ်မှတ်တမ်း', 1881, 814, 1954],
            ['KG', 'ဗျည်းအက္ခရာကိန်းဂဏန်းတွေအရေးကျင့်ကြမယ်', 1881, 814, 1954],

            ['Grade-1', 'မြန်မာစာ', 315, 127, 296],
            ['Grade-1', 'အင်္ဂလိပ်စာ', 322, 115, 301],
            ['Grade-1', 'သင်္ချာ', 315, 127, 296],
            ['Grade-1', 'လူမှုရေး', 322, 115, 301],
            ['Grade-1', 'သိပ္ပံ', 322, 115, 301],
            ['Grade-1', 'စာရိတ္တနှင့်ပြည်သူ့နီတိ', 315, 127, 296],

            ['Grade-2', 'မြန်မာစာ', 350, 130, 445],
            ['Grade-2', 'အင်္ဂလိပ်စာ', 350, 130, 445],
            ['Grade-2', 'သင်္ချာ', 350, 130, 445],
            ['Grade-2', 'လူမှုရေး', 353, 125, 447],
            ['Grade-2', 'သိပ္ပံ', 350, 130, 445],
            ['Grade-2', 'စာရိတ္တနှင့်ပြည်သူ့နီတိ', 360, 120, 445],

            ['Grade-3', 'မြန်မာစာ', 413, 147, 421],
            ['Grade-3', 'အင်္ဂလိပ်စာ', 421, 139, 421],
            ['Grade-3', 'သင်္ချာ', 413, 147, 421],
            ['Grade-3', 'လူမှုရေး', 421, 139, 421],
            ['Grade-3', 'သိပ္ပံ', 421, 139, 421],
            ['Grade-3', 'စာရိတ္တနှင့်ပြည်သူ့နီတိ', 421, 139, 421],

            ['Grade-4', 'မြန်မာစာ', 176, 720, 1910],
            ['Grade-4', 'အင်္ဂလိပ်စာ', 176, 720, 1910],
            ['Grade-4', 'သင်္ချာ', 176, 720, 1910],
            ['Grade-4', 'လူမှုရေး', 176, 720, 1910],
            ['Grade-4', 'သိပ္ပံ', 176, 720, 1910],
            ['Grade-4', 'စာရိတ္တနှင့်ပြည်သူ့နီတိ', 176, 720, 1910],
            ['Grade-4', 'ဘဝတွက်တာကျွမ်းကျင်စရာ', 393, 105, 356],
            ['Grade-4', 'ကာယပညာ', 176, 720, 1910],
            ['Grade-4', 'အနုပညာ(ပန်းချီ)', 176, 720, 1910],
            ['Grade-4', 'အနုပညာ(ဂီတ)', 38, 830, 2107],

            ['Grade-5', 'မြန်မာစာ', 11, 853, 1387],
            ['Grade-5', 'အင်္ဂလိပ်စာ', 11, 853, 1387],
            ['Grade-5', 'သင်္ချာ', 11, 853, 1387],
            ['Grade-5', 'လူမှုရေး', 11, 853, 1387],
            ['Grade-5', 'သိပ္ပံ', 11, 853, 1387],
            ['Grade-5', 'စာရိတ္တနှင့်ပြည်သူ့နီတိ', 11, 853, 1387],
            ['Grade-5', 'ဘဝတွက်တာကျွမ်းကျင်စရာ', 441, 101, 477],
            ['Grade-5', 'ကာယပညာ', 11, 853, 1387],
            ['Grade-5', 'အနုပညာ(ပန်းချီ)', 11, 853, 1387],
            ['Grade-5', 'အနုပညာ(ဂီတ)', 11, 853, 1387],

            ['Grade-10', 'အင်္ဂလိပ်စာ', 89, 0, 0],
            ['Grade-10', 'ပထဝီဝင်', 176, 0, 0],
            ['Grade-10', 'သမိုင်း', 176, 0, 0],
            ['Grade-10', 'ဘောဂဗေဒ', 15, 0, 0],
            ['Grade-11', 'ပထဝီဝင်', 18, 0, 0],
            ['Grade-11', 'သမိုင်း', 18, 0, 0],
            ['Grade-12', 'ပထဝီဝင်', 4, 0, 0],
            ['Grade-12', 'သမိုင်း', 4, 0, 0],
            ['Grade-12', 'သိပ္ပံ(ဓာတု+ရူပ+ဇီဝ)', 12, 0, 0],
        ];

        foreach ($rows as [$gradeName, $subjectName, $mya, $kya, $ing]) {
            $grade = Grade::firstOrCreate(['name' => $gradeName], ['is_active' => true]);
            $book = BookName::firstOrCreate(['name' => $subjectName], ['is_active' => true]);

            $balances = [
                'မြန်အောင်' => $mya,
                'ကြံခင်း' => $kya,
                'အင်္ဂပူ' => $ing,
            ];

            foreach ($balances as $townshipName => $balance) {
                PreviousYearBalance::updateOrCreate(
                    [
                        'academic_year_id' => $year->id,
                        'township_id' => $townships[$townshipName]->id,
                        'grade_id' => $grade->id,
                        'book_name_id' => $book->id,
                    ],
                    ['balance' => (int) $balance]
                );
            }
        }
    }
}
