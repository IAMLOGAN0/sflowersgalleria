@extends('frontend.dashboard.layouts.master')

@section('title')
    {{ $settings->site_name }} || Order Invoice
@endsection

@push('styles')
<style>
    .invoice-container {
        background: #fff;
        border-radius: 12px;
        padding: 40px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    }
    .invoice-header {
        border-bottom: 2px solid #f1f1f1;
        margin-bottom: 30px;
        padding-bottom: 20px;
    }
    .invoice-header h4 {
        font-weight: 700;
        color: #222;
    }
    .invoice-header small {
        font-size: 14px;
        color: #666;
    }
    .invoice-meta {
        text-align: right;
    }
    .invoice-meta h5 {
        margin-bottom: 5px;
        font-weight: 600;
    }
    .invoice-table-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .invoice-table {
        min-width: 600px;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .invoice-table th {
        background: #f8f9fa;
        padding: 12px;
        font-weight: 600;
        font-size: 14px;
        border-bottom: 1px solid #eaeaea;
    }
    .invoice-table td {
        padding: 12px;
        font-size: 14px;
        vertical-align: top;
        border-bottom: 1px solid #f1f1f1;
    }
    .address-box {
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 15px;
        background: #fafafa;
        font-size: 14px;
    }
    .totals-box {
        max-width: 350px;
        margin-left: auto;
        border: 1px solid #eee;
        border-radius: 8px;
        background: #fafafa;
        padding: 20px;
    }
    .totals-box div {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
    }
    .totals-box .grand-total {
        font-weight: 700;
        font-size: 16px;
        border-top: 2px solid #ddd;
        padding-top: 10px;
        margin-top: 10px;
    }
    .print-btn {
        margin-top: 30px;
    }

    @media print {
        .print-btn, .sidebar { display: none !important; }
        body { background: #fff !important; }
    }

    /* Mobile Responsive Table */
    @media (max-width: 576px) {
        .invoice-table thead {
            display: none;
        }
        .invoice-table tbody tr {
            display: block;
            margin-bottom: 15px;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 10px;
        }
        .invoice-table tbody td {
            display: flex;
            justify-content: space-between;
            padding: 5px 10px;
            border: none;
            border-bottom: 1px solid #f1f1f1;
        }
        .invoice-table tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #555;
        }

        /* Stack addresses and totals */
        .row.mt-4 > [class^="col-"] {
            margin-bottom: 15px;
        }
        .totals-box {
            max-width: 100%;
        }
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
    }
</style>
@endpush

@section('content')
<section id="wsus__dashboard">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
                <div class="dashboard_content mt-2 mt-md-0">
                     <h3 class="dashboard-title text-center">
                        <i class="far fa-file-invoice"></i> Order Invoice
                    </h3>
                    <div class="invoice-container mt-3">
                        <!-- Header -->
                        <div class="d-flex justify-content-between align-items-center invoice-header">
                            <div>
                                <h4 class="mb-0">{{ $settings->site_name }}</h4>
                                <small>Thank you for shopping with us</small>
                            </div>
                            <div class="invoice-meta">
                                <h5>Invoice #: {{ $order->invocie_id }}</h5>
                                <span class="badge bg-info">
                                    {{ config('order_status.order_status_admin')[$order->order_status]['status'] }}
                                </span>
                                <p class="mb-0">Date: {{ $order->created_at->format('d M Y') }}</p>
                            </div>
                        </div>

                        <!-- Products -->
                        <div class="invoice-table-wrapper">
                            <table class="table invoice-table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Variants</th>
                                        <th>Unit Price</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->orderProducts as $product)
                                        @php
                                            $variants = json_decode($product->variants);
                                            $shipAddress = json_decode($product->delivery_address);
                                            $billAddress = json_decode($product->delivery_address ?? []);
                                        @endphp
                                        <tr>
                                            <td data-label="Product">
                                                <strong>{{ $product->product_name }}</strong><br>
                                                <small class="text-muted">{{ $product->vendor->shop_name ?? 'Vendor' }}</small>
                                            </td>
                                            <td data-label="Variants">
                                                @if(!empty($variants))
                                                    @foreach ($variants as $key => $item)
                                                        <div>{{ $key }}: {{ $item->name }} ({{ $settings->currency_icon }}{{ $item->price }})</div>
                                                    @endforeach
                                                @else
                                                    <span>-</span>
                                                @endif
                                            </td>
                                            <td data-label="Unit Price">{{ $settings->currency_icon }}{{ $product->unit_price }}</td>
                                            <td data-label="Qty">{{ $product->qty }}</td>
                                            <td data-label="Total" class="fw-bold">{{ $settings->currency_icon }}{{ $product->unit_price * $product->qty }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Addresses -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold">Billing Address</h6>
                                <div class="address-box mt-1">
                                    <p class="mb-1"><strong>{{ $billAddress->name ?? '' }}</strong></p>
                                    <p class="mb-1">{{ $billAddress->phone ?? '' }}</p>
                                    <p class="mb-0">{{ $billAddress->address ?? '' }}, {{ $billAddress->city ?? '' }}, {{ $billAddress->country ?? '' }} - {{ $billAddress->zip ?? '' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold">Shipping Address</h6>
                                <div class="address-box mt-1">
                                    <p class="mb-1"><strong>{{ $shipAddress->name ?? '' }}</strong></p>
                                    <p class="mb-1">{{ $shipAddress->phone ?? '' }}</p>
                                    <p class="mb-0">{{ $shipAddress->address ?? '' }}, {{ $shipAddress->city ?? '' }}, {{ $shipAddress->country ?? '' }} - {{ $shipAddress->zip ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Totals -->
                        <div class="totals-box mt-4">
                            <div><span>Sub Total:</span> <span>{{ $settings->currency_icon }} {{ $order->sub_total }}</span></div>
                            <div><span>Shipping Fee:</span> <span>{{ $settings->currency_icon }} {{ @$shipping->cost ?? 0 }}</span></div>
                            <div><span>Coupon:</span> <span>- {{ $settings->currency_icon }} {{ $coupon->discount ?? 0 }}</span></div>
                            <div class="grand-total"><span>Total Amount:</span> <span>{{ $settings->currency_icon }} {{ $order->amount }}</span></div>
                        </div>
                    </div>

                    <!-- Print Button -->
                    <div class="print-btn text-end">
                        <button class="btn btn-warning print_invoice"><i class="fas fa-print me-2"></i> Print Invoice</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    $('.print_invoice').on('click', function() {
        window.print();
    });
</script>
@endpush

