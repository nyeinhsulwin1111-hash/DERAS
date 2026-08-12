<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\BookName;
use App\Models\Grade;
use App\Models\Textbook;
use App\Models\Township;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TextbookController extends Controller
{
    public function index(Request $request)
    {
        $yearId = $request->academic_year_id;
        $townshipId = $request->township_id;
        $search = trim((string) $request->input('search', ''));

        $query = Textbook::with(['year', 'township', 'grade', 'bookName']);

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

        $textbooks = $query->orderBy('township_id')->orderBy('id')->get();

        $years = AcademicYear::where('is_active', true)->orderBy('name')->get();
        $townships = Township::dropdownOptions();

        $blocks = $textbooks->groupBy('township_id')->map(function ($items) {
            return [
                'academic_year' => $items->first()->year?->name,
                'township' => $items->first()->township?->name,
                'rows' => $items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'grade' => $item->grade?->name ?? '',
                        'book_name' => $item->bookName?->name ?? '',
                        'books_per_set' => $item->books_per_set ?? 0,
                        'student_count' => $item->student_count ?? 0,
                        'book_count' => $item->book_count ?? '',
                        'remark' => $item->remark ?? '',
                    ];
                })->values()->toArray(),
            ];
        })->values()->toArray();

        $maxRows = collect($blocks)->max(fn($block) => count($block['rows'])) ?? 0;

        return view('textbook.index', compact(
            'blocks',
            'maxRows',
            'years',
            'townships',
            'yearId',
            'townshipId',
            'search'
        ));
    }

    public function create()
    {
        return view('textbook.create', [
            'years' => AcademicYear::where('is_active', true)->orderBy('name')->get(),
            'townships' => Township::dropdownOptions(),
            'grades' => Grade::dropdownOptions(),
            'bookNames' => BookName::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Textbook::create($this->validatedData($request));

        return redirect()->route('textbook.index')->with('success', 'အောင်မြင်စွာဖန်တီးပြီးပါပြီ');
    }

    public function edit(Textbook $textbook)
    {
        return view('textbook.edit', [
            'textbook' => $textbook,
            'years' => AcademicYear::where('is_active', true)->orderBy('name')->get(),
            'townships' => Township::dropdownOptions(),
            'grades' => Grade::dropdownOptions(),
            'bookNames' => BookName::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Textbook $textbook)
    {
        $textbook->update($this->validatedData($request, $textbook));

        return redirect()->route('textbook.index')->with('success', 'အောင်မြင်စွာပြင်ဆင်ပြီးပါပြီ');
    }

    public function destroy(Textbook $textbook)
    {
        $textbook->delete();

        return redirect()->route('textbook.index')->with('success', 'အောင်မြင်စွာဖျက်လိုက်ပါပြီ');
    }

    private function validatedData(Request $request, ?Textbook $textbook = null): array
    {
        return $request->validate(
            [
                'academic_year_id' => 'required|exists:academic_years,id',
                'township_id' => 'required|exists:townships,id',
                'grade_id' => 'required|exists:grades,id',
                'book_name_id' => [
                    'required',
                    'exists:book_names,id',
                    Rule::unique('textbooks', 'book_name_id')
                        ->ignore($textbook?->id)
                        ->where(fn ($q) => $q
                            ->where('academic_year_id', $request->academic_year_id)
                            ->where('township_id', $request->township_id)
                            ->where('grade_id', $request->grade_id)
                        ),
                ],
                'books_per_set' => 'nullable|integer|min:0',
                'student_count' => 'nullable|integer|min:0',
                'book_count' => 'nullable|string|max:255',
                'remark' => 'nullable|string|max:255',
            ],
            [
                'book_name_id.unique' => 'ဤမြို့နယ်အတွက် ရွေးထားသော အတန်းနှင့် ဘာသာရပ် ပေါင်းစည်းမှု ရှိပြီးသားဖြစ်ပါသည်။',
            ]
        );
    }
}
