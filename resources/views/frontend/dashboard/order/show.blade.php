@php
    $address = json_decode($order->order_address);
    $shipping = json_decode($order->shpping_method);
    $coupon = json_decode($order->coupon);
@endphp

@extends('frontend.dashboard.layouts.master')

@section('title')
    {{ $settings->site_name }} || Product
@endsection

@push('styles')
<style>
    /* Mobile Adjustments */
    @media (max-width: 767px) {
        .invoice-print {
            padding: 15px;
        }
        .invoice-header {
            flex-direction: column;
            align-items: flex-start !important;
        }
        .invoice-header .text-end {
            text-align: left !important;
            margin-top: 10px;
        }
        .invoice-info h6 {
            margin-top: 15px;
        }
        .table-responsive {
            border: none;
        }
        .print_invoice {
            width: 100%;
        }
    }
</style>
@endpush
@section('content')
    <!--=============================
        DASHBOARD START
      ==============================-->
    <section id="wsus__dashboard">
        <div class="container-fluid">
            @include('vendor.layouts.sidebar')

            <div class="row">
                <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
                    <div class="dashboard_content mt-2 mt-md-0">
                        <h3><i class="far fa-file-invoice"></i> Order Invoice</h3>

                        <section class="invoice-print p-4 bg-white shadow rounded">
                            <!-- HEADER -->
                            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4 invoice-header">
                                <div>
                                    <h4 class="mb-0">{{ $settings->site_name }}</h4>
                                    <small class="text-muted">Thank you for your purchase!</small>
                                </div>
                                <div class="text-end">
                                    <h5 class="mb-1">Order ID: #{{ $order->invocie_id }}</h5>
                                    <span class="badge bg-info">
                                        {{ config('order_status.order_status_admin')[$order->order_status]['status'] }}
                                    </span>
                                    <p class="mb-0">Date: {{ $order->created_at->format('d M Y') }}</p>
                                </div>
                            </div>

                            <!-- BILLING / SHIPPING -->
                            <div class="row mb-4 invoice-info">
                                <div class="col-md-6 col-12">
                                    <h6 class="fw-bold mb-2">Billing Information</h6>
                                    <p class="mb-1">{{ $address->name }}</p>
                                    <p class="mb-1">{{ $address->email }}</p>
                                    <p class="mb-1">{{ $address->phone }}</p>
                                    <p class="mb-1">{{ $address->address }}, {{ $address->city }}, {{ $address->state }} - {{ $address->zip }}</p>
                                    <p class="mb-0">{{ $address->country }}</p>
                                </div>
                                <div class="col-md-6 col-12">
                                    <h6 class="fw-bold mb-2">Shipping Information</h6>
                                    <p class="mb-1">{{ $address->name }}</p>
                                    <p class="mb-1">{{ $address->email }}</p>
                                    <p class="mb-1">{{ $address->phone }}</p>
                                    <p class="mb-1">{{ $address->address }}, {{ $address->city }}, {{ $address->state }} - {{ $address->zip }}</p>
                                    <p class="mb-0">{{ $address->country }}</p>
                                </div>
                            </div>

                            <!-- PRODUCT TABLE -->
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>Vendor</th>
                                            <th>Unit Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->orderProducts as $product)
                                            @php $variants = json_decode($product->variants); @endphp
                                            <tr>
                                                <td>
                                                    <strong>{{ $product->product_name }}</strong><br>
                                                    @foreach ($variants as $key => $item)
                                                        <small class="text-muted">{{ $key }}: {{ $item->name }} ({{ $settings->currency_icon }}{{ $item->price }})</small><br>
                                                    @endforeach
                                                </td>
                                                <td>{{ $product->vendor->shop_name }}</td>
                                                <td>{{ $settings->currency_icon }}{{ $product->unit_price }}</td>
                                                <td>{{ $product->qty }}</td>
                                                <td>{{ $settings->currency_icon }}{{ $product->unit_price * $product->qty }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- TOTALS -->
                            <div class="d-flex justify-content-end mt-4">
                                <div style="max-width: 300px;" class="w-100">
                                    <div class="d-flex justify-content-between">
                                        <span>Sub Total:</span>
                                        <span>{{ @$settings->currency_icon }} {{ @$order->sub_total }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Shipping Fee (+):</span>
                                        <span>{{ @$settings->currency_icon }} {{ @$shipping->cost }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Coupon (-):</span>
                                        <span>{{ @$settings->currency_icon }} {{ @$coupon->discount ? $coupon->discount : 0 }}</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between fw-bold">
                                        <span>Total Amount:</span>
                                        <span>{{ @$settings->currency_icon }} {{ @$order->amount }}</span>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- PRINT BUTTON -->
                        <div class="mt-3 text-end">
                            <button class="btn btn-warning print_invoice"><i class="fas fa-print me-2"></i> Print Invoice</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    </section>
    <!--=============================
        DASHBOARD START
      ==============================-->
@endsection

@push('scripts')
    <script>
        $('.print_invoice').on('click', function() {
            let printBody = $('.invoice-print');
            let originalContents = $('body').html();

            $('body').html(printBody.html());

            window.print();

            $('body').html(originalContents);

        })
    </script>
@endpush
