@extends('layouts.master')

@section('content')
    <div class="app-page-container space-y-6">

        <!-- Top Metric Cards Row (Original Labels & Formulas Restored) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <!-- Metric 1: ခွဲတမ်းစာအုပ် -->
            <div class="modern-card p-5 border-l-4 border-l-emerald-600 hover:scale-[1.02] transition-transform">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-sm sm:text-base font-bold text-slate-600 tracking-wide block mb-1">
                            ခွဲတမ်းစာအုပ်
                        </span>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-emerald-900 m-0">
                            {{ number_format($summary['total_quota_books']) }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-sm">
                        <i class="fas fa-book-open"></i>
                    </div>
                </div>
            </div>

            <!-- Metric 2: ဖြန့်ဝေပြီး -->
            <div class="modern-card p-5 border-l-4 border-l-teal-600 hover:scale-[1.02] transition-transform">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-sm sm:text-base font-bold text-slate-600 tracking-wide block mb-1">
                            ဖြန့်ဝေပြီး
                        </span>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-teal-700 m-0">
                            {{ number_format($summary['distributed_books']) }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl shadow-sm">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>

            <!-- Metric 3: ယခုနှစ်လက်ကျန် -->
            <div class="modern-card p-5 border-l-4 border-l-amber-500 hover:scale-[1.02] transition-transform">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-sm sm:text-base font-bold text-slate-600 tracking-wide block mb-1">
                            လက်ကျန်
                        </span>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-amber-600 m-0">
                            {{ number_format($summary['remaining_books']) }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shadow-sm">
                        <i class="fas fa-boxes"></i>
                    </div>
                </div>
            </div>

            <!-- Metric 4: ကျောင်းသား -->
            <div class="modern-card p-5 border-l-4 border-l-indigo-600 hover:scale-[1.02] transition-transform">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-sm sm:text-base font-bold text-slate-600 tracking-wide block mb-1">
                            ကျောင်းသား
                        </span>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-indigo-700 m-0">
                            {{ number_format($summary['students']) }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shadow-sm">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
            </div>

        </div>


        <!-- SECTION 1: ပြဌာန်းစာအုပ် Donut & Bar Chart -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Donut Chart: ပြဌာန်းစာအုပ် ထုတ်ပေးမှု -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <h6 class="modern-card-header-title text-base">
                        <i class="fas fa-chart-pie"></i>
                        မြို့နယ်အလိုက်ပြဌာန်းစာအုပ်ထုတ်ပေးမှုအခြေအနေ
                    </h6>
                </div>
                <div class="p-4 flex items-center justify-center" style="min-height: 340px;">
                    <canvas id="pieChart" style="max-height: 320px;"></canvas>
                </div>
            </div>

            <!-- Bar Chart: မြို့နယ်အလိုက် ကျောင်းသားနှင့် ပြဌာန်းစာအုပ် ဖြန့်ဝေမှု -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <h6 class="modern-card-header-title text-base">
                        <i class="fas fa-chart-bar"></i>
                        မြို့နယ်အလိုက် ကျောင်းသားနှင့် ပြဌာန်းစာအုပ် ဖြန့်ဝေမှု
                    </h6>
                </div>
                <div class="p-4 flex items-center justify-center" style="min-height: 340px;">
                    <canvas id="barChart" style="max-height: 320px;"></canvas>
                </div>
            </div>

        </div>


        <!-- SECTION 2: ခဲတံ၊ ဘောပင်၊ ဝတ်စုံ Donut & Bar Chart -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Donut Chart: မူလတန်း၊ အလယ်တန်း၊ အထက်တန်း အလိုက် ကျောင်းသားဦးရေ -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <h6 class="modern-card-header-title text-base">
                        <i class="fas fa-chart-pie"></i>
                        မူလတန်း၊ အလယ်တန်း၊ အထက်တန်း အလိုက် ကျောင်းသားဦးရေ
                    </h6>
                </div>
                <div class="p-4 flex items-center justify-center" style="min-height: 340px;">
                    <canvas id="quotaDonutChart" style="max-height: 320px;"></canvas>
                </div>
            </div>

            <!-- Bar Chart: မြို့နယ်အလိုက် ခဲတံ၊ ဘောပင်၊ ဝတ်စုံ ဖြန့်ဝေရန် ကျောင်းသားဦးရေ -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <h6 class="modern-card-header-title text-base">
                        <i class="fas fa-chart-bar"></i>
                        မြို့နယ်အလိုက် ခဲတံ၊ ဘောပင်၊ ဝတ်စုံ ဖြန့်ဝေရန် ကျောင်းသားဦးရေ
                    </h6>
                </div>
                <div class="p-4 flex items-center justify-center" style="min-height: 340px;">
                    <canvas id="quotaBarChart" style="max-height: 320px;"></canvas>
                </div>
            </div>

        </div>

    </div>
@endsection

@section('script-code')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            Chart.defaults.font.family = "Noto Sans Myanmar";
            Chart.register(ChartDataLabels);

            /*
            |--------------------------------------------------------------------------
            | 1. Pie/Doughnut Chart (ပြဌာန်းစာအုပ် ထုတ်ပေးမှု)
            |--------------------------------------------------------------------------
            */
            const pieData = @json($distributionChart);
            const pieTotal = pieData.data.reduce(
                (total, value) => total + Number(value),
                0
            );

            new Chart(
                document.getElementById('pieChart'), {
                    type: 'doughnut',
                    data: {
                        labels: pieData.labels,
                        datasets: [{
                            data: pieData.data,
                            backgroundColor: [
                                '#059669', // Emerald (match quota donut)
                                '#0284c7', // Sky
                                '#d97706'  // Amber
                            ],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '55%',
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    font: {
                                        family: 'Noto Sans Myanmar'
                                    }
                                }
                            },
                            datalabels: {
                                color: '#ffffff',
                                font: {
                                    weight: 'bold',
                                    size: 14
                                },
                                formatter: function(value) {
                                    if (pieTotal === 0) return '0%';
                                    let percentage = (value / pieTotal) * 100;
                                    return percentage.toFixed(1) + '%';
                                }
                            }
                        }
                    },
                    plugins: [{
                        id: 'centerText',
                        beforeDraw(chart) {
                            const { ctx } = chart;
                            ctx.save();
                            ctx.font = 'bold 22px Noto Sans Myanmar';
                            ctx.fillStyle = '#1f2937';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            const chartArea = chart.chartArea;
                            if (chartArea) {
                                ctx.fillText(
                                    pieTotal.toLocaleString(),
                                    (chartArea.left + chartArea.right) / 2,
                                    (chartArea.top + chartArea.bottom) / 2
                                );
                            }
                            ctx.restore();
                        }
                    }]
                }
            );


            /*
            |--------------------------------------------------------------------------
            | 2. Bar Chart (မြို့နယ်အလိုက် ကျောင်းသားနှင့် ပြဌာန်းစာအုပ် ဖြန့်ဝေမှု)
            | Flat sharp rectangle bars without top rounding & without datalabels
            |--------------------------------------------------------------------------
            */
            const barData = @json($barChart);

            new Chart(
                document.getElementById('barChart'), {
                    type: 'bar',
                    data: {
                        labels: barData.labels,
                        datasets: [{
                                label: 'ကျောင်းသား',
                                data: barData.students,
                                backgroundColor: '#059669', // Emerald (match donut)
                                borderRadius: 0,
                                maxBarThickness: 32
                            },
                            {
                                label: 'ဖြန့်ဝေ',
                                data: barData.distributed,
                                backgroundColor: '#0284c7', // Sky (match donut)
                                borderRadius: 0,
                                maxBarThickness: 32
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top'
                            },
                            datalabels: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    maxRotation: 0,
                                    minRotation: 0,
                                    autoSkip: false,
                                    font: {
                                        family: 'Noto Sans Myanmar',
                                        size: 12,
                                        weight: 'bold'
                                    },
                                    color: '#334155'
                                }
                            },
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                }
            );


            /*
            |--------------------------------------------------------------------------
            | 3. Donut Chart (မူလတန်း၊ အလယ်တန်း၊ အထက်တန်း)
            |--------------------------------------------------------------------------
            */
            const quotaDonutData = @json($quotaDonutChart);
            const quotaDonutTotal = quotaDonutData.data.reduce(
                (total, value) => total + Number(value),
                0
            );

            new Chart(
                document.getElementById('quotaDonutChart'), {
                    type: 'doughnut',
                    data: {
                        labels: quotaDonutData.labels,
                        datasets: [{
                            data: quotaDonutData.data,
                            backgroundColor: [
                                '#059669', // မူလတန်း - Emerald
                                '#0284c7', // အလယ်တန်း - Sky
                                '#d97706', // အထက်တန်း - Amber
                                '#0d9488'  // စက်၊စိုက်၊မွေး - Teal
                            ],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '55%',
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    font: {
                                        family: 'Noto Sans Myanmar',
                                        size: 13
                                    }
                                }
                            },
                            datalabels: {
                                color: '#ffffff',
                                font: {
                                    weight: 'bold',
                                    size: 13
                                },
                                formatter: function(value) {
                                    if (quotaDonutTotal === 0 || value === 0) return '';
                                    let percentage = (value / quotaDonutTotal) * 100;
                                    return percentage.toFixed(1) + '%';
                                }
                            }
                        }
                    },
                    plugins: [{
                        id: 'centerTextQuota',
                        beforeDraw(chart) {
                            const { ctx } = chart;
                            ctx.save();
                            ctx.font = 'bold 22px Noto Sans Myanmar';
                            ctx.fillStyle = '#1f2937';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            const chartArea = chart.chartArea;
                            if (chartArea) {
                                ctx.fillText(
                                    quotaDonutTotal.toLocaleString(),
                                    (chartArea.left + chartArea.right) / 2,
                                    (chartArea.top + chartArea.bottom) / 2
                                );
                            }
                            ctx.restore();
                        }
                    }]
                }
            );


            /*
            |--------------------------------------------------------------------------
            | 4. Bar Chart (မြို့နယ်အလိုက် ခဲတံ၊ ဘောပင်၊ ဝတ်စုံ ဖြန့်ဝေရန် ကျောင်းသားဦးရေ)
            | Flat sharp rectangle bars without top rounding & without datalabels
            |--------------------------------------------------------------------------
            */
            const quotaBarData = @json($quotaBarChart);

            new Chart(
                document.getElementById('quotaBarChart'), {
                    type: 'bar',
                    data: {
                        labels: quotaBarData.labels,
                        datasets: [
                            {
                                label: 'ဖြန့်ဝေရန် ကျောင်းသားဦးရေ',
                                data: quotaBarData.data,
                                backgroundColor: [
                                    '#059669', // မြန်အောင်
                                    '#0284c7', // ကြံခင်း
                                    '#d97706', // အင်္ဂပူ
                                    '#334155'  // စုစုပေါင်း
                                ],
                                borderRadius: 0,
                                maxBarThickness: 36,
                                barPercentage: 0.55,
                                categoryPercentage: 0.65
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            datalabels: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    maxRotation: 0,
                                    minRotation: 0,
                                    autoSkip: false,
                                    font: {
                                        family: 'Noto Sans Myanmar',
                                        size: 12,
                                        weight: 'bold'
                                    },
                                    color: '#334155'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#f1f5f9'
                                },
                                ticks: {
                                    callback: function(val) {
                                        return val.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                }
            );

        });
    </script>
@endsection
