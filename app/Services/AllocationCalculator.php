<?php

namespace App\Services;

class AllocationCalculator
{
    /**
     * Compute allocation plan totals and per-township breakdown.
     *
     * @param  array{
     *     received_books:int,
     *     books_per_package:int,
     *     myanaung_previous:int,
     *     kyankhin_previous:int,
     *     ingapu_previous:int,
     *     myanaung_total_students:int,
     *     kyankhin_total_students:int,
     *     ingapu_total_students:int,
     *     myanaung_transferable:int,
     *     kyankhin_transferable:int,
     *     ingapu_transferable:int
     * }  $input
     * @return array{plan: array<string, int|float>, detail: array<string, int>}
     */
    public function compute(array $input): array
    {
        $districts = ['myanaung', 'kyankhin', 'ingapu'];
        $unit = max(1, (int) ($input['books_per_package'] ?? 1));
        $received = (int) ($input['received_books'] ?? 0);

        $eligible = [];
        foreach ($districts as $district) {
            $previous = (int) ($input["{$district}_previous"] ?? 0);
            $students = (int) ($input["{$district}_total_students"] ?? 0);
            $transferable = (int) ($input["{$district}_transferable"] ?? 0);
            $eligible[$district] = $students - ($previous + $transferable);
        }

        $eligibleTotal = array_sum($eligible);
        $ratio = $eligibleTotal > 0 ? $received / $eligibleTotal : 0;

        $detail = [];
        $allocationTotal = 0;
        $studentTotal = 0;
        $transferableTotal = 0;
        $availableTotal = 0;
        $differenceTotal = 0;

        foreach ($districts as $district) {
            $previous = (int) ($input["{$district}_previous"] ?? 0);
            $students = (int) ($input["{$district}_total_students"] ?? 0);
            $transferable = (int) ($input["{$district}_transferable"] ?? 0);
            $allocation = (int) round($ratio * $eligible[$district]);
            $package = $unit > 0 ? intdiv($allocation, $unit) : 0;
            $loose = $unit > 0 ? $allocation % $unit : 0;
            $final = $previous + $allocation + $transferable;
            $difference = $final - $students;

            $detail["{$district}_students"] = $eligible[$district];
            $detail["{$district}_allocation"] = $allocation;
            $detail["{$district}_package"] = $package;
            $detail["{$district}_loose"] = $loose;
            $detail["{$district}_previous"] = $previous;
            $detail["{$district}_total_students"] = $students;
            $detail["{$district}_transferable"] = $transferable;
            $detail["{$district}_final"] = $final;
            $detail["{$district}_difference"] = $difference;

            $allocationTotal += $allocation;
            $studentTotal += $students;
            $transferableTotal += $transferable;
            $availableTotal += $final;
            $differenceTotal += $difference;
        }

        $detail['total_difference'] = $differenceTotal;

        return [
            'plan' => [
                'received_books' => $received,
                'books_per_package' => $unit,
                'ratio' => $ratio,
                'eligible_students_total' => $eligibleTotal,
                'allocated_books_total' => $allocationTotal,
                'student_count_total' => $studentTotal,
                'transferable_books_total' => $transferableTotal,
                'available_total' => $availableTotal,
                'surplus_shortage_total' => $differenceTotal,
            ],
            'detail' => $detail,
        ];
    }
}
