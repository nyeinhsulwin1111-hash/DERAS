@extends('layouts.master')

@section('content')
    <div class="app-page-container">

        {{-- Main Card: Title + Filter Section --}}
        <div class="modern-card">
            {{-- Card Header --}}
            <div class="modern-card-header" style="background: #072a1e !important; background-image: none !important; color: #ffffff !important; border-bottom: 1px solid rgba(255, 255, 255, 0.15);">
                <h5 class="modern-card-header-title text-white text-lg font-bold">
                    <i class="fas fa-book-open text-amber-400"></i>
                    {{ $selectedYear?->name ?? 'အားလုံး' }}
                    ပညာသင်နှစ်အတွက် ဆရာကိုင်/ဆရာလမ်းညွှန်
                    စာအုပ်များ ဖြန့်ဝေရန်ခွဲတမ်းစာရင်း
                </h5>
            </div>

            <div class="modern-card-body p-4 sm:p-6">
                {{-- Filter Form --}}
                <form method="GET" action="{{ route('teacher-guide-distributions.index') }}" class="m-0">

                    {{-- Top: ဘာသာရပ်ရှာဖွေရန် + actions --}}
                    <div class="flex flex-wrap items-end justify-between gap-3">
                        <div class="flex flex-wrap items-end gap-3" style="flex: 1; min-width: 260px;">
                            <div style="flex: 1; min-width: 220px; max-width: 360px;">
                                <label class="block text-sm font-extrabold mb-1.5" style="color: #105c3a;">ဘာသာရပ်ရှာဖွေရန်</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="fas fa-search" style="color: #16a34a;"></i>
                                    </span>
                                    <input type="text" name="search" class="modern-input text-sm font-medium"
                                        style="padding-left: 2.25rem;"
                                        value="{{ $search ?? '' }}"
                                        placeholder="ဘာသာရပ်ရှာရန်...">
                                </div>
                            </div>

                            <div class="flex items-end gap-2" style="padding-bottom: 1px;">
                                <button type="submit" class="btn-modern-primary">
                                    <i class="fas fa-filter"></i>
                                    စစ်ထုတ်ပါ
                                </button>
                                <a href="{{ route('teacher-guide-distributions.index') }}" class="btn-modern-secondary">
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
                            <a href="{{ route('teacher-guide-distributions.create') }}" class="btn-modern-primary">
                                <i class="fas fa-plus"></i>
                                ဖန်တီးပါ
                            </a>
                        </div>
                    </div>

                    {{-- Bottom: ပညာသင်နှစ် / အတန်း / အမျိုးအစား --}}
                    <div class="flex flex-wrap items-end gap-3 mt-4 pt-3 border-t border-slate-100">
                        <div style="flex: 2; min-width: 180px;">
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
                                <option value="ဆရာကိုင်" {{ $guideType === 'ဆရာကိုင်' ? 'selected' : '' }}>
                                    ဆရာကိုင်
                                </option>
                                <option value="ဆရာလမ်းညွှန်" {{ $guideType === 'ဆရာလမ်းညွှန်' ? 'selected' : '' }}>
                                    ဆရာလမ်းညွှန်
                                </option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Data Table Card --}}
        <div class="modern-table-container mt-4">
            <table class="table table-bordered table-sm text-center align-middle distribution-table mb-0">
                <thead>
                    <tr>
                        <th rowspan="2" class="col-seq">စဉ်</th>
                        <th rowspan="2" class="col-grade">အတန်း</th>
                        <th rowspan="2" class="col-book-name">ဘာသာရပ်အမည်</th>
                        <th rowspan="2" class="col-guide-type">အမျိုးအစား</th>

                        <th colspan="3">ခရိုင်ရရှိခွဲတမ်း</th>
                        <th colspan="3">KG to G-12 ခွဲတမ်း</th>
                        <th colspan="3">G-1 to G-5 ခွဲတမ်း</th>
                        <th colspan="3">၂ မျိုးပေါင်း ဖြန့်ဝေမှု</th>

                        <th rowspan="2" class="col-total">ဖြန့်ဝေမှု စုစုပေါင်း</th>
                        <th rowspan="2" class="col-total">ခရိုင်ရုံးလက်ကျန်</th>
                        <th rowspan="2" class="col-remark">မှတ်ချက်</th>
                        <th rowspan="2" class="col-action">လုပ်ဆောင်ချက်</th>
                    </tr>
                    <tr>
                        <th class="col-num-sm">KG-G12</th>
                        <th class="col-num-sm">G1-G5</th>
                        <th class="col-num-sm">ပေါင်း</th>

                        <th class="col-num-sm">မြန်အောင်</th>
                        <th class="col-num-sm">ကြံခင်း</th>
                        <th class="col-num-sm">အင်္ဂပူ</th>

                        <th class="col-num-sm">မြန်အောင်</th>
                        <th class="col-num-sm">ကြံခင်း</th>
                        <th class="col-num-sm">အင်္ဂပူ</th>

                        <th class="col-num-sm">မြန်အောင်</th>
                        <th class="col-num-sm">ကြံခင်း</th>
                        <th class="col-num-sm">အင်္ဂပူ</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($teacherGuides as $row)
                        <tr>
                            <td>{{ $row->sequence_no }}</td>

                            <td class="whitespace-nowrap font-medium text-slate-800">{{ $row->grade?->name }}</td>

                            <td class="text-start">
                                {{ $row->bookName?->name }}
                            </td>

                            <td>{{ $row->guide_type }}</td>

                            <td>{{ number_format($row->kg_to_g12_quota) }}</td>
                            <td>{{ number_format($row->g1_to_g5_quota) }}</td>
                            <td>{{ number_format($row->total_quota) }}</td>

                            <td>{{ number_format($row->kg_g12_myanaung_qty ?? 0) }}</td>
                            <td>{{ number_format($row->kg_g12_kyankhin_qty ?? 0) }}</td>
                            <td>{{ number_format($row->kg_g12_ingapu_qty ?? 0) }}</td>

                            <td>{{ number_format($row->g1_g5_myanaung_qty ?? 0) }}</td>
                            <td>{{ number_format($row->g1_g5_kyankhin_qty ?? 0) }}</td>
                            <td>{{ number_format($row->g1_g5_ingapu_qty ?? 0) }}</td>

                            <td>{{ number_format($row->total_myanaung_qty ?? 0) }}</td>
                            <td>{{ number_format($row->total_kyankhin_qty ?? 0) }}</td>
                            <td>{{ number_format($row->total_ingapu_qty ?? 0) }}</td>

                            <td>{{ number_format($row->distributed_total ?? 0) }}</td>
                            <td>{{ number_format($row->remaining_total ?? $row->total_quota) }}</td>

                            <td>{{ $row->remark }}</td>

                            <td class="whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5 justify-center">
                                    <a href="{{ route('teacher-guide-distributions.edit', $row->id) }}"
                                        class="btn-modern-warning" title="ပြင်ဆင်ပါ">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    @if (auth()->user()?->role === 'super')
                                        <form action="{{ route('teacher-guide-distributions.destroy', $row->id) }}"
                                            method="POST" class="d-inline m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-modern-danger" title="ဖျက်ပါ"
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
                            <td colspan="20" class="text-muted py-4">
                                Data မရှိသေးပါ။
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <style>
        .modern-table-container .distribution-table {
            min-width: 3300px;
            width: 100%;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .distribution-table th {
            font-size: 15px !important;
            vertical-align: middle !important;
            padding: 10px 12px !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            white-space: nowrap !important;
        }

        .distribution-table td {
            font-size: 14px !important;
            vertical-align: middle !important;
            padding: 10px 12px !important;
            border: 1px solid #e2e8f0 !important;
        }

        /* Dark green header — same as လက်ခံရရှိမှု */
        .distribution-table thead th {
            background-color: #072a1e !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            text-align: center !important;
            white-space: nowrap !important;
        }

        /* Zebra: every other row light green */
        .distribution-table tbody tr:nth-of-type(odd) {
            background-color: #ffffff !important;
        }

        .distribution-table tbody tr:nth-of-type(even) {
            background-color: #f0fdf4 !important;
        }

        .distribution-table tbody tr:hover {
            background-color: #d1fae5 !important;
        }

        .col-seq { width: 80px; min-width: 80px; text-align: center; }
        .col-grade { width: 190px; min-width: 180px; text-align: center; white-space: nowrap !important; }
        .col-book-name { width: 340px; min-width: 300px; text-align: left !important; }
        .col-guide-type { width: 150px; min-width: 140px; text-align: center; white-space: nowrap !important; }
        .col-num-sm { width: 160px; min-width: 145px; text-align: center; white-space: nowrap !important; }
        .col-total { width: 170px; min-width: 155px; text-align: center; white-space: nowrap !important; }
        .col-remark { width: 180px; min-width: 160px; text-align: center; }
        .col-action { width: 140px; min-width: 130px; text-align: center; white-space: nowrap !important; }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/file-saver/dist/FileSaver.min.js"></script>

    <script>
        async function exportExcel() {
            const workbook = new ExcelJS.Workbook();

            const sheet = workbook.addWorksheet(
                'Teacher Guide Distribution'
            );

            sheet.columns = [{
                    width: 8
                },
                {
                    width: 28
                },
                {
                    width: 30
                },
                {
                    width: 18
                },

                {
                    width: 14
                },
                {
                    width: 14
                },
                {
                    width: 14
                },

                {
                    width: 14
                },
                {
                    width: 14
                },
                {
                    width: 14
                },

                {
                    width: 14
                },
                {
                    width: 14
                },
                {
                    width: 14
                },

                {
                    width: 14
                },
                {
                    width: 14
                },
                {
                    width: 14
                },

                {
                    width: 18
                },
                {
                    width: 18
                },
                {
                    width: 22
                }
            ];

            sheet.mergeCells('A1:S1');

            sheet.getCell('A1').value =
                @json(($selectedYear?->name ?? 'အားလုံး') . ' ပညာသင်နှစ်အတွက် ဆရာကိုင်/ဆရာလမ်းညွှန် စာအုပ်များ ဖြန့်ဝေရန်ခွဲတမ်းစာရင်း');

            sheet.getCell('A1').font = {
                bold: true,
                size: 16
            };

            sheet.getCell('A1').alignment = {
                horizontal: 'center',
                vertical: 'middle',
                wrapText: true
            };

            sheet.getRow(1).height = 32;

            sheet.addRow([]);

            sheet.addRow([
                'စဉ်',
                'အတန်း',
                'ဘာသာရပ်အမည်',
                'အမျိုးအစား',
                'ခရိုင်ရရှိခွဲတမ်း',
                '',
                '',
                'KG to G-12 ခွဲတမ်း',
                '',
                '',
                'G-1 to G-5 ခွဲတမ်း',
                '',
                '',
                '၂ မျိုးပေါင်း ဖြန့်ဝေမှု',
                '',
                '',
                'ဖြန့်ဝေမှုစုစုပေါင်း',
                'ခရိုင်ရုံးယခင်နှစ်လက်ကျန်',
                'မှတ်ချက်'
            ]);

            sheet.addRow([
                '',
                '',
                '',
                '',
                'KG-G12',
                'G1-G5',
                'ပေါင်း',
                'မြန်အောင်',
                'ကြံခင်း',
                'အင်္ဂပူ',
                'မြန်အောင်',
                'ကြံခင်း',
                'အင်္ဂပူ',
                'မြန်အောင်',
                'ကြံခင်း',
                'အင်္ဂပူ',
                '',
                '',
                ''
            ]);

            sheet.mergeCells('A3:A4');
            sheet.mergeCells('B3:B4');
            sheet.mergeCells('C3:C4');
            sheet.mergeCells('D3:D4');

            sheet.mergeCells('E3:G3');
            sheet.mergeCells('H3:J3');
            sheet.mergeCells('K3:M3');
            sheet.mergeCells('N3:P3');

            sheet.mergeCells('Q3:Q4');
            sheet.mergeCells('R3:R4');
            sheet.mergeCells('S3:S4');

            @foreach ($teacherGuides as $row)
                sheet.addRow([
                    {{ $row->sequence_no }},

                    {!! json_encode($row->grade?->name) !!},

                    {!! json_encode($row->bookName?->name) !!},

                    {!! json_encode($row->guide_type) !!},

                    {{ $row->kg_to_g12_quota ?? 0 }},
                    {{ $row->g1_to_g5_quota ?? 0 }},
                    {{ $row->total_quota ?? 0 }},

                    {{ $row->kg_g12_myanaung_qty ?? 0 }},
                    {{ $row->kg_g12_kyankhin_qty ?? 0 }},
                    {{ $row->kg_g12_ingapu_qty ?? 0 }},

                    {{ $row->g1_g5_myanaung_qty ?? 0 }},
                    {{ $row->g1_g5_kyankhin_qty ?? 0 }},
                    {{ $row->g1_g5_ingapu_qty ?? 0 }},

                    {{ $row->total_myanaung_qty ?? 0 }},
                    {{ $row->total_kyankhin_qty ?? 0 }},
                    {{ $row->total_ingapu_qty ?? 0 }},

                    {{ $row->distributed_total ?? 0 }},

                    {{ $row->remaining_total ?? ($row->total_quota ?? 0) }},

                    {!! json_encode($row->remark) !!}
                ]);
            @endforeach

            sheet.eachRow((row, rowNumber) => {
                row.eachCell({
                    includeEmpty: true
                }, (cell) => {
                    cell.alignment = {
                        horizontal: 'center',
                        vertical: 'middle',
                        wrapText: true
                    };

                    cell.border = {
                        top: {
                            style: 'thin'
                        },
                        left: {
                            style: 'thin'
                        },
                        bottom: {
                            style: 'thin'
                        },
                        right: {
                            style: 'thin'
                        }
                    };
                });

                if (rowNumber === 3 || rowNumber === 4) {
                    row.font = {
                        bold: true
                    };

                    row.height = 28;
                }
            });

            sheet.getColumn(2).alignment = {
                horizontal: 'left',
                vertical: 'middle',
                wrapText: true
            };

            sheet.getColumn(3).alignment = {
                horizontal: 'left',
                vertical: 'middle',
                wrapText: true
            };

            const buffer = await workbook.xlsx.writeBuffer();

            saveAs(
                new Blob([buffer], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                }),
                'teacher-guide-distributions.xlsx'
            );
        }
    </script>
@endsection
