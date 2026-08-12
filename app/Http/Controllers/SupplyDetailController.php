<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\SchoolSupplyAllocation;
use App\Models\SchoolSupplyItem;
use App\Models\SupplyDetail;
use App\Models\SupplyItem;
use App\Models\Township;
use Illuminate\Http\Request;

class SupplyDetailController extends Controller
{
    public function index(Request $request)
    {
        $yearId = $request->academic_year_id;
        $townshipId = $request->township_id;
        $gradeId = $request->grade_id;

        $query = SupplyDetail::with([
            'academicYear',
            'township',
            'grade',
            'item',
        ]);

        if ($yearId) {
            $query->where('academic_year_id', $yearId);
        }

        if ($townshipId) {
            $query->where('township_id', $townshipId);
        }

        if ($gradeId) {
            $query->where('grade_id', $gradeId);
        }

        $details = $query
            ->orderBy('township_id')
            ->orderBy('grade_id')
            ->orderBy('sequence_no')
            ->get();

        $years = AcademicYear::where('is_active', true)->orderBy('name')->get();
        $townships = Township::dropdownOptions();
        $grades = Grade::dropdownOptions();

        return view('supply-details.index', compact(
            'details',
            'years',
            'townships',
            'grades',
            'yearId',
            'townshipId',
            'gradeId'
        ));
    }

    public function create()
    {
        return view('supply-details.create', [
            'years' => AcademicYear::where('is_active', true)->orderBy('name')->get(),
            'townships' => Township::dropdownOptions(),
            'grades' => Grade::dropdownOptions(),
            'items' => SupplyItem::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        $data['sequence_no'] = SupplyDetail::max('sequence_no') + 1;

        SupplyDetail::create($data);

        return redirect()->route('supply-details.index')
            ->with('success', 'အောင်မြင်စွာဖန်တီးပြီးပါပြီ.');
    }

    public function edit(SupplyDetail $supplyDetail)
    {
        return view('supply-details.edit', [
            'supplyDetail' => $supplyDetail,
            'years' => AcademicYear::where('is_active', true)->orderBy('name')->get(),
            'townships' => Township::dropdownOptions(),
            'grades' => Grade::dropdownOptions(),
            'items' => SupplyItem::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, SupplyDetail $supplyDetail)
    {
        $supplyDetail->update($this->validatedData($request));

        return redirect()->route('supply-details.index')
            ->with('success', 'အောင်မြင်စွာပြင်ဆင်ပြီးပါပြီ.');
    }

    public function destroy(SupplyDetail $supplyDetail)
    {
        $supplyDetail->delete();

        return redirect()->route('supply-details.index')
            ->with('success', 'အောင်မြင်စွာဖျက်ပြီးပါပြီ.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'township_id' => 'required|exists:townships,id',
            'grade_id' => 'required|exists:grades,id',
            'supply_item_id' => 'required|exists:supply_items,id',
            'sequence_no' => 'nullable|integer|min:1',
            'unit' => 'nullable|integer|min:0',
            'issued_total' => 'nullable|integer|min:0',
            'package_count' => 'nullable|integer|min:0',
            'loose_count' => 'nullable|integer|min:0',
            'remark' => 'nullable|string|max:255',
        ]);

        // Auto: ထုတ်ပေးမှု ← ခွဲတမ်း အရေအတွက်
        $quotaQty = $this->quotaQuantityFor(
            $data['academic_year_id'] ?? null,
            $data['township_id'],
            $data['grade_id'],
            $data['supply_item_id']
        );
        if ($quotaQty !== null) {
            $data['issued_total'] = $quotaQty;
        }

        $unit = (int) ($data['unit'] ?? 0);
        $issued = (int) ($data['issued_total'] ?? 0);
        if ($unit > 0) {
            $data['package_count'] = intdiv($issued, $unit);
            $data['loose_count'] = $issued % $unit;
        }

        return $data;
    }

    private function quotaQuantityFor($yearId, $townshipId, $gradeId, $supplyItemId): ?int
    {
        $supplyItem = SupplyItem::find($supplyItemId);
        if (!$supplyItem) {
            return null;
        }

        $target = $this->normalizeItemName($supplyItem->name);
        $schoolItemIds = SchoolSupplyItem::query()
            ->get(['id', 'name'])
            ->filter(fn ($item) => $this->normalizeItemName($item->name) === $target)
            ->pluck('id');

        if ($schoolItemIds->isEmpty()) {
            return null;
        }

        $base = SchoolSupplyAllocation::query()
            ->where('grade_id', $gradeId)
            ->where('township_id', $townshipId)
            ->whereIn('school_supply_item_id', $schoolItemIds)
            ->where('row_type', 'township');

        $row = (clone $base)
            ->when($yearId, fn ($q) => $q->where('academic_year_id', $yearId))
            ->orderByDesc('id')
            ->first()
            ?? $base->orderByDesc('id')->first();

        return $row ? (int) $row->quantity : null;
    }

    private function normalizeItemName(string $name): string
    {
        $name = mb_strtolower(trim($name));
        $name = preg_replace('/\s+/u', '', $name) ?? $name;
        $name = str_replace(['(', ')', '（', '）', '၊', ',', '.', '-', '–', '/', '\\'], '', $name);

        return $name;
    }
}
