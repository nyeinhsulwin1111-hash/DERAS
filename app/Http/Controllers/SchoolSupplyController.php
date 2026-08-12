<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\SchoolSupplyAllocation;
use App\Models\SchoolSupplyItem;
use App\Models\Township;
use Illuminate\Http\Request;

class SchoolSupplyController extends Controller
{
    public function index(Request $request)
    {
        $yearId = $request->academic_year_id;
        $gradeId = $request->grade_id;
        // Empty = မြို့နယ်အားလုံး (default) → show district totals only
        $townshipId = $request->input('township_id', '');

        $query = SchoolSupplyAllocation::with([
            'academicYear',
            'grade',
            'township',
            'item',
        ]);

        if ($yearId) {
            $query->where('academic_year_id', $yearId);
        }

        if ($gradeId) {
            $query->where('grade_id', $gradeId);
        }

        if ($townshipId !== '' && $townshipId !== null) {
            // Specific township selected
            $query->where('township_id', $townshipId)
                ->where('row_type', 'township')
                ->whereNotIn('row_label', Township::EXCLUDED_NAMES);
        } else {
            // မြို့နယ်အားလုံး → ခရိုင်စုစုပေါင်း only
            $query->where(function ($q) {
                $q->where('row_type', 'total')
                    ->orWhereIn('row_label', Township::EXCLUDED_NAMES)
                    ->orWhere('row_label', 'like', '%ခရိုင်%စုစုပေါင်း%');
            });
        }

        $allocations = $query
            ->orderBy('grade_id')
            ->orderBy('row_type')
            ->orderBy('row_label')
            ->orderBy('school_supply_item_id')
            ->get();

        $years = AcademicYear::where('is_active', true)->orderBy('name')->get();
        $grades = Grade::dropdownOptions();
        $townships = Township::dropdownOptions();
        $items = SchoolSupplyItem::dropdownOptions();

        $selectedYear = $yearId
            ? AcademicYear::find($yearId)
            : null;

        return view('school-supplies.index', compact(
            'allocations',
            'years',
            'grades',
            'townships',
            'items',
            'yearId',
            'gradeId',
            'townshipId',
            'selectedYear'
        ));
    }

    public function create()
    {
        return view('school-supplies.create', [
            'years' => AcademicYear::where('is_active', true)->orderBy('name')->get(),
            'grades' => Grade::dropdownOptions(),
            'townships' => Township::dropdownOptions(),
            'items' => SchoolSupplyItem::dropdownOptions(),
        ]);
    }

    public function store(Request $request)
    {
        SchoolSupplyAllocation::create($this->validatedData($request));

        return redirect()->route('school-supplies.index')
            ->with('success', 'အောင်မြင်စွာဖန်တီးပြီးပါပြီ.');
    }

    public function edit(SchoolSupplyAllocation $schoolSupply)
    {
        $schoolSupply->load('item');

        return view('school-supplies.edit', [
            'schoolSupply' => $schoolSupply,
            'years' => AcademicYear::where('is_active', true)->orderBy('name')->get(),
            'grades' => Grade::dropdownOptions(),
            'townships' => Township::dropdownOptions(),
            'items' => SchoolSupplyItem::dropdownOptions(),
        ]);
    }

    public function update(Request $request, SchoolSupplyAllocation $schoolSupply)
    {
        $schoolSupply->update($this->validatedData($request));

        return redirect()->route('school-supplies.index')
            ->with('success', 'အောင်မြင်စွာပြင်ဆင်ပြီးပါပြီ.');
    }

    public function destroy(SchoolSupplyAllocation $schoolSupply)
    {
        $schoolSupply->delete();

        return redirect()->route('school-supplies.index')
            ->with('success', 'အောင်မြင်စွာဖျက်ပြီးပါပြီ.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'grade_id' => 'required|exists:grades,id',
            'township_id' => 'nullable|exists:townships,id',
            'school_supply_item_id' => 'required|exists:school_supply_items,id',

            'region' => 'nullable|string|max:255',
            'row_type' => 'nullable|in:township,total,box,loose',
            'row_label' => 'nullable|string|max:255',
            'school_count' => 'nullable|integer|min:0',
            'quantity' => 'nullable|integer|min:0',
            'remark' => 'nullable|string|max:255',
        ]);

        $item = SchoolSupplyItem::find($data['school_supply_item_id']);
        $schoolCount = (int) ($data['school_count'] ?? 0);
        $rate = (int) ($item?->rate ?? 0);

        // Auto: အရေအတွက် = နှုန်း × ကျောင်းအရေအတွက်
        $data['quantity'] = $rate * $schoolCount;

        if (empty($data['row_type'])) {
            $data['row_type'] = !empty($data['township_id']) ? 'township' : 'total';
        }

        if (empty($data['row_label']) && !empty($data['township_id'])) {
            $data['row_label'] = Township::find($data['township_id'])?->name;
        } elseif (empty($data['row_label']) && empty($data['township_id'])) {
            $data['row_label'] = 'ခရိုင်စုစုပေါင်း';
        }

        return $data;
    }
}
