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
/* ----------------------------------------
   FNP Inspired Elegant Dashboard
----------------------------------------- */
.dashboard-title {
    margin-bottom: 2rem;
    font-size: 1.7rem;
    font-weight: 700;
    color: #2e3d49;
}

/* FNP Color Palette */
:root {
    --fnp-green: #2baf63;
    --fnp-dark: #1c2b21;
    --fnp-soft-bg: #f3f7f4;
    --fnp-card-bg: #ffffff;
    --fnp-text: #2d3a32;
}

/* Container background */
#wsus__banner {
    background: var(--fnp-soft-bg);
}

/* Dashboard Cards */
.wsus__dashboard_item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;

    background: var(--fnp-card-bg);
    border-radius: 18px;
    padding: 30px 10px;

    transition: all 0.25s ease-in-out;

    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
    height: 140px;

    border: 1px solid rgba(0, 0, 0, 0.05);
}

/* Icon */
.wsus__dashboard_item i {
    font-size: 2rem;
    margin-bottom: 12px;
    color: var(--fnp-green);
}

/* Label */
.wsus__dashboard_item p {
    margin: 0;
    font-size: 1rem;
    color: var(--fnp-dark);
    font-weight: 600;
}

/* Numbers */
.wsus__dashboard_item h4 {
    font-size: 1.4rem;
    font-weight: 700;
    margin-top: 6px;
    color: var(--fnp-green);
}

/* Hover — soft floating */
.wsus__dashboard_item:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.10);
}

/* Card Color Accents */
.red i, .red h4     { color: #e63946; }
.green i, .green h4 { color: #2baf63; }
.sky i, .sky h4     { color: #0072ff; }
.orange i, .orange h4 { color: #ff6600; }

/* Remove old gradient backgrounds – FNP uses clean white cards */
.red, .green, .sky, .orange {
    background: #ffffff !important;
}

/* Responsive */
@media (max-width: 767.98px) {
    .wsus__dashboard_item {
        padding: 20px 8px;
        height: auto;
    }

    .wsus__dashboard_item i {
        font-size: 1.6rem;
    }

    .wsus__dashboard_item h4 {
        font-size: 1.2rem;
    }
}


</style>
@endpush
