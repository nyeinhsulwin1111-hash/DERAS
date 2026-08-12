<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Quota;
use App\Models\Township;
use Illuminate\Http\Request;

class QuotaController extends Controller
{
    public function index(Request $request)
    {
        $years = AcademicYear::where('is_active', true)
            ->orderBy('name')
            ->get();

        $academicYearId = $request->get('academic_year_id')
            ?? AcademicYear::where('name', '2025-2026')->value('id')
            ?? $years->last()?->id;

        $selectedYear = AcademicYear::find($academicYearId);

        $quotaQuery = Quota::with(['academicYear', 'township'])
            ->where('academic_year_id', $academicYearId);

        $rows = $quotaQuery
            ->orderBy('id')
            ->get()
            ->map(function ($quota) {
                return [
                    'id' => $quota->id,
                    'academic_year' => $quota->academicYear?->name,
                    'township' => $quota->township?->name,

                    'primary_public' => $quota->primary_public,
                    'primary_monk' => $quota->primary_monk,
                    'primary_private' => $quota->primary_private,
                    'primary_total' => $quota->primary_total,

                    'middle_public' => $quota->middle_public,
                    'middle_monk' => $quota->middle_monk,
                    'middle_private' => $quota->middle_private,
                    'middle_total' => $quota->middle_total,

                    'high_public' => $quota->high_public,
                    'high_monk' => $quota->high_monk,
                    'high_private' => $quota->high_private,
                    'high_total' => $quota->high_total,

                    'grand_public' => $quota->grand_public,
                    'grand_monk' => $quota->grand_monk,
                    'grand_private' => $quota->grand_private,
                    'grand_total' => $quota->grand_total,

                    'agriculture' => $quota->agriculture,
                    'total_with_agriculture' => $quota->total_with_agriculture,
                    'distribution_total' => $quota->distribution_total,
                ];
            });

        $query = Quota::where('academic_year_id', $academicYearId);

        $totals = [
            'primary_public' => (clone $query)->sum('primary_public'),
            'primary_monk' => (clone $query)->sum('primary_monk'),
            'primary_private' => (clone $query)->sum('primary_private'),
            'primary_total' => (clone $query)->sum('primary_total'),

            'middle_public' => (clone $query)->sum('middle_public'),
            'middle_monk' => (clone $query)->sum('middle_monk'),
            'middle_private' => (clone $query)->sum('middle_private'),
            'middle_total' => (clone $query)->sum('middle_total'),

            'high_public' => (clone $query)->sum('high_public'),
            'high_monk' => (clone $query)->sum('high_monk'),
            'high_private' => (clone $query)->sum('high_private'),
            'high_total' => (clone $query)->sum('high_total'),

            'grand_public' => (clone $query)->sum('grand_public'),
            'grand_monk' => (clone $query)->sum('grand_monk'),
            'grand_private' => (clone $query)->sum('grand_private'),
            'grand_total' => (clone $query)->sum('grand_total'),

            'agriculture' => (clone $query)->sum('agriculture'),
            'total_with_agriculture' => (clone $query)->sum('total_with_agriculture'),
            'distribution_total' => (clone $query)->sum('distribution_total'),
        ];

        $academicYear = $selectedYear?->name ?? '2025-2026';

        return view('quota.index', compact(
            'rows',
            'totals',
            'years',
            'academicYear',
            'academicYearId'
        ));
    }

    public function create()
    {
        $years = AcademicYear::where('is_active', true)->orderBy('name')->get();
        $townships = Township::dropdownOptions();

        return view('quota.create', compact('years', 'townships'));
    }

    public function store(Request $request)
    {
        Quota::create($this->validatedData($request));

        return redirect()->route('quota.index')->with('success', 'အောင်မြင်စွာဖန်တီးပြီးပါပြီ');
    }

    public function edit($id)
    {
        $quota = Quota::findOrFail($id);
        $years = AcademicYear::where('is_active', true)->orderBy('name')->get();
        $townships = Township::dropdownOptions();
        return view('quota.edit', compact('quota', 'years', 'townships'));
    }

    public function update(Request $request, $id)
    {
        $quota = Quota::findOrFail($id);
        $quota->update($request->all());

        return redirect()->route('quota.index')->with('success', 'အောင်မြင်စွာပြင်ဆင်ပြီးပါပြီ');
    }

    public function destroy($id)
    {
        Quota::findOrFail($id)->delete();

        return redirect()->route('quota.index')->with('success', 'အောင်မြင်စွာဖျက်လိုက်ပါပြီ');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'township_id' => 'required|exists:townships,id',

            'primary_public' => 'nullable|integer|min:0',
            'primary_monk' => 'nullable|integer|min:0',
            'primary_private' => 'nullable|integer|min:0',
            'primary_total' => 'nullable|integer|min:0',

            'middle_public' => 'nullable|integer|min:0',
            'middle_monk' => 'nullable|integer|min:0',
            'middle_private' => 'nullable|integer|min:0',
            'middle_total' => 'nullable|integer|min:0',

            'high_public' => 'nullable|integer|min:0',
            'high_monk' => 'nullable|integer|min:0',
            'high_private' => 'nullable|integer|min:0',
            'high_total' => 'nullable|integer|min:0',

            'grand_public' => 'nullable|integer|min:0',
            'grand_monk' => 'nullable|integer|min:0',
            'grand_private' => 'nullable|integer|min:0',
            'grand_total' => 'nullable|integer|min:0',

            'agriculture' => 'nullable|integer|min:0',
            'total_with_agriculture' => 'nullable|integer|min:0',
            'distribution_total' => 'nullable|integer|min:0',
        ]);
    }
}
