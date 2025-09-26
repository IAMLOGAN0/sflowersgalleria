@extends('frontend.dashboard.layouts.master')

@section('title')
    {{ $settings->site_name }} || Orders
@endsection

@section('content')
<section id="wsus__dashboard">
    <div class="container-fluid">
        @include('frontend.dashboard.layouts.sidebar')

        <div class="row">
            <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
                <div class="dashboard_content mt-2 mt-md-0">
                    <h3><i class="far fa-user"></i> My Orders</h3>

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

                                <div class="mt-3 d-flex justify-content-between flex-wrap">
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
                                           class="btn btn-sm btn-primary">
                                           View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>No orders found.</p>
                    @endforelse

                    <div class="mt-3">
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
    .order-card {
        border-radius: 10px;
        transition: 0.2s ease-in-out;
    }
    .order-card:hover {
        transform: scale(1.01);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
</style>
@endpush
