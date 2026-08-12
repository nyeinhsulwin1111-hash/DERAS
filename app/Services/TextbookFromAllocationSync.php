<?php

namespace App\Services;

use App\Models\AllocationPlan;
use App\Models\Textbook;
use App\Models\Township;

class TextbookFromAllocationSync
{
    /**
     * Township name keys used on allocation_plan_details.
     */
    private const TOWNSHIP_KEYS = [
        'မြန်အောင်' => 'myanaung',
        'ကြံခင်း' => 'kyankhin',
        'အင်္ဂပူ' => 'ingapu',
    ];

    /**
     * Push calculated per-township allocation quantities into ပုံမှန်ဖြန့်ဝေစာရင်း.
     * Auto fields: တစ်အိတ်ပါယူနစ် (books_per_set), ထုတ်ပေးသည့်အုပ်ရေ (student_count).
     */
    public function sync(AllocationPlan $plan): void
    {
        $plan->loadMissing('detail');

        if (!$plan->detail) {
            return;
        }

        $booksPerSet = (int) $plan->books_per_package;

        foreach (self::TOWNSHIP_KEYS as $townshipName => $key) {
            $township = Township::query()
                ->where('name', $townshipName)
                ->first();

            if (!$township) {
                continue;
            }

            $issuedQty = (int) ($plan->detail->{"{$key}_allocation"} ?? 0);

            Textbook::updateOrCreate(
                [
                    'academic_year_id' => $plan->academic_year_id,
                    'township_id' => $township->id,
                    'grade_id' => $plan->grade_id,
                    'book_name_id' => $plan->book_name_id,
                ],
                [
                    'books_per_set' => $booksPerSet,
                    'student_count' => $issuedQty,
                    'book_count' => $this->formatBagCount($issuedQty, $booksPerSet),
                    'remark' => $plan->remark,
                ]
            );
        }
    }

    private function formatBagCount(int $issuedQty, int $booksPerSet): string
    {
        if ($booksPerSet <= 0) {
            return (string) $issuedQty;
        }

        $full = intdiv($issuedQty, $booksPerSet);
        $loose = $issuedQty % $booksPerSet;

        return "{$full}အိတ်{$loose}အုပ်";
    }
}
