@extends('frontend.layouts.master')

@section('title')
   Payment Success
@endsection

@section('content')

<!--============================
    PAYMENT SUCCESS START
==============================-->
<section id="wsus__payment_success" class="py-5 mt-2">
    <div class="container">
        <div class="wsus__payment_success_area text-center shadow-sm p-5 rounded" style="background: #fff;">
            <div class="success_icon mb-4">
                <svg width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="#28a745" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M9 12l2 2l4-4"></path>
                </svg>
            </div>
            <h1 class="mb-3 text-success">Payment Successful!</h1>
            <p class="text-muted mb-4">
                🎉 Thank you for your purchase! <br>
                Your order number is <strong>#{{$order->invocie_id}}</strong>. <br>
                We’ve sent a confirmation email with all the details of your order.
            </p>
            <div class="order_summary p-4 rounded" style="background: #f8f9fa;">
                <h5 class="mb-3">Order Summary</h5>
                <ul class="list-unstyled text-start d-inline-block">
                    <li><strong>Order Number:</strong> {{$order->invocie_id}}</li>
                    <li><strong>Payment Method:</strong> Razorpay</li>
                    <li><strong>Total Amount:</strong> ₹{{ number_format($order->total, 2) }}</li>
                    <li><strong>Status:</strong> <span class="badge bg-success">Paid</span></li>
                </ul>
            </div>
            <div class="mt-4 d-flex flex-column flex-sm-row justify-content-center gap-2 gap-sm-3">
                <a href="{{route('home')}}" class="btn btn-success w-100">Continue Shopping</a>
                <a href="{{route('user.orders.index')}}" class="btn btn-outline-primary w-100">View My Orders</a>
            </div>
        </div>
    </div>
</section>
<!--============================
    PAYMENT SUCCESS END
==============================-->
@endsection
