@extends('layouts.master')

@section('content')
    <div class="app-page-container">

        {{-- Filter Card: Title + Search --}}
        <div class="modern-card">
            <div class="modern-card-header" style="background: #072a1e !important; background-image: none !important; color: #ffffff !important; border-bottom: 1px solid rgba(255, 255, 255, 0.15);">
                <h5 class="modern-card-header-title text-white text-lg font-bold">
                    <i class="fas fa-book-reader text-amber-400"></i>
                    {{ $selectedYear?->name ?? 'အားလုံး' }} ပညာသင်နှစ်အတွက် ဆရာကိုင်/ဆရာလမ်းညွှန် စာအုပ်များ လက်ခံရရှိမှုနှင့် ဖြန့်ဝေရန်ခွဲတမ်းစာရင်း
                </h5>
            </div>

            <div class="modern-card-body p-4 sm:p-6">
                <form method="GET" class="m-0">
                    <div class="flex flex-wrap items-end justify-between gap-3">

                        <div class="flex flex-wrap items-end gap-3" style="flex: 1; min-width: 260px;">
                            <div style="min-width: 160px; max-width: 220px; flex: 1;">
                                <label class="block text-sm font-extrabold mb-1.5" style="color: #105c3a;">ပညာသင်နှစ်</label>
                                <select name="academic_year_id" class="modern-select text-sm font-medium" onchange="this.form.submit()">
                                    <option value="">ပညာသင်နှစ်ရွေးချယ်ပါ</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year->id }}"
                                            {{ (string) $yearId === (string) $year->id ? 'selected' : '' }}>
                                            {{ $year->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div style="min-width: 140px; max-width: 200px; flex: 1;">
                                <label class="block text-sm font-extrabold mb-1.5" style="color: #105c3a;">အတန်း</label>
                                <select name="grade_id" class="modern-select text-sm font-medium" onchange="this.form.submit()">
                                    <option value="">အတန်းရွေးချယ်ပါ</option>
                                    @foreach ($grades as $grade)
                                        <option value="{{ $grade->id }}"
                                            {{ (string) $gradeId === (string) $grade->id ? 'selected' : '' }}>
                                            {{ $grade->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div style="min-width: 140px; max-width: 200px; flex: 1;">
                                <label class="block text-sm font-extrabold mb-1.5" style="color: #105c3a;">အမျိုးအစား</label>
                                <select name="guide_type" class="modern-select text-sm font-medium" onchange="this.form.submit()">
                                    <option value="">အမျိုးအစားရွေးချယ်ပါ</option>
                                    <option value="ဆရာကိုင်" {{ $guideType === 'ဆရာကိုင်' ? 'selected' : '' }}>
                                        ဆရာကိုင်
                                    </option>
                                    <option value="ဆရာလမ်းညွှန်" {{ $guideType === 'ဆရာလမ်းညွှန်' ? 'selected' : '' }}>
                                        ဆရာလမ်းညွှန်
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-end gap-2 ms-auto" style="padding-bottom: 1px;">
                            <a href="{{ route('teacher-guides.index') }}" class="btn-modern-secondary">
                                <i class="fas fa-redo"></i>
                                ပြန်လည်သတ်မှတ်
                            </a>
                            <button type="button" class="btn-modern-excel" onclick="exportTeacherGuideExcel()">
                                <i class="fas fa-file-excel"></i>
                                Excel ထုတ်ပါ
                            </button>
                            <a href="{{ route('teacher-guides.create') }}" class="btn-modern-primary">
                                <i class="fas fa-plus"></i>
                                ဖန်တီးပါ
                            </a>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        {{-- Data Table (outside card, below) --}}
        <div class="modern-table-container mt-4">
            <table class="modern-table teacher-guide-table">
                <thead>
                    <tr>
                        <th>စဉ်</th>
                        <th>အတန်း</th>
                        <th>ဘာသာ</th>
                        <th>အမျိုးအစား</th>
                        <th>KG to G-12 ခရိုင်ရရှိခွဲတမ်း</th>
                        <th>G-1 to G-5 ခရိုင်ရရှိခွဲတမ်း</th>
                        <th>၂မျိုးပေါင်း ခရိုင်ရရှိခွဲတမ်း</th>
                        <th>လုပ်ဆောင်ချက်</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($teacherGuides as $row)
                        <tr>
                            <td class="font-mono text-slate-500">{{ $row->sequence_no }}</td>
                            <td class="whitespace-nowrap font-medium text-slate-800">{{ $row->grade?->name }}</td>
                            <td class="text-left font-medium text-slate-800">{{ $row->bookName?->name }}</td>
                            <td class="whitespace-nowrap font-medium text-slate-800">{{ $row->guide_type }}</td>
                            <td class="font-mono text-slate-600">{{ number_format($row->kg_to_g12_quota) }}</td>
                            <td class="font-mono text-slate-600">{{ number_format($row->g1_to_g5_quota) }}</td>
                            <td class="font-mono font-semibold text-emerald-700">{{ number_format($row->kg_to_g12_quota + $row->g1_to_g5_quota) }}</td>
                            <td class="whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('teacher-guides.edit', $row->id) }}" class="btn-modern-warning" title="ပြင်ဆင်ပါ">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    @if (auth()->user()?->role === 'super')
                                        <form action="{{ route('teacher-guides.destroy', $row->id) }}" method="POST" class="d-inline m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn-modern-danger" title="ဖျက်ပါ"
                                                onclick="return confirm('ဖျက်ရန်?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-muted py-4 text-center">
                                Data မရှိသေးပါ။
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/file-saver/dist/FileSaver.min.js"></script>
    <script>
        async function exportTeacherGuideExcel() {
            const workbook = new ExcelJS.Workbook();
            const sheet = workbook.addWorksheet('Teacher Guides');

            sheet.columns = [
                { width: 8 }, { width: 28 }, { width: 30 }, { width: 18 },
                { width: 20 }, { width: 20 }, { width: 20 },
            ];

            sheet.mergeCells('A1:G1');
            sheet.getCell('A1').value =
                '{{ $selectedYear?->name ?? 'အားလုံး' }} ပညာသင်နှစ်အတွက် ဆရာကိုင်/ဆရာလမ်းညွှန် စာအုပ်များ လက်ခံရရှိမှုနှင့် ဖြန့်ဝေရန်ခွဲတမ်းစာရင်း';

            sheet.getCell('A1').font = { bold: true, size: 15 };
            sheet.getCell('A1').alignment = { horizontal: 'center', vertical: 'middle' };

            sheet.getRow(3).values = [
                'စဉ်', 'အတန်း', 'ဘာသာ', 'အမျိုးအစား',
                'KG to G-12 ခရိုင်ရရှိခွဲတမ်း', 'G-1 to G-5 ခရိုင်ရရှိခွဲတမ်း', '၂မျိုးပေါင်း ခရိုင်ရရှိခွဲတမ်း'
            ];

            @foreach ($teacherGuides as $row)
                sheet.addRow([
                    {{ $row->sequence_no }},
                    `{{ addslashes($row->grade?->name) }}`,
                    `{{ addslashes($row->bookName?->name) }}`,
                    `{{ $row->guide_type }}`,
                    {{ $row->kg_to_g12_quota }},
                    {{ $row->g1_to_g5_quota }},
                    {{ $row->kg_to_g12_quota + $row->g1_to_g5_quota }}
                ]);
            @endforeach

            sheet.eachRow((row, rowNumber) => {
                row.eachCell((cell) => {
                    cell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };
                    cell.border = { top: { style: 'thin' }, left: { style: 'thin' }, bottom: { style: 'thin' }, right: { style: 'thin' } };
                    if (rowNumber === 3) {
                        cell.font = { bold: true };
                        cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFD9EAD3' } };
                    }
                });
            });

            sheet.getRow(1).height = 35;
            sheet.getRow(3).height = 35;

            const buffer = await workbook.xlsx.writeBuffer();
            saveAs(new Blob([buffer]), 'Teacher_Guides.xlsx');
        }
    </script>
@endsection

@push('styles')
    <style>
        body {
            background: #f4f6f8;
        }

        .teacher-guide-table {
            background: #ffffff;
            color: #4f5870;
        }

        .teacher-guide-table th {
            font-size: 15px !important;
            border: 1px solid #2f2f2f !important;
            vertical-align: middle !important;
            padding: 6px 8px;
        }

        .teacher-guide-table td {
            font-size: 14px !important;
            border: 1px solid #2f2f2f !important;
            vertical-align: middle !important;
            padding: 6px 8px;
        }

        .teacher-guide-table thead th {
            background: #e5e5ea;
            font-weight: 700;
            white-space: nowrap;
        }

        .teacher-guide-table tbody tr:hover {
            background: #f1f8f4;
        }
    </style>
@endpush
