@extends('layouts.master')

@section('content')
    <div class="container-fluid py-4 px-4">
        @php $plan = $plan ?? ($allocationPlan ?? null); @endphp
        @if (!$plan)
            <div class="alert alert-danger rounded-3 border-0 shadow-sm">
                <i class="fas fa-exclamation-triangle me-2"></i>
                ပြင်ဆင်မည့် ခွဲတမ်းအချက်အလက်ကို ရှာမတွေ့ပါ။
            </div>
        @else
            <div class="card border-0" style="border-radius: 16px; box-shadow: 0 4px 28px rgba(16, 92, 58, 0.11); overflow: hidden;">
                <div class="card-header bg-success text-white py-3 px-4">
                    <h5 class="mb-0 fw-bold">ပြဌာန်းခွဲတမ်းတွက်ချက်မှုပြင်ဆင်ရန်</h5>
                </div>
                <div class="card-body" style="padding: 24px 28px;">
                    <form method="POST" action="{{ route('allocation-plans.update', $plan->id) }}">
                        @csrf
                        @method('PUT')
                        @include('allocation-plans._form')
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection
