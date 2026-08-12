@extends('authentication.layouts.master')

@section('content')
    <style>
        body {
            background: #f4f7f6;
        }

        .auth-card {
            border-radius: 28px;
            overflow: hidden;
        }

        .top-bar {
            height: 6px;
            background: #117729;
        }

        .brand-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .form-control {
            border-radius: 14px;
            padding: 12px 14px;
        }

        .form-control:focus {
            border-color: #117729;
            box-shadow: 0 0 0 0.15rem rgba(23, 58, 59, 0.15);
        }

        .btn-custom {
            background: #117729;
            border: none;
            border-radius: 14px;
        }

        .btn-custom:hover {
            background: #117747;
        }

        .gold-line {
            width: 80px;
            height: 2px;
            background: #f1c40f;
            margin: 10px auto 0;
            border-radius: 50px;
        }
    </style>

    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">

            <div class="col-xl-6 col-lg-7 col-md-9 col-sm-10">

                <div class="border-0 shadow-lg card auth-card">

                    <!-- Top Bar -->
                    <div class="top-bar"></div>

                    <div class="p-5 card-body">

                        <div class="mb-4 text-center">

                            <img src="{{ asset('image/logo.jpg') }}"
                                class="border-4 shadow-sm rounded-circle border-success brand-img">

                            <h4 class="my-3 fw-bold" style="color:#117729;">
                                ပြန်လည်ကြိုဆိုပါတယ်
                            </h4>

                            <div class="my-2 gold-line"></div>
                            <p>သင့်အကောင့်သို့ ဝင်ရောက်ပါ</p>

                        </div>

                        <!-- Form -->
                        <form method="POST" action="">
                            @csrf
                            <!-- Email -->
                            <div class="my-3">
                                <input type="email" class="form-control" placeholder="သင့်အီးမေးလ်လိပ်စာကို  ထည့်ပါ"
                                    name="email" value="{{ old('email') }}">
                                <div class="mt-2">
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <!-- Password -->

                            <div class="mb-4">
                                <div style="position: relative; width: 100%;">
                                    <input type="password" id="login_password" class="form-control" name="password"
                                        placeholder="သင့်စကားဝှက်ကို ထည့်ပါ" readonly
                                        onfocus="setTimeout(()=>{this.removeAttribute('readonly');},100);"
                                        style="padding-right: 40px;">
                                    <i class="fas fa-eye-slash" id="login_icon"
                                        onclick="togglePass('login_password', 'login_icon')"
                                        style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #6c757d; z-index: 5;"></i>
                                    <div class="mt-2">
                                        @error('password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Login Button -->
                                <div class="mt-4 d-grid">
                                    <button type="submit" class="py-2 text-white btn btn-custom fw-bold form-control">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        စနစ်အတွင်းသို့ဝင်မည်
                                    </button>
                                </div>

                                <div class="my-2 d-flex align-items-center">

                                    <div class="flex-grow-1" style="height:1px; background:#ddd;"></div>

                                    <span class="px-3 text-muted small fw-semibold">
                                        သို့မဟုတ်
                                    </span>

                                    <div class="flex-grow-1" style="height:1px; background:#ddd;"></div>

                                </div>
                                <div class="mt-4 text-center small">
                                    <span class="text-muted">အကောင့်မရှိသေးဘူးလား?</span>
                                    <a href="{{ route('register') }}" class="text-success fw-bold text-decoration-none">
                                        အကောင့်ဖွင့်မည်
                                    </a>
                                </div>

                            </div>

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
