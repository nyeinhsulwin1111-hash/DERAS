@extends('layouts.master')

@section('content')
    <div class="container-fluid py-4 px-4">
        @if (!isset($teacherGuideIssue))
            <div class="alert alert-danger rounded-3 border-0 shadow-sm">
                <i class="fas fa-exclamation-triangle me-2"></i>
                ပြင်ဆင်မည့် အချက်အလက်ကို ရှာမတွေ့ပါ။
            </div>
        @else
            <div class="card border-0" style="border-radius: 16px; box-shadow: 0 4px 28px rgba(16, 92, 58, 0.11); overflow: hidden;">
                <div class="card-header bg-success text-white py-3 px-4">
                    <h5 class="mb-0 fw-bold">ဆရာကိုင်/ဆရာလမ်းညွှန် ဖြန့်ဝေစာရင်းပြင်ဆင်ရန်</h5>
                </div>
                <div class="card-body" style="padding: 24px 28px;">
                    <form method="POST" action="{{ route('teacher-guide-issues.update', $teacherGuideIssue->id) }}">
                        @method('PUT')
                        @include('teacher-guide-issues._form')
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection

