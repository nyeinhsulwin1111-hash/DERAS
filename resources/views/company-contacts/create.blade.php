@extends('layouts.master')

@section('content')
    <div class="container-fluid py-4 px-4">
        <div class="card border-0 mx-auto" style="max-width: 850px; border-radius: 16px; box-shadow: 0 4px 28px rgba(16, 92, 58, 0.11); overflow: hidden;">
            <div class="card-header bg-success text-white py-3 px-4">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-building me-2"></i>ကုမ္ပဏီအချက်အလက် ဖန်တီးရန်
                </h5>
            </div>

            <div class="card-body" style="padding: 24px 28px;">
                <form method="POST" action="{{ route('company-contacts.store') }}">
                    @include('company-contacts._form')
                </form>
            </div>
        </div>
    </div>
@endsection
