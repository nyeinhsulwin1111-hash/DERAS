@extends('layouts.master')

@section('content')
    <div class="app-page-container">

        {{-- Main Filter & Action Card (allocation-plans style) --}}
        <div class="modern-card">
            <div class="modern-card-header" style="background: #072a1e !important; background-image: none !important; color: #ffffff !important; border-bottom: 1px solid rgba(255, 255, 255, 0.15);">
                <h5 class="modern-card-header-title text-white text-lg font-bold">
                    <i class="fas fa-calculator text-amber-400 me-2"></i>
                    {{ $selectedYear?->name ?? 'အားလုံး' }} ဘဏ္ဍာရေးနှစ်၊ ခရိုင်ပညာရေးမှူးရုံးများရှိ ဆရာလမ်းညွှန်စာအုပ်များ ခွဲတမ်းရရှိမှု၊ ဖြန့်ဝေပေးမှုနှင့် ယခင်နှစ်လက်ကျန်စာရင်းချုပ်
                </h5>
            </div>

            <div class="modern-card-body p-4 sm:p-6">
                <form method="GET" action="{{ route('teacher-guide-summaries.index') }}" class="m-0">

                    {{-- Top: စာအုပ်ရှာဖွေရန် + actions --}}
                    <div class="flex flex-wrap items-end justify-between gap-3">
                        <div class="flex flex-wrap items-end gap-3" style="flex: 1; min-width: 260px;">
                            <div style="flex: 1; min-width: 220px; max-width: 360px;">
                                <label class="block text-sm font-extrabold mb-1.5" style="color: #105c3a;">စာအုပ်ရှာဖွေရန်</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="fas fa-search" style="color: #16a34a;"></i>
                                    </span>
                                    <input type="text" name="search" class="modern-input text-sm font-medium"
                                        style="padding-left: 2.25rem;"
                                        value="{{ $search }}"
                                        placeholder="စာအုပ်ရှာရန်...">
                                </div>
                            </div>

                            <div class="flex items-end gap-2" style="padding-bottom: 1px;">
                                <button type="submit" class="btn-modern-primary">
                                    <i class="fas fa-search"></i>
                                    စစ်ထုတ်ပါ
                                </button>
                                <a href="{{ route('teacher-guide-summaries.index') }}" class="btn-modern-secondary">
                                    <i class="fas fa-redo"></i>
                                    ပြန်လည်သတ်မှတ်
                                </a>
                            </div>
                        </div>

                        <div class="flex items-end gap-2" style="padding-bottom: 1px;">
                            <button type="button" class="btn-modern-excel" onclick="exportExcel()">
                                <i class="fas fa-file-excel"></i>
                                Excel ထုတ်ပါ
                            </button>
                            <a href="{{ route('teacher-guide-summaries.create') }}" class="btn-modern-primary">
                                <i class="fas fa-plus"></i>
                                ဖန်တီးပါ
                            </a>
                        </div>
                    </div>

                    {{-- Bottom: ပညာသင်နှစ် / အတန်း / အမျိုးအစား --}}
                    <div class="flex flex-wrap items-end gap-3 mt-4 pt-3 border-t border-slate-100">
                        <div style="flex: 2; min-width: 160px;">
                            <label class="block text-sm font-extrabold mb-1.5" style="color: #105c3a;">ပညာသင်နှစ်</label>
                            <select name="academic_year_id" class="modern-select text-sm font-medium">
                                <option value="">ပညာသင်နှစ်ရွေးချယ်ပါ</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year->id }}"
                                        {{ (string) $yearId === (string) $year->id ? 'selected' : '' }}>
                                        {{ $year->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div style="flex: 2; min-width: 160px;">
                            <label class="block text-sm font-extrabold mb-1.5" style="color: #105c3a;">အတန်း</label>
                            <select name="grade_id" class="modern-select text-sm font-medium">
                                <option value="">အတန်းရွေးချယ်ပါ</option>
                                @foreach ($grades as $grade)
                                    <option value="{{ $grade->id }}"
                                        {{ (string) $gradeId === (string) $grade->id ? 'selected' : '' }}>
                                        {{ $grade->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div style="flex: 2; min-width: 160px;">
                            <label class="block text-sm font-extrabold mb-1.5" style="color: #105c3a;">အမျိုးအစား</label>
                            <select name="guide_type" class="modern-select text-sm font-medium">
                                <option value="">အမျိုးအစားရွေးချယ်ပါ</option>
                                <option value="ဆရာကိုင်" {{ $guideType === 'ဆရာကိုင်' ? 'selected' : '' }}>ဆရာကိုင်</option>
                                <option value="ဆရာလမ်းညွှန်" {{ $guideType === 'ဆရာလမ်းညွှန်' ? 'selected' : '' }}>ဆရာလမ်းညွှန်</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Table Section with #072a1e dark green header --}}
        <div class="table-responsive mt-4">
            <table class="table table-bordered table-striped text-center align-middle summary-table">
                <thead style="background-color: #072a1e; color: #ffffff;">
                    <tr>
                        <th style="width: 50px; min-width: 50px; white-space: nowrap;">စဉ်</th>
                        <th style="min-width: 120px; text-align: left; white-space: nowrap;">အတန်း</th>
                        <th style="min-width: 90px; white-space: nowrap;">ဘာသာစဉ်</th>
                        <th style="min-width: 180px; text-align: left; white-space: nowrap;">ဘာသာအမည်</th>
                        <th style="min-width: 130px; white-space: nowrap;">ယခင်နှစ်လက်ကျန်</th>
                        <th style="min-width: 140px; white-space: nowrap;">ဘဏ္ဍာရေးနှစ်ခွဲတမ်း</th>
                        <th style="min-width: 130px; white-space: nowrap;">စုစုပေါင်းအုပ်ရေ</th>
                        <th style="min-width: 140px; white-space: nowrap;">ဖြန့်ဝေပြီးအုပ်ရေ</th>
                        <th style="min-width: 120px; white-space: nowrap;">ခရိုင်ရုံးလက်ကျန်</th>
                        <th style="min-width: 150px; text-align: left; white-space: nowrap;">မှတ်ချက်</th>
                        <th style="min-width: 100px; white-space: nowrap;">လုပ်ဆောင်ချက်</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($summaries as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="whitespace-nowrap font-medium text-slate-800">{{ $row->grade?->name }}</td>
                            <td>{{ $row->sequence_no }}</td>
                            <td class="text-start font-medium">{{ $row->bookName?->name }}</td>
                            <td class="fw-semibold">{{ number_format((int) ($row->previous_balance ?? 0)) }}</td>
                            <td class="fw-semibold">{{ number_format((int) ($row->fiscal_year_quota ?? 0)) }}</td>
                            <td class="fw-bold" style="color: #105c3a;">{{ number_format((int) ($row->total_books ?? 0)) }}</td>
                            <td class="fw-bold text-success">{{ number_format((int) ($row->distributed_books ?? 0)) }}</td>
                            <td class="fw-bold text-amber-600">{{ number_format((int) ($row->remaining_books ?? 0)) }}</td>
                            <td class="text-start">{{ $row->remark ?? '-' }}</td>
                            <td class="whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5 justify-center">
                                    <a href="{{ route('teacher-guide-summaries.edit', $row->id) }}"
                                        class="btn-modern-warning" title="ပြင်ဆင်ပါ">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    @if (auth()->user()?->role === 'super')
                                        <form method="POST" action="{{ route('teacher-guide-summaries.destroy', $row->id) }}" class="d-inline m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn-modern-danger" title="ဖျက်ပါ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="py-4 text-muted">အချက်အလက် မရှိသေးပါ။</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/file-saver/dist/FileSaver.min.js"></script>

    <script>
        async function exportExcel() {
            const workbook = new ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet('Teacher Guide Summary');

            worksheet.columns = [
                { width: 8 },
                { width: 25 },
                { width: 12 },
                { width: 30 },
                { width: 18 },
                { width: 18 },
                { width: 18 },
                { width: 18 },
                { width: 15 },
                { width: 20 },
            ];

            worksheet.mergeCells('A1:J1');
            worksheet.getCell('A1').value =
                "{{ $selectedYear?->name ?? 'အားလုံး' }} ဘဏ္ဍာရေးနှစ်၊ ခရိုင်ပညာရေးမှူးရုံးများရှိ ဆရာလမ်းညွှန်စာအုပ်များ ခွဲတမ်းရရှိမှု၊ ဖြန့်ဝေပေးမှုနှင့် ယခင်နှစ်လက်ကျန်စာရင်းချုပ်";

            worksheet.getCell('A1').alignment = {
                horizontal: 'center',
                vertical: 'middle',
                wrapText: true
            };

            worksheet.getCell('A1').font = {
                bold: true,
                size: 14
            };

            worksheet.addRow([]);

            worksheet.addRow([
                'စဉ်',
                'အတန်း',
                'ဘာသာစဉ်',
                'ဘာသာအမည်',
                'ယခင်နှစ်ယခင်နှစ်လက်ကျန်',
                'ဘဏ္ဍာရေးနှစ်ခွဲတမ်း',
                'စုစုပေါင်းအုပ်ရေ',
                'ဖြန့်ဝေပြီးအုပ်ရေ',
                'ယခင်နှစ်လက်ကျန်',
                'မှတ်ချက်'
            ]);

            @foreach ($summaries as $index => $row)
                worksheet.addRow([
                    {{ $index + 1 }},
                    {!! json_encode($row->grade?->name) !!},
                    {{ $row->sequence_no }},
                    {!! json_encode($row->bookName?->name) !!},
                    {{ $row->previous_balance ?? 0 }},
                    {{ $row->fiscal_year_quota ?? 0 }},
                    {{ $row->total_books ?? 0 }},
                    {{ $row->distributed_books ?? 0 }},
                    {{ $row->remaining_books ?? 0 }},
                    {!! json_encode($row->remark) !!}
                ]);
            @endforeach

            worksheet.eachRow((row, rowNumber) => {
                row.eachCell((cell) => {
                    cell.alignment = {
                        horizontal: 'center',
                        vertical: 'middle',
                        wrapText: true
                    };
                    cell.border = {
                        top: { style: 'thin' },
                        left: { style: 'thin' },
                        bottom: { style: 'thin' },
                        right: { style: 'thin' }
                    };
                });

                if (rowNumber === 3) {
                    row.font = {
                        bold: true
                    };
                }
            });

            const buffer = await workbook.xlsx.writeBuffer();
            saveAs(new Blob([buffer]), 'teacher-guide-summary.xlsx');
        }
    </script>
@endsection
