<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\TeacherGuide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherGuideDistributionController extends Controller
{
    public function index(Request $request): View
    {
        $yearId = $request->integer('academic_year_id') ?: null;
        $gradeId = $request->integer('grade_id') ?: null;
        $guideType = $request->input('guide_type');
        $search = trim((string) $request->input('search', ''));

        $query = TeacherGuide::query()
            ->with([
                'academicYear',
                'grade',
                'bookName',
            ]);

        if ($yearId) {
            $query->where('academic_year_id', $yearId);
        }

        if ($gradeId) {
            $query->where('grade_id', $gradeId);
        }

        if ($guideType) {
            $query->where('guide_type', $guideType);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereHas(
                    'bookName',
                    fn ($bookQuery) => $bookQuery->where('name', 'like', "%{$search}%")
                )->orWhere('group_title', 'like', "%{$search}%");
            });
        }

        $teacherGuides = $query
            ->orderBy('group_no')
            ->orderBy('sequence_no')
            ->get();

        $years = AcademicYear::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $grades = Grade::dropdownOptions();

        $selectedYear = $yearId
            ? AcademicYear::find($yearId)
            : null;

        return view('teacher-guide-distributions.index', compact(
            'teacherGuides',
            'years',
            'grades',
            'yearId',
            'gradeId',
            'guideType',
            'search',
            'selectedYear'
        ));
    }

    public function create(): View
    {
        return view('teacher-guide-distributions.create', [
            'years' => AcademicYear::where('is_active', true)
                ->orderBy('name')
                ->get(),

            'grades' => Grade::dropdownOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'grade_id' => 'required|exists:grades,id',
            'book_name_id' => 'required|exists:book_names,id',

            'group_no' => 'nullable|integer|min:1',
            'group_title' => 'nullable|string|max:1000',
            'guide_type' => 'required|in:ဆရာကိုင်,ဆရာလမ်းညွှန်',
            'sequence_no' => 'nullable|integer|min:1',

            'kg_to_g12_quota' => 'nullable|integer|min:0',
            'g1_to_g5_quota' => 'nullable|integer|min:0',

            'kg_g12_myanaung_qty' => 'nullable|integer|min:0',
            'kg_g12_kyankhin_qty' => 'nullable|integer|min:0',
            'kg_g12_ingapu_qty' => 'nullable|integer|min:0',

            'g1_g5_myanaung_qty' => 'nullable|integer|min:0',
            'g1_g5_kyankhin_qty' => 'nullable|integer|min:0',
            'g1_g5_ingapu_qty' => 'nullable|integer|min:0',

            'remark' => 'nullable|string|max:1000',
        ]);

        // Prefer district quotas already saved in လက်ခံရရှိမှု
        $receipt = TeacherGuide::query()
            ->where('academic_year_id', $data['academic_year_id'])
            ->where('grade_id', $data['grade_id'])
            ->where('book_name_id', $data['book_name_id'])
            ->where('guide_type', $data['guide_type'])
            ->orderByDesc('id')
            ->first();

        if ($receipt) {
            $data['kg_to_g12_quota'] = (int) ($receipt->kg_to_g12_quota ?? 0);
            $data['g1_to_g5_quota'] = (int) ($receipt->g1_to_g5_quota ?? 0);
            $data['group_no'] = $receipt->group_no;
            $data['sequence_no'] = $receipt->sequence_no;
            $data['group_title'] = $receipt->group_title;
        } else {
            $data['group_no'] = (TeacherGuide::max('group_no') ?? 0) + 1;
            $data['sequence_no'] = (TeacherGuide::where('grade_id', $data['grade_id'])
                ->where('guide_type', $data['guide_type'])
                ->max('sequence_no') ?? 0) + 1;

            $gradeName = Grade::where('id', $data['grade_id'])->value('name');
            $data['group_title'] = $gradeName . "\n(" . $data['guide_type'] . ')';
        }

        $data['total_quota'] =
            ($data['kg_to_g12_quota'] ?? 0)
            + ($data['g1_to_g5_quota'] ?? 0);

        $data['total_myanaung_qty'] =
            ($data['kg_g12_myanaung_qty'] ?? 0)
            + ($data['g1_g5_myanaung_qty'] ?? 0);

        $data['total_kyankhin_qty'] =
            ($data['kg_g12_kyankhin_qty'] ?? 0)
            + ($data['g1_g5_kyankhin_qty'] ?? 0);

        $data['total_ingapu_qty'] =
            ($data['kg_g12_ingapu_qty'] ?? 0)
            + ($data['g1_g5_ingapu_qty'] ?? 0);

        $data['distributed_total'] =
            $data['total_myanaung_qty']
            + $data['total_kyankhin_qty']
            + $data['total_ingapu_qty'];

        $data['remaining_total'] =
            $data['total_quota']
            - $data['distributed_total'];

        TeacherGuide::updateOrCreate(
            [
                'academic_year_id' => $data['academic_year_id'],
                'grade_id' => $data['grade_id'],
                'book_name_id' => $data['book_name_id'],
                'guide_type' => $data['guide_type'],
            ],
            $data
        );

        return redirect()
            ->route('teacher-guide-distributions.index')
            ->with('success', 'ဖြန့်ဝေရန်ခွဲတမ်းအသစ်ဖန်တီးပြီးပါပြီ');
    }

    public function edit(TeacherGuide $teacherGuideDistribution): View
    {
        $teacherGuideDistribution->load([
            'academicYear',
            'grade',
            'bookName',
        ]);

        return view('teacher-guide-distributions.edit', [
            'teacherGuide' => $teacherGuideDistribution,
        ]);
    }

    public function update(
        Request $request,
        TeacherGuide $teacherGuideDistribution
    ): RedirectResponse {
        $data = $this->validatedData($request);

        $data['total_myanaung_qty'] =
            ($data['kg_g12_myanaung_qty'] ?? 0)
            + ($data['g1_g5_myanaung_qty'] ?? 0);

        $data['total_kyankhin_qty'] =
            ($data['kg_g12_kyankhin_qty'] ?? 0)
            + ($data['g1_g5_kyankhin_qty'] ?? 0);

        $data['total_ingapu_qty'] =
            ($data['kg_g12_ingapu_qty'] ?? 0)
            + ($data['g1_g5_ingapu_qty'] ?? 0);

        $data['distributed_total'] =
            $data['total_myanaung_qty']
            + $data['total_kyankhin_qty']
            + $data['total_ingapu_qty'];

        $data['remaining_total'] =
            $teacherGuideDistribution->total_quota
            - $data['distributed_total'];

        $teacherGuideDistribution->update($data);

        return redirect()
            ->route('teacher-guide-distributions.index')
            ->with('success', 'ဖြန့်ဝေရန်ခွဲတမ်း အချက်အလက် ပြင်ဆင်ပြီးပါပြီ။');
    }

    public function destroy(TeacherGuide $teacherGuide)
    {
        $teacherGuide->delete();

        return redirect()
            ->route('teacher-guide-distributions.index')
            ->with('success', 'အောင်မြင်စွာဖျက်ပြီးပါပြီ.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'kg_g12_myanaung_qty' => 'nullable|integer|min:0',
            'kg_g12_kyankhin_qty' => 'nullable|integer|min:0',
            'kg_g12_ingapu_qty' => 'nullable|integer|min:0',

            'g1_g5_myanaung_qty' => 'nullable|integer|min:0',
            'g1_g5_kyankhin_qty' => 'nullable|integer|min:0',
            'g1_g5_ingapu_qty' => 'nullable|integer|min:0',

            'remark' => 'nullable|string|max:1000',
        ]);
    }
}
