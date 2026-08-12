<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\TeacherGuide;
use App\Models\TeacherGuideIssue;
use App\Models\Township;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TeacherGuideIssueController extends Controller
{
    public function index(Request $request)
    {
        $query = TeacherGuideIssue::with(['academicYear', 'grade', 'bookName', 'townshipIssues.township']);
        $query->when($request->academic_year_id, fn($q, $v) => $q->where('academic_year_id', $v));
        $query->when($request->grade_id, fn($q, $v) => $q->where('grade_id', $v));
        $query->when($request->guide_type, fn($q, $v) => $q->where('guide_type', $v));
        $query->when($request->township_id, function ($q, $v) {
            $q->whereHas('townshipIssues', fn($sub) => $sub->where('township_id', $v));
        });
        $query->when($request->search, function ($q, $v) {
            $q->whereHas('bookName', fn($sub) => $sub->where('name', 'like', '%' . $v . '%'));
        });

        $issues = $query->orderBy('group_no')->orderBy('sequence_no')->get();
        return view('teacher-guide-issues.index', [
            'issues' => $issues,
            'years' => AcademicYear::where('is_active', true)->orderBy('name')->get(),
            'grades' => Grade::dropdownOptions(),
            'townships' => Township::dropdownOptions(),
        ]);
    }

    public function create()
    {
        return view('teacher-guide-issues.create', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        DB::transaction(function () use ($data) {
            if (empty($data['issue']['group_no'])) {
                $last = TeacherGuideIssue::orderByDesc('id')->first();
                $data['issue']['group_no'] = $last ? $last->group_no + 1 : 1;
            }

            if (empty($data['issue']['sequence_no'])) {
                $data['issue']['sequence_no'] = TeacherGuideIssue::where(
                    'group_no',
                    $data['issue']['group_no']
                )->count() + 1;
            }

            $issue = TeacherGuideIssue::create($data['issue']);

            foreach ($data['townships'] as $townshipId => $values) {
                $issue->townshipIssues()->create(
                    ['township_id' => $townshipId] + $values
                );
            }
        });

        return redirect()
            ->route('teacher-guide-issues.index')
            ->with('success', 'အောင်မြင်စွာဖန်တီးပြီးပါပြီ.');
    }

    public function edit(TeacherGuideIssue $teacherGuideIssue)
    {
        $teacherGuideIssue->load(['townshipIssues', 'bookName']);

        return view('teacher-guide-issues.edit', $this->formData() + [
            'teacherGuideIssue' => $teacherGuideIssue,
        ]);
    }

    public function update(Request $request, TeacherGuideIssue $teacherGuideIssue)
    {
        $data = $this->validatedData($request, $teacherGuideIssue);
        DB::transaction(function () use ($teacherGuideIssue, $data) {
            $teacherGuideIssue->update($data['issue']);
            foreach ($data['townships'] as $townshipId => $values) {
                $teacherGuideIssue->townshipIssues()->updateOrCreate(['township_id' => $townshipId], $values);
            }
        });
        return redirect()->route('teacher-guide-issues.index')->with('success', 'အောင်မြင်စွာပြင်ဆင်ပြီးပါပြီ.');
    }

    public function destroy(TeacherGuideIssue $teacherGuideIssue)
    {
        $teacherGuideIssue->delete();
        return redirect()->route('teacher-guide-issues.index')->with('success', 'အောင်မြင်စွာဖျက်ပြီးပါပြီ.');
    }

    private function formData(): array
    {
        return [
            'years' => AcademicYear::where('is_active', true)->orderBy('name')->get(),
            'grades' => Grade::dropdownOptions(),
            'townships' => Township::dropdownOptions(),
        ];
    }

    private function validatedData(Request $request, ?TeacherGuideIssue $issue = null): array
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'grade_id' => 'required|exists:grades,id',
            'book_name_id' => [
                'required',
                'exists:book_names,id',
                Rule::unique('teacher_guide_issues')->where(
                    fn ($q) => $q
                        ->where('academic_year_id', $request->academic_year_id)
                        ->where('grade_id', $request->grade_id)
                        ->where('guide_type', $request->guide_type)
                )->ignore($issue?->id),
            ],
            'group_no' => 'nullable|integer|min:1',
            'guide_type' => 'required|in:ဆရာကိုင်,ဆရာလမ်းညွှန်',
            'sequence_no' => 'nullable|integer|min:1',
            'district_unit' => 'nullable|integer|min:0',
            'package_unit' => 'required|integer|min:0',
            'remark' => 'nullable|string|max:1000',
            'township_values' => 'required|array',
            'township_values.*.issued_quantity' => 'nullable|integer|min:0',
            'township_values.*.full_package_count' => 'nullable|integer|min:0',
            'township_values.*.loose_book_count' => 'nullable|integer|min:0',
        ]);

        $quota = TeacherGuide::query()
            ->where('academic_year_id', $validated['academic_year_id'])
            ->where('grade_id', $validated['grade_id'])
            ->where('book_name_id', $validated['book_name_id'])
            ->where('guide_type', $validated['guide_type'])
            ->orderByDesc('id')
            ->first();

        $validated['district_unit'] = $quota
            ? (int) ($quota->remaining_total ?? 0)
            : (int) ($validated['district_unit'] ?? 0);

        $gradeName = Grade::where('id', $validated['grade_id'])->value('name');
        $validated['group_title'] = $quota?->group_title
            ?? ($gradeName . "\n(" . $validated['guide_type'] . ')');

        if ($quota) {
            $validated['group_no'] = $quota->group_no;
            $validated['sequence_no'] = $quota->sequence_no;
        }

        $issuedByTownshipName = [
            'မြန်အောင်' => (int) ($quota?->total_myanaung_qty ?? 0),
            'ကြံခင်း' => (int) ($quota?->total_kyankhin_qty ?? 0),
            'အင်္ဂပူ' => (int) ($quota?->total_ingapu_qty ?? 0),
        ];

        $packageUnit = (int) ($validated['package_unit'] ?? 0);
        $townships = [];

        foreach (Township::dropdownOptions() as $township) {
            $issued = $issuedByTownshipName[$township->name]
                ?? (int) ($validated['township_values'][$township->id]['issued_quantity'] ?? 0);

            $packages = $packageUnit > 0 ? intdiv($issued, $packageUnit) : 0;
            $loose = $packageUnit > 0 ? $issued % $packageUnit : 0;

            $townships[$township->id] = [
                'issued_quantity' => $issued,
                'full_package_count' => $packages,
                'loose_book_count' => $loose,
            ];
        }

        return [
            'issue' => collect($validated)->except('township_values')->all(),
            'townships' => $townships,
        ];
    }
}
