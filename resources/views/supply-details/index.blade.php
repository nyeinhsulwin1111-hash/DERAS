@extends('layouts.master')

@section('content')
    <div class="app-page-container">

        {{-- Filter Card: Title + Search --}}
        <div class="modern-card">
            <div class="modern-card-header" style="background: #072a1e !important; background-image: none !important; color: #ffffff !important; border-bottom: 1px solid rgba(255, 255, 255, 0.15);">
                <h5 class="modern-card-header-title text-white text-lg font-bold">
                    <i class="fas fa-truck-loading text-amber-400"></i>
                    {{ $selectedYear?->name ?? '' }} ပညာသင်နှစ်အတွက် သင်ထောက်ကူပစ္စည်းများ ထုတ်ပေးမှု စာရင်း
                </h5>
            </div>

            <div class="modern-card-body p-4 sm:p-6">
                <form method="GET" class="m-0">
                    <div class="flex flex-wrap items-end justify-between gap-3">

                        <div class="flex flex-wrap items-end gap-3" style="flex: 1; min-width: 260px;">
                            <div style="min-width: 160px; max-width: 220px; flex: 1;">
                                <label class="block text-sm font-extrabold mb-1.5" style="color: #105c3a;">ပညာသင်နှစ်</label>
                                <select name="academic_year_id" class="modern-select text-sm font-medium" onchange="this.form.submit()">
                                    <option value="">--ရွေးချယ်ပါ--</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year->id }}"
                                            {{ (string) $yearId === (string) $year->id ? 'selected' : '' }}>
                                            {{ $year->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div style="min-width: 140px; max-width: 200px; flex: 1;">
                                <label class="block text-sm font-extrabold mb-1.5" style="color: #105c3a;">မြို့နယ်</label>
                                <select name="township_id" class="modern-select text-sm font-medium" onchange="this.form.submit()">
                                    <option value="">--ရွေးချယ်ပါ--</option>
                                    @foreach ($townships as $township)
                                        <option value="{{ $township->id }}"
                                            {{ (string) $townshipId === (string) $township->id ? 'selected' : '' }}>
                                            {{ $township->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div style="min-width: 140px; max-width: 200px; flex: 1;">
                                <label class="block text-sm font-extrabold mb-1.5" style="color: #105c3a;">အတန်း</label>
                                <select name="grade_id" class="modern-select text-sm font-medium" onchange="this.form.submit()">
                                    <option value="">--ရွေးချယ်ပါ--</option>
                                    @foreach ($grades as $grade)
                                        <option value="{{ $grade->id }}"
                                            {{ (string) $gradeId === (string) $grade->id ? 'selected' : '' }}>
                                            {{ $grade->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex items-end gap-2 ms-auto" style="padding-bottom: 1px;">
                            <a href="{{ route('supply-details.index') }}" class="btn-modern-secondary">
                                <i class="fas fa-redo"></i>
                                ပြန်လည်သတ်မှတ်
                            </a>
                            <button type="button" class="btn-modern-excel" onclick="exportSupplyDetails()">
                                <i class="fas fa-file-excel"></i>
                                Excel ထုတ်ပါ
                            </button>
                            <a href="{{ route('supply-details.create') }}" class="btn-modern-primary">
                                <i class="fas fa-plus"></i>
                                ဖန်တီးပါ
                            </a>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        @php
            $exportRows = [];

            foreach ($details as $row) {
                $unit = (int) ($row->unit ?? 0);
                $issuedTotal = (int) ($row->issued_total ?? 0);

                $packageCount = $unit > 0 ? intdiv($issuedTotal, $unit) : 0;
                $looseCount = $unit > 0 ? $issuedTotal % $unit : 0;

                $exportRows[] = [
                    'sequence_no' => $row->sequence_no,
                    'academic_year' => $row->academicYear?->name,
                    'township' => $row->township?->name,
                    'grade' => $row->grade?->name,
                    'item' => $row->item?->name,
                    'unit' => $unit,
                    'issued_total' => $issuedTotal,
                    'package_count' => $packageCount,
                    'loose_count' => $looseCount,
                ];
            }
        @endphp

        {{-- Data Table (outside card, below) --}}
        <div class="modern-table-container mt-4">
            <table class="modern-table supply-detail-table">
                <thead>
                    <tr>
                        <th>စဉ်</th>
                        <th>ပညာသင်နှစ်</th>
                        <th>မြို့နယ်</th>
                        <th>အတန်း</th>
                        <th>ပစ္စည်းအမျိုးအမည်</th>
                        <th>လက်ခံရရှိမှု (Unit)</th>
                        <th>ထုတ်ပေးမှု (ဦးရေပေါင်း)</th>
                        <th>ပုံး/အိတ်</th>
                        <th>အပြေ</th>
                        <th>လုပ်ဆောင်ချက်</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($details as $row)
                        @php
                            $unit = (int) ($row->unit ?? 0);
                            $issuedTotal = (int) ($row->issued_total ?? 0);

                            $packageCount = $unit > 0 ? intdiv($issuedTotal, $unit) : 0;
                            $looseCount = $unit > 0 ? $issuedTotal % $unit : 0;
                        @endphp

                        <tr>
                            <td class="font-mono text-slate-500">{{ $row->sequence_no }}</td>
                            <td class="whitespace-nowrap font-medium text-slate-800">{{ $row->academicYear?->name }}</td>
                            <td class="whitespace-nowrap font-medium text-slate-800">{{ $row->township?->name }}</td>
                            <td class="whitespace-nowrap font-medium text-slate-800">{{ $row->grade?->name }}</td>
                            <td class="text-left font-medium text-slate-800">{{ $row->item?->name }}</td>
                            <td class="font-mono text-slate-600">{{ number_format($unit) }}</td>
                            <td class="font-mono text-slate-600">{{ number_format($issuedTotal) }}</td>
                            <td class="font-mono font-semibold text-slate-800">{{ number_format($packageCount) }}</td>
                            <td class="font-mono font-semibold text-emerald-700">{{ number_format($looseCount) }}</td>
                            <td class="whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('supply-details.edit', $row->id) }}" class="btn-modern-warning" title="ပြင်ဆင်ပါ">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    @if (auth()->user()?->role === 'super')
                                        <form action="{{ route('supply-details.destroy', $row->id) }}" method="POST" class="d-inline m-0">
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
                            <td colspan="10" class="text-muted py-4 text-center">
                                အချက်အလက် မရှိသေးပါ။
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
        async function exportSupplyDetails() {
            const rows = @json($exportRows);
            const selectedYear = @json($selectedYear?->name ?? '');

            const workbook = new ExcelJS.Workbook();
            const sheet = workbook.addWorksheet('Supply Details');

            sheet.views = [{ showGridLines: true }];

            sheet.columns = [
                { width: 8 }, { width: 18 }, { width: 18 }, { width: 14 },
                { width: 35 }, { width: 20 }, { width: 25 }, { width: 14 }, { width: 14 },
            ];

            sheet.mergeCells('A1:I1');
            sheet.getCell('A1').value =
                (selectedYear ? selectedYear + ' ' : '') +
                'ပညာသင်နှစ်အတွက် သင်ထောက်ကူပစ္စည်းများ ထုတ်ပေးမှု အသေးစိတ်စာရင်း';

            sheet.getCell('A1').font = { bold: true, size: 14 };
            sheet.getCell('A1').alignment = { horizontal: 'center', vertical: 'middle' };

            sheet.getRow(3).values = [
                'စဉ်', 'ပညာသင်နှစ်', 'မြို့နယ်', 'အတန်း', 'ပစ္စည်းအမျိုးအမည်',
                'လက်ခံရရှိမှု (Unit)', 'ထုတ်ပေးမှု (ဦးရေပေါင်း)', 'ပုံး/အိတ်', 'အပြေ',
            ];

            rows.forEach((row, index) => {
                sheet.getRow(index + 4).values = [
                    row.sequence_no, row.academic_year, row.township, row.grade, row.item,
                    row.unit, row.issued_total, row.package_count, row.loose_count,
                ];
            });

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
            saveAs(new Blob([buffer]), `supply_details_${selectedYear || 'all'}.xlsx`);
        }
    </script>
@endsection

@push('styles')
    <style>
        body {
            background: #f4f6f8;
        }

        .supply-detail-table {
            font-size: 13px;
            background: #ffffff;
            color: #4f5870;
        }

        .supply-detail-table th,
        .supply-detail-table td {
            border: 1px solid #cbd5e1 !important;
            vertical-align: middle !important;
            padding: 6px 8px;
        }

        .supply-detail-table thead th {
            background: #e5e5ea;
            font-weight: 700;
            white-space: nowrap;
        }

        .supply-detail-table tbody tr:hover {
            background: #f1f8f4;
        }
    </style>
@endpush
