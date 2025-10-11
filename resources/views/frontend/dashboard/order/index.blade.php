@extends('frontend.dashboard.layouts.master')

@section('title')
    {{ $settings->site_name }} || Orders
@endsection

@section('content')
<section id="wsus__dashboard" class="py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-9 col-lg-9 col-md-10">
                <div class="dashboard_content mt-2 mt-md-0">
                    <h3 class="dashboard-title text-center mb-3">
                        <i class="far fa-user me-2"></i> My Orders
                    </h3>

                    @forelse($orders as $order)
                        <div class="card mb-3 shadow-sm order-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between flex-wrap align-items-center">
                                    <div>
                                        <h6 class="mb-1">Invoice #{{ $order->invocie_id }}</h6>
                                        <small class="text-muted">
                                            Placed on {{ date('d M, Y', strtotime($order->created_at)) }}
                                        </small>
                                    </div>
                                    <div>
                                        <span class="badge
                                            @if($order->order_status == 'pending') bg-warning
                                            @elseif($order->order_status == 'delivered') bg-success
                                            @elseif($order->order_status == 'canceled') bg-danger
                                            @else bg-info @endif">
                                            {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-3 d-flex justify-content-between flex-wrap align-items-center">
                                    <div>
                                        <p class="mb-1"><strong>Items:</strong> {{ $order->product_qty }}</p>
                                        <p class="mb-1"><strong>Payment:</strong>
                                            @if($order->payment_status == 1)
                                                <span class="text-success">Paid</span>
                                            @else
                                                <span class="text-danger">Unpaid</span>
                                            @endif
                                        </p>
                                        <p class="mb-1"><strong>Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                                    </div>
                                    <div class="text-end">
                                        <h5 class="mb-1">{{ $order->currency_icon }}{{ $order->amount }}</h5>
                                        <a href="{{ route('user.orders.show', $order->id) }}"
                                           class="btn btn-sm btn-primary rounded-pill px-3">
                                           View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted mt-4">No orders found.</p>
                    @endforelse

                    <div class="mt-4 d-flex justify-content-center">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    #wsus__dashboard {
        background-color: #f8f9fa;
        min-height: 100vh;
    }

    .order-card {
        border: none;
        border-radius: 12px;
        transition: all 0.3s ease;
        background-color: #fff;
    }

    .order-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .dashboard_content h3 {
        font-weight: 700;
        color: #333;
    }

    .badge {
        font-size: 0.85rem;
        padding: 6px 10px;
        border-radius: 10px;
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
            margin-top: 45px;
        }
    }
</style>
@endpush
