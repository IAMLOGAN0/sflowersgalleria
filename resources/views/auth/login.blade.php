@extends('frontend.layouts.master')

@section('title')
{{$settings->site_name}} || Login
@endsection

@section('content')

    <!--============================
         BREADCRUMB START
    ==============================-->
    <section id="wsus__breadcrumb">
        {{-- <div class="wsus_breadcrumb_overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h4>login / register</h4>
                        <ul>
                            <li><a href="#">home</a></li>
                            <li><a href="#">login / register</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div> --}}
    </section>
    <!--============================
        BREADCRUMB END
    ==============================-->


    <!--============================
       LOGIN/REGISTER PAGE START
    ==============================-->
    <section class="py-5 mt-6" style="background: #f5f7fa;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-4">
                            <ul class="nav nav-tabs mb-4 justify-content-center" id="authTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active fw-semibold" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab">Login</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link fw-semibold" id="signup-tab" data-bs-toggle="tab" data-bs-target="#signup" type="button" role="tab">Sign Up</button>
                                </li>
                            </ul>

                            <div class="tab-content" id="authTabsContent">
                                <!-- Login -->
                                <div class="tab-pane fade show active" id="login" role="tabpanel">
                                    <form method="POST" action="{{ route('otp.generate') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Mobile Number</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fas fa-mobile-alt"></i></span>
                                                <input type="text" name="mobile_no" class="form-control" placeholder="Enter mobile number" required>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100 mt-3">Login with OTP</button>
                                    </form>
                                </div>

                                <!-- Sign Up -->
                                <div class="tab-pane fade" id="signup" role="tabpanel">
                                    <form method="POST" action="{{ route('register') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                                <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                                                <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Mobile No</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fas fa-mobile-alt"></i></span>
                                                <input type="text" name="mobile_no" class="form-control" placeholder="Mobile Number" required>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Password</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fas fa-lock"></i></span>
                                                <input type="password" name="password" class="form-control" placeholder="Password" required>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Confirm Password</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fas fa-lock"></i></span>
                                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-success w-100 mt-3">Sign Up</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--============================
       LOGIN/REGISTER PAGE END
    ==============================-->
@endsection
