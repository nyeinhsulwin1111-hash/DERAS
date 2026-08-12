<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\BookName;
use App\Models\Grade;
use App\Models\Stock;
use App\Models\Township;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $yearId = $request->academic_year_id;
        $townshipId = $request->township_id;
        $search = trim((string) $request->input('search', ''));

        $query = Stock::with([
            'academicYear',
            'township',
            'grade',
            'bookName',
        ]);

        if ($yearId) {
            $query->where('academic_year_id', $yearId);
        }

        if ($townshipId) {
            $query->where('township_id', $townshipId);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereHas(
                    'bookName',
                    fn ($bookQuery) => $bookQuery->where('name', 'like', "%{$search}%")
                )->orWhereHas(
                    'grade',
                    fn ($gradeQuery) => $gradeQuery->where('name', 'like', "%{$search}%")
                )->orWhere('remark', 'like', "%{$search}%");
            });
        }

        $stocks = $query->latest('id')->get();

        $years = AcademicYear::where('is_active', true)->orderBy('name')->get();
        $townships = Township::dropdownOptions();

        return view('stocks.index', compact(
            'stocks',
            'years',
            'townships',
            'yearId',
            'townshipId',
            'search'
        ));
    }

    public function create()
    {
        return view('stocks.create', [
            'years' => AcademicYear::where('is_active', true)->orderBy('name')->get(),
            'townships' => Township::dropdownOptions(),
            'grades' => Grade::dropdownOptions(),
            'bookNames' => BookName::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Stock::create($this->validatedData($request));

        return redirect()->route('stocks.index')
            ->with('success', 'အောင်မြင်စွာဖန်တီးပြီးပါပြီ');
    }

    public function edit(Stock $stock)
    {
        return view('stocks.edit', [
            'stock' => $stock,
            'years' => AcademicYear::where('is_active', true)->orderBy('name')->get(),
            'townships' => Township::dropdownOptions(),
            'grades' => Grade::dropdownOptions(),
            'bookNames' => BookName::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Stock $stock)
    {
        $stock->update($this->validatedData($request, $stock));

        return redirect()->route('stocks.index')
            ->with('success', 'အောင်မြင်စွာပြင်ဆင်ပြီးပါပြီ');
    }

    public function destroy(Stock $stock)
    {
        $stock->delete();

        return redirect()->route('stocks.index')
            ->with('success', 'အောင်မြင်စွာဖျက်လိုက်ပါပြီ');
    }

    private function validatedData(Request $request, ?Stock $stock = null): array
    {
        return $request->validate(
            [
                'academic_year_id' => 'required|exists:academic_years,id',
                'township_id' => 'required|exists:townships,id',
                'grade_id' => 'required|exists:grades,id',
                'book_name_id' => [
                    'required',
                    'exists:book_names,id',
                    Rule::unique('stocks', 'book_name_id')
                        ->ignore($stock?->id)
                        ->where(fn ($q) => $q
                            ->where('academic_year_id', $request->academic_year_id)
                            ->where('township_id', $request->township_id)
                            ->where('grade_id', $request->grade_id)
                        ),
                ],
                'previous_balance' => 'nullable|integer|min:0',
                'transferred' => 'nullable|integer|min:0',
                'enrolled_need' => 'nullable|integer|min:0',
                'required_qty' => 'nullable|integer|min:0',
                'remark' => 'nullable|string|max:255',
            ],
            [
                'book_name_id.unique' => 'ဤမြို့နယ်အတွက် ရွေးထားသော အတန်းနှင့် ဘာသာရပ် ပေါင်းစည်းမှု ရှိပြီးသားဖြစ်ပါသည်။',
            ]
        );
    }
}
