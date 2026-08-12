@extends('authentication.layouts.master')

@section('content')
    <style>
        body {
            background: #f4f7f6;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 15px;
            font-family: sans-serif;
        }

        .auth-card {
            width: 100%;
            max-width: 680px;
            border: none;
            border-radius: 24px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .top-bar {
            height: 6px;
            background: #117729;
        }

        .card-body {
            padding: 2.3rem;
        }

        .brand-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .title {
            color: #117729;
            font-weight: bold;
        }

        .gold-line {
            width: 60px;
            height: 3px;
            background: #f1c40f;
            margin: 8px auto;
            border-radius: 30px;
        }

        .subtitle {
            color: #777;
            font-size: 14px;
            margin-bottom: 0;
        }

        label {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            padding: 10px 14px;
            border: 1px solid #ced4da;
            background: #fafafa;
            font-size: 14px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #117729 !important;
            box-shadow: 0 0 0 0.15rem rgba(17, 119, 41, 0.15) !important;
        }

        .form-select {
            appearance: auto;
        }

        .form-check-input:checked {
            background-color: #117729;
            border-color: #117729;
        }

        .btn-custom {
            background: #117729;
            border: none;
            border-radius: 10px;
            padding: 11px;
            color: white;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-custom:hover {
            background: #0d5e20;
        }

        .form-section {
            margin-bottom: 15px;
        }

        .login-text {
            font-size: 14px;
        }

        .login-link:hover {
            text-decoration: underline;
        }
    </style>

    <div class="container">
        <div class="row justify-content-center">

            <div class="card auth-card">

                <div class="top-bar"></div>

                <div class="card-body">

                    <div class="mb-4 text-center">

                        <img src="{{ asset('image/logo.jpg') }}"
                            class="border-4 shadow-sm rounded-circle border-success brand-img">

                        <h4 class="mt-3 title">
                            အကောင့်အသစ်ဖွင့်ရန်
                        </h4>

                        <div class="gold-line"></div>

                        <p class="subtitle">
                            အောက်ပါအချက်အလက်များကို ဖြည့်သွင်းပါ
                        </p>

                    </div>

                    <form method="POST" action="{{ url('register') }}">

                        @csrf

                        <div class="row g-3 form-section">

                            <div class="col-md-6">

                                <label>အမည်</label>

                                <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                    placeholder="အသုံးပြုသူ၏အမည်ကို ထည့်ပါ">

                                @error('name')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>

                            <div class="col-md-6">

                                <label>ဖုန်းနံပါတ်</label>

                                <input type="text" class="form-control" name="phone" value="{{ old('phone') }}"
                                    placeholder="ဖုန်းနံပါတ်ကို ထည့်ပါ">

                                @error('phone')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>

                        </div>

                        <div class="mb-4 form-section">

                            <label for="position" class="form-label d-block">
                                ရာထူး
                            </label>

                            <select id="position" class="mt-1 form-select w-100" name="position" required>

                                <option value="" disabled {{ old('position') ? '' : 'selected' }}>
                                    မိမိ၏ရာထူးကိုရွေးပါ
                                </option>

                                <option value="manager" {{ old('position') === 'manager' ? 'selected' : '' }}>
                                    ဒုဦးစီးမှူး(စာရင်းအင်း)
                                </option>

                                <option value="staff" {{ old('position') === 'staff' ? 'selected' : '' }}>
                                    ဦးစီးအရာရှိ(ဝန်ထမ်း)
                                </option>

                                <option value="supervisor" {{ old('position') === 'supervisor' ? 'selected' : '' }}>
                                    Supervisor
                                </option>

                                <option value="clerk" {{ old('position') === 'clerk' ? 'selected' : '' }}>
                                    Clerk
                                </option>

                            </select>

                            @error('position')
                                <div class="mt-1">
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                </div>
                            @enderror

                        </div>

                        {{-- Role --}}
                        <div class="mb-4 form-section">

                            <label for="role" class="form-label d-block">
                                အသုံးပြုသူအဆင့်
                            </label>

                            <select id="role" class="mt-1 form-select w-100" name="role" required>

                                <option value="" disabled {{ old('role') ? '' : 'selected' }}>
                                    အသုံးပြုသူအဆင့်ကို ရွေးပါ
                                </option>
                                <option value="super" {{ old('role') === 'super' ? 'selected' : '' }}>
                                    Super Admin
                                </option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                                    Admin
                                </option>
                            </select>

                            @error('role')
                                <div class="mt-1">
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                </div>
                            @enderror

                        </div>

                        <div class="form-section">

                            <label>အီးမေးလ်</label>

                            <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                                placeholder="သင့်အီးမေးလ်လိပ်စာကို ထည့်ပါ">

                            @error('email')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>

                        <div class="mb-4 row g-3">

                            <div class="col-md-6">

                                <label class="form-label">
                                    စကားဝှက်
                                </label>

                                <div style="position: relative; width: 100%;">

                                    <input type="password" id="password" class="form-control w-100" name="password"
                                        placeholder="သင့်စကားဝှက်ကို ထည့်ပါ" readonly
                                        onfocus="setTimeout(()=>{this.removeAttribute('readonly');},100);"
                                        style="padding-right: 40px; height: 45px;">

                                    <i class="fas fa-eye-slash" id="icon1" onclick="togglePass('password', 'icon1')"
                                        style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #6c757d; z-index: 5;">
                                    </i>

                                </div>

                                @error('password')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    စကားဝှက်ကိုအတည်ပြုပါ
                                </label>

                                <div style="position: relative; width: 100%;">

                                    <input type="password" id="password_confirmation" class="form-control w-100"
                                        name="password_confirmation" placeholder="သင့်စကားဝှက်ကို အတည်ပြုပါ" readonly
                                        onfocus="setTimeout(()=>{this.removeAttribute('readonly');},100);"
                                        autocomplete="off" style="padding-right: 40px; height: 45px;">

                                    <i class="fas fa-eye-slash" id="icon2"
                                        onclick="togglePass('password_confirmation', 'icon2')"
                                        style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #6c757d; z-index: 5;">
                                    </i>

                                </div>

                                @error('password_confirmation')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>

                        </div>

                        <div class="mt-4 d-grid">

                            <button type="submit" class="p-2 btn btn-custom form-control">
                                အကောင့်ဖွင့်မည်
                            </button>

                        </div>

                        <div class="mt-4 text-center small">

                            <span class="text-muted login-text">
                                အကောင့်ရှိပြီးသားလား?
                            </span>

                            <a href="{{ route('login') }}" class="mx-3 text-success fw-bold text-decoration-none">
                                ဝင်မည်
                            </a>

                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePass(inputId, iconId) {

            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);


            if (input.type === "password") {

                input.type = "text";

                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");

            } else {

                input.type = "password";

                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");

            }

        }
    </script>
@endsection
