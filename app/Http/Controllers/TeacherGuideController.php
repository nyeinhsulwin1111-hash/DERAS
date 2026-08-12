<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\BookName;
use App\Models\Grade;
use App\Models\TeacherGuide;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeacherGuideController extends Controller
{
    public function index(Request $request)
    {
        $yearId = $request->academic_year_id;
        $gradeId = $request->grade_id;
        $guideType = $request->guide_type;

        $query = TeacherGuide::with(['academicYear', 'grade', 'bookName']);

        if ($yearId) {
            $query->where('academic_year_id', $yearId);
        }

        if ($gradeId) {
            $query->where('grade_id', $gradeId);
        }

        if ($guideType) {
            $query->where('guide_type', $guideType);
        }

        $teacherGuides = $query
            ->orderBy('group_no')
            ->orderBy('sequence_no')
            ->get();

        $years = AcademicYear::where('is_active', true)->orderBy('name')->get();
        $grades = Grade::dropdownOptions();

        $selectedYear = $yearId ? AcademicYear::find($yearId) : null;

        return view('teacher-guides.index', compact(
            'teacherGuides',
            'years',
            'grades',
            'yearId',
            'gradeId',
            'guideType',
            'selectedYear'
        ));
    }

    public function create()
    {
        return view('teacher-guides.create', [
            'years' => AcademicYear::where('is_active', true)->orderBy('name')->get(),
            'grades' => Grade::dropdownOptions(),
            'bookNames' => BookName::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        $data['group_no'] = (TeacherGuide::max('group_no') ?? 0) + 1;
        $data['sequence_no'] = (TeacherGuide::max('sequence_no') ?? 0) + 1;

        TeacherGuide::create($data);

        return redirect()->route('teacher-guides.index')
            ->with('success', 'အောင်မြင်စွာဖန်တီးပြီးပါပြီ.');
    }

    public function edit(TeacherGuide $teacherGuide)
    {
        return view('teacher-guides.edit', [
            'teacherGuide' => $teacherGuide,
            'years' => AcademicYear::where('is_active', true)->orderBy('name')->get(),
            'grades' => Grade::dropdownOptions(),
            'bookNames' => BookName::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, TeacherGuide $teacherGuide)
    {
        $teacherGuide->update($this->validatedData($request, $teacherGuide));

        return redirect()->route('teacher-guides.index')
            ->with('success', 'အောင်မြင်စွာပြင်ဆင်ပြီးပါပြီ.');
    }

    public function destroy(TeacherGuide $teacherGuide)
    {
        $teacherGuide->delete();

        return redirect()->route('teacher-guides.index')
            ->with('success', 'အောင်မြင်စွာဖျက်ပြီးပါပြီ.');
    }

    private function validatedData(Request $request, ?TeacherGuide $teacherGuide = null): array
    {
        return $request->validate(
            [
                'academic_year_id' => 'required|exists:academic_years,id',
                'grade_id' => 'required|exists:grades,id',
                'book_name_id' => [
                    'required',
                    'exists:book_names,id',
                    Rule::unique('teacher_guides', 'book_name_id')
                        ->ignore($teacherGuide?->id)
                        ->where(fn ($q) => $q
                            ->where('academic_year_id', $request->academic_year_id)
                            ->where('grade_id', $request->grade_id)
                            ->where('guide_type', $request->guide_type)
                        ),
                ],
                'group_no' => 'nullable|integer|min:1',
                'sequence_no' => 'nullable|integer|min:1',
                'group_title' => 'required|string|max:255',
                'guide_type' => 'required|in:ဆရာကိုင်,ဆရာလမ်းညွှန်',
                'kg_to_g12_quota' => 'nullable|integer|min:0',
                'g1_to_g5_quota' => 'nullable|integer|min:0',
                'total_quota' => 'nullable|integer|min:0',
                'remark' => 'nullable|string|max:255',
            ],
            [
                'book_name_id.unique' => 'ရွေးထားသော အတန်း၊ ဘာသာရပ်နှင့် ဆရာကိုင်/ဆရာလမ်းညွှန် ပေါင်းစည်းမှု ရှိပြီးသားဖြစ်ပါသည်။',
            ]
        );
    }
}
