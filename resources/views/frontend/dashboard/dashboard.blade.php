@extends('frontend.dashboard.layouts.master')

@section('title')
    {{ $settings->site_name }} || Dashboard
@endsection

@section('content')
<section id="wsus__banner" class="py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-9 col-lg-10 col-md-11">
                <div class="dashboard_content text-center">

                    <!-- Modern Title -->
                    <h3 class="dashboard-title text-center">
                        <i class="fal fa-chart-bar me-2"></i> Dashboard
                    </h3>

                    <!-- Dashboard Boxes -->
                    <div class="row g-3 justify-content-center">
                        <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                            <a class="wsus__dashboard_item red" href="{{ route('user.orders.index') }}">
                                <i class="fas fa-cart-plus"></i>
                                <p>Total Orders</p>
                                <h4>{{ $totalOrder }}</h4>
                            </a>
                        </div>

                        <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                            <a class="wsus__dashboard_item green" href="#">
                                <i class="fas fa-hourglass-half"></i>
                                <p>Pending</p>
                                <h4>{{ $pendingOrder }}</h4>
                            </a>
                        </div>

                        <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                            <a class="wsus__dashboard_item sky" href="#">
                                <i class="fas fa-check-circle"></i>
                                <p>Completed</p>
                                <h4>{{ $completeOrder }}</h4>
                            </a>
                        </div>

                        <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                            <a class="wsus__dashboard_item orange" href="{{ route('user.profile') }}">
                                <i class="fas fa-user-shield"></i>
                                <p>Profile</p>
                                <h4>-</h4>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@push('styles')
<style>
    /* ------------------------------
       Modern Dashboard Styling
    ------------------------------- */
    #wsus__dashboard {
        background-color: #f8f9fa;
        min-height: 100vh;
    }

    .dashboard_content {
        overflow: visible !important;
    }

    /* Make sure title is always visible */
    .dashboard-title {
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
        font-weight: 700;
        color: #333;
        word-wrap: break-word;
        white-space: normal;
    }

    .dashboard-title i {
        color: var(--bs-primary, #0d6efd);
    }

    /* Fix possible overflow/hidden issues */
    #wsus__dashboard .dashboard_content {
        overflow: visible !important;
    }

    /* Adjust for mobile screens */
    @media (max-width: 575.98px) {
        .dashboard-title {
            font-size: 1.25rem;
            text-align: center;
            padding: 10px 0;
            margin-top: 70px;
        }
    }s


    /* Dashboard Cards */
    .wsus__dashboard_item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        background-color: #fff;
        border-radius: 16px;
        padding: 25px 10px;
        transition: all 0.3s ease-in-out;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        text-decoration: none;
        height: 120px;
    }

    .wsus__dashboard_item i {
        font-size: 1.8rem;
        margin-bottom: 10px;
        color: #fff;
    }

    .wsus__dashboard_item p {
        margin: 0;
        font-size: 0.95rem;
        color: #fff;
        font-weight: 600;
    }

    .wsus__dashboard_item h4 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-top: 8px;
        color: #fff;
    }

    /* Hover animation */
    .wsus__dashboard_item:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    }

    /* Custom Colors */
    .red    { background: linear-gradient(135deg, #ff4e50, #f9d423); }
    .green  { background: linear-gradient(135deg, #11998e, #38ef7d); }
    .sky    { background: linear-gradient(135deg, #00c6ff, #0072ff); }
    .orange { background: linear-gradient(135deg, #ff6a00, #ee0979); }

    /* Responsive Fixes */
    @media (max-width: 767.98px) {
        .wsus__dashboard_item {
            padding: 18px 8px;
            height: auto;
        }
        .wsus__dashboard_item p {
            font-size: 0.9rem;
        }
        .wsus__dashboard_item h4 {
            font-size: 1.1rem;
        }
    }

</style>
@endpush
