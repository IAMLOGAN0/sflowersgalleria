@extends('admin.layouts.master')

@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Assign Delivery</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="col-12">
                            <!-- Assignment Form -->
                            <form id="assignOrdersForm" method="POST" action="{{ route('admin.assign-orders.store') }}">
                                @csrf
                                <!-- Delivery Boy Selection -->
                                <div class="card shadow-sm bg-gray border-0 mb-4">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <label for="delivery_boy_id" class="form-label fw-semibold">
                                                    <i class="fas fa-user-tie text-warning me-2 mr-2"></i>Select Delivery Personnel
                                                </label>
                                                <select class="form-select form-select-lg" id="delivery_boy_id" name="delivery_boy_id">
                                                    <option value="">Choose delivery personnel...</option>
                                                    @foreach($delivery_boys as $boy)
                                                        <option value="{{ $boy->id }}">{{ $boy->name }} - {{ $boy->phone }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="bg-light rounded p-3 mt-3 mt-md-0">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="text-muted">Selected Orders:</span>
                                                        <span class="badge bg-warning text-dark fs-6" id="selectedCount">0</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Orders Table -->
                                <div class="card shadow-sm border-0">
                                    {{-- <div class="card-header bg-light border-0"> --}}
                                        <h6 class="mb-0 fw-bold ">
                                            Available Orders <span>({{ $orders->count() }}) </span>
                                        </h6>
                                    {{-- </div> --}}
                                    <div class="card-body p-0 mt-2  ">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead class="table-light   ">
                                                    <tr>
                                                        <th class="border-0 py-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="masterCheckbox">
                                                                <label class="form-check-label fw-semibold" for="masterCheckbox">
                                                                    Select
                                                                </label>
                                                            </div>
                                                        </th>
                                                        <th class="border-0 py-3 fw-semibold">Order ID</th>
                                                        <th class="border-0 py-3 fw-semibold">Customer</th>
                                                        <th class="border-0 py-3 fw-semibold">Address</th>
                                                        <th class="border-0 py-3 fw-semibold">Amount</th>
                                                        <th class="border-0 py-3 fw-semibold">Status</th>
                                                        <th class="border-0 py-3 fw-semibold">Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($orders as $order)
                                                    <tr class="order-row" data-order-id="{{ $order->id }}">
                                                        <td class="py-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input order-checkbox"
                                                                    type="checkbox"
                                                                    name="order_ids[]"
                                                                    value="{{ $order->id }}"
                                                                    id="order_{{ $order->id }}">
                                                                <label class="form-check-label" for="order_{{ $order->id }}"></label>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <span class="badge bg-light ">#{{ $order->invocie_id }}</span>
                                                        </td>
                                                        <td class="py-3">
                                                            <div>
                                                                <div class="fw-semibold">{{ $order->user->name }}</div>
                                                                <small class="text-muted">{{ $order->user->phone }}</small>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            @php
                                                                $address = json_decode($order->order_address);
                                                            @endphp

                                                            @if($address)
                                                                <div style="max-width: 250px;" title="{{ $address->address }}, {{ $address->city }}, {{ $address->state }}, {{ $address->country }} - {{ $address->zip }}">
                                                                    <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                                                    {{ $address->address }}, {{ $address->city }}, {{ $address->state }} <br>
                                                                    {{ $address->country }} - {{ $address->zip }}
                                                                </div>
                                                            @else
                                                                <span class="text-muted">No Address Available</span>
                                                            @endif
                                                        </td>
                                                        <td class="py-3">
                                                            <span class="fw-bold text-dark">{{ $settings->currency_icon }} {{ number_format($order->sub_total, 2) }}</span>
                                                        </td>
                                                        <td class="py-3">
                                                            <span class="badge bg-success">{{ ucfirst($order->order_status) }}</span>
                                                        </td>
                                                        <td class="py-3">
                                                            <small class="text-muted">{{ $order->created_at->format('M d, Y') }}</small>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center py-5">
                                                            <div class="text-muted">
                                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                                <p class="mb-0">No orders available for assignment</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg px-5" id="assignBtn">
                                        <span class="spinner-border spinner-border-sm me-2 d-none" id="loadingSpinner"></span>
                                        <i class="fas fa-paper-plane me-2"></i>
                                        Assign Selected Orders
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

@endsection

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    .form-check-input:checked {
        background-color: #ffc107;
        border-color: #ffc107;
    }

    .order-row:hover {
        background-color: #fff3cd;
        transition: background-color 0.2s ease;
    }

    .order-row.selected {
        background-color: #fff3cd;
        border-left: 4px solid #ffc107;
    }

    .card {
        border-radius: 12px;
    }

    .btn {
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {

    // Update selected count
    function updateSelectedCount() {
        const count = $('.order-checkbox:checked').length;
        $('#selectedCount').text(count);

        // Master checkbox state
        const totalOrders = $('.order-checkbox').length;
        const masterCheckbox = $('#masterCheckbox');

        if (count === 0) {
            masterCheckbox.prop('indeterminate', false).prop('checked', false);
        } else if (count === totalOrders) {
            masterCheckbox.prop('indeterminate', false).prop('checked', true);
        } else {
            masterCheckbox.prop('indeterminate', true).prop('checked', false);
        }
    }

    // Individual checkbox change
    $('.order-checkbox').on('change', function() {
        const row = $(this).closest('.order-row');
        if ($(this).is(':checked')) {
            row.addClass('selected');
        } else {
            row.removeClass('selected');
        }
        updateSelectedCount();
    });

    // Master checkbox
    $('#masterCheckbox').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.order-checkbox').prop('checked', isChecked).trigger('change');
    });

    // Delivery boy selection change
    $('#delivery_boy_id').on('change', function() {
        updateSelectedCount();
    });

    // Form submission
    $('#assignOrdersForm').on('submit', function(e) {
        e.preventDefault();

        const selectedOrderIds = $('.order-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        const deliveryBoyId = $('#delivery_boy_id').val();

        if (selectedOrderIds.length === 0) {
            showAlert('Please select at least one order', 'warning');
            return;
        }

        if (!deliveryBoyId) {
            showAlert('Please select a delivery personnel', 'warning');
            return;
        }

        // Show loading state
        $('#assignBtn').prop('disabled', true);
        $('#loadingSpinner').removeClass('d-none');

        // AJAX request
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: {
                _token: $('input[name="_token"]').val(),
                delivery_boy_id: deliveryBoyId,
                order_ids: selectedOrderIds
            },
            success: function(response) {
                showAlert('Orders assigned successfully!', 'success');

                // Remove assigned orders from table
                selectedOrderIds.forEach(function(orderId) {
                    $(`tr[data-order-id="${orderId}"]`).fadeOut(300, function() {
                        $(this).remove();
                        updateSelectedCount();
                    });
                });

                // Reset form
                $('#delivery_boy_id').val('');
                $('.order-checkbox').prop('checked', false).trigger('change');
            },
            error: function(xhr) {
                let message = 'An error occurred while assigning orders';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showAlert(message, 'error');
            },
            complete: function() {
                $('#assignBtn').prop('disabled', false);
                $('#loadingSpinner').addClass('d-none');
            }
        });
    });

    // Show alert function
    function showAlert(message, type) {
        switch(type) {
            case 'success':
                toastr.success(message);
                break;
            case 'warning':
                toastr.warning(message);
                break;
            case 'error':
            case 'danger':
                toastr.error(message);
                break;
            case 'info':
                toastr.info(message);
                break;
            default:
                toastr.info(message);
        }
    }

    // Initialize
    updateSelectedCount();
});
</script>
@endpush

