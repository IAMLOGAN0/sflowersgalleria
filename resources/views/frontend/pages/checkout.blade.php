@extends('frontend.layouts.master')

@section('title')
    {{ $settings->site_name }} || Checkout
@endsection
@push('styles')
<style>
    /* Modern hover/focus effects */
    #coupon_form input:focus {
        box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
        border-color: #007bff;
        outline: none;
    }

    #coupon_form button:hover {
        background-color: #0056b3;
    }
</style>
@endpush

@section('content')
    <!--============================
                    BREADCRUMB START
                ==============================-->
    <section id="wsus__breadcrumb">
        <div class="wsus_breadcrumb_overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h4>check out</h4>
                        <ul>
                            <li><a href="{{ route('home') }}">home</a></li>
                            <li><a href="javascript:;">check out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
                    BREADCRUMB END
                ==============================-->


    <!--============================
                    CHECK OUT PAGE START
                ==============================-->
    <section id="wsus__cart_view">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-7">
                    <div class="wsus__check_form">
                        <div class="d-flex align-items-center mb-4">
                            <h4 class="fw-bold mb-0">Order & Delivery Details</h4>
                        </div>

                        <div class="">
                            @foreach ($cartItems as $index => $item)
                            <div class="gift-box mb-4 p-4 rounded-3 shadow-sm border bg-white">

                                <!-- Item Info -->
                                <div class="gift-box mb-3  rounded-3  bg-white">
                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start">

                                        <!-- Left: Product Info -->
                                        <div class="d-flex">
                                            <img src="{{ asset($item->options->image) }}"
                                                alt="{{ $item->name }}"
                                                class="rounded shadow-sm"
                                                width="90" height="90" style="object-fit: cover;">

                                            <div class="ms-3">
                                                <h6 class="fw-semibold mb-1">{{ $item->name }}</h6>
                                                <p class="text-muted small mb-1">₹{{ $item->price }} × {{ $item->qty }}</p>
                                            </div>
                                        </div>

                                        <!-- Right: Delivery Info -->
                                        <div class="mt-3 mt-md-0 text-md-end">
                                            <p class="fw-semibold mb-1">Delivery On</p>
                                            <span class="badge bg-success fs-6 px-2 py-1">
                                                {{ \Carbon\Carbon::parse($item->options->order_date)->format('D, jS M, Y') }}
                                            </span>
                                            <p class="text-muted small mb-2">
                                                {{ $item->options->order_slot }}
                                            </p>
                                            {{-- <button class="btn btn-outline-secondary btn-sm">CHANGE ></button> --}}
                                        </div>
                                    </div>
                                </div>

                                <!-- Delivery Address -->
                                <div class="mb-4">
    <label class="fw-semibold mb-2 d-block">Delivery Address</label>

    <div class="input-group">
        <select class="form-select addressField" name="address[{{$item->rowId}}]">
            @foreach($addresses as $address)
                <option value="{{ $address->id }}">{{ $address->full_address }}</option>
            @endforeach
        </select>

        <!-- Edit Button -->
        <button type="button" class="btn btn-outline-secondary editAddressBtn">
            <!-- Pencil SVG -->
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0
                .708l-9.5 9.5-3.5.5a.5.5 0 0 1-.57-.57l.5-3.5 9.5-9.5zM11.207
                2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586
                3L10.5 3.207 4 9.707V10h.293L12.793 5.5z"/>
            </svg>
        </button>

        <!-- Add New Button -->
        <button type="button"
                class="btn btn-outline-primary addNewAddress"
                data-pincode="{{ $item->options->order_pincode }}"
                data-sector="{{ $item->options->order_sector }}">
            <!-- Plus SVG -->
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0
                1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0
                1 8 4z"/>
            </svg>
        </button>

    </div>
</div>


                                <!-- Occasion -->
                                <div class="mb-4">
                                    <label class="fw-semibold mb-2 d-block">Occasion</label>
                                    <select class="form-select" name="occasion[{{$item->rowId}}]">
                                        <option>Birthday</option>
                                        <option>Anniversary</option>
                                        <option>Congratulations</option>
                                        <option>Get Well Soon</option>
                                        <option>Other</option>
                                    </select>
                                </div>

                                <!-- Free Message Card -->
                                <div class="mb-2">
                                    <label class="fw-semibold mb-2 d-block">Free Message Card</label>
                                    <textarea class="form-control"
                                            rows="3"
                                            name="message[{{$item->rowId}}]"
                                            placeholder="Write a personalized message..."></textarea>
                                    <small class="text-muted">We’ll print this message on a complimentary card.</small>
                                </div>

                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-5">
                    <div class="wsus__order_details" id="sticky_sidebar">
                        <p class="wsus__product">Shipping Methods</p>
                        @foreach ($shippingMethods as $method)
                            @if ($method->type == 'min_cost' && getCartTotal() >= $method->min_cost)
                                <div class="form-check">
                                    <input class="form-check-input shipping_method" type="radio" name="exampleRadios"
                                        id="exampleRadios1" value="{{ $method->id }}"  data-id="{{ $method->cost }}">
                                    <label class="form-check-label" for="exampleRadios1">
                                        {{ $method->name }}
                                        <span>cost: ({{ $settings->currency_icon }}{{ $method->cost }})</span>
                                    </label>
                                </div>
                            @elseif ($method->type === 'flat_cost')
                                {{ $method->min_cost }}
                                <div class="form-check">
                                    <input class="form-check-input shipping_method" type="radio" name="exampleRadios"
                                        id="exampleRadios1" value="{{ $method->id }}" data-id="{{ $method->cost }}">
                                    <label class="form-check-label" for="exampleRadios1">
                                        {{ $method->name }}
                                        <span>cost: ({{ $settings->currency_icon }}{{ $method->cost }})</span>
                                    </label>
                                </div>
                            @endif
                        @endforeach

                        <div class="wsus__order_details_summery">
                            <p>subtotal: <span>{{ $settings->currency_icon }}{{ getCartTotal() }}</span></p>
                            <p>shipping fee(+): <span id="shipping_fee">{{ $settings->currency_icon }}0</span></p>
                            <p>coupon(-): <span id="discount">{{ $settings->currency_icon }}{{ getCartDiscount() }}</span></p>
                            <p><b>total:</b> <span><b id="total_amount"
                                        data-id="{{ getMainCartTotal() }}">{{ $settings->currency_icon }}{{ getMainCartTotal() }}</b></span>
                            </p>
                            <form id="coupon_form" class="d-flex align-items-center gap-2 p-2 bg-white rounded-3 shadow-sm">
                                <input type="text"
                                    class="form-control flex-grow-1 rounded-3 border-secondary"
                                    placeholder="Enter coupon code"
                                    name="coupon_code"
                                    value="{{ session()->has('coupon') ? session()->get('coupon')['coupon_code'] : '' }}">
                                <button type="submit" class="btn btn-primary rounded-3 px-4">
                                    Apply
                                </button>
                            </form>
                        </div>
                        <div class="terms_area">
                            <div class="form-check">
                                <input class="form-check-input agree_term" type="checkbox" value=""
                                    id="flexCheckChecked3" checked>
                                <label class="form-check-label" for="flexCheckChecked3">
                                    I have read and agree to the website <a href="#">terms and conditions *</a>
                                </label>
                            </div>
                        </div>
                        <form action="" id="checkOutForm" style="display: none">
                            <input type="hidden" name="shipping_method_id" value="" id="shipping_method_id">
                        </form>
                        <a href="" id="submitCheckoutForm" class="common_btn">Place Order</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="wsus__popup_address">
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">add new address</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="wsus__check_form p-3">
                            <form id="addressForm">
                                @csrf
                                <div class="row g-3">

                                    {{-- Receiver’s Contact --}}
                                    <div class="col-12">
                                        <h6 class="fw-bold mb-2 text-secondary">Receiver's Contact</h6>
                                    </div>

                                    <div class="col-12">
                                        <input type="text" class="form-control" name="name" placeholder="Full Name *" required value="{{ old('name') }}">
                                    </div>

                                    <div class="col-12">
                                        <input type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}" required>
                                    </div>

                                    <div class="col-12">
                                        <input type="number" class="form-control" name="phone" placeholder="Mobile Number *" required value="{{ old('phone') }}">
                                    </div>

                                    {{-- Receiver’s Address --}}
                                    <div class="col-12 mt-3">
                                        <h6 class="fw-bold mb-2 text-secondary">Receiver's Address</h6>
                                    </div>

                                    <div class="col-12">
                                        <input type="text" class="form-control" name="address" placeholder="Flat / House / Tower / Village *" required value="{{ old('address') }}">
                                    </div>

                                    <div class="col-md-6">
                                        <input id="zipInput" type="number" readonly class="form-control" name="pincode" placeholder="Pincode *" required value="{{ old('pincode') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" readonly name="sector" placeholder="Sector" id="sector" required value="{{ old('sector') }}">
                                    </div>

                                    <div class="col-12">
                                        <input type="text" class="form-control" name="landmark" placeholder="Landmark (optional)" value="{{ old('landmark') }}">
                                    </div>

                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="city" placeholder="City" readonly value="Gurugram">
                                    </div>

                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="country" placeholder="Country" readonly value="India">
                                    </div>

                                    {{-- Type of Address --}}
                                    <div class="col-12 mt-3">
                                        <h6 class="fw-bold mb-2 text-secondary">Type of Address</h6>
                                        <div class="d-flex gap-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="type" id="home" value="home" checked>
                                                <label class="form-check-label" for="home">Home</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="type" id="office" value="office">
                                                <label class="form-check-label" for="office">Office</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="type" id="other" value="other">
                                                <label class="form-check-label" for="other">Other</label>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Button --}}
                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn w-100 text-white common_btn" >Save & Continue</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--============================
                    CHECK OUT PAGE END
                ==============================-->
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('input[type="radio"]').prop('checked', false);
            $('#shipping_method_id').val("");
            $('#shipping_address_id').val("");

            $('.shipping_method').on('click', function() {
                let shippingFee = $(this).data('id');
                let currentTotalAmount = $('#total_amount').data('id')
                let totalAmount = currentTotalAmount + shippingFee;

                $('#shipping_method_id').val($(this).val());
                $('#shipping_fee').text("{{ $settings->currency_icon }}" + shippingFee);

                $('#total_amount').text("{{ $settings->currency_icon }}" + totalAmount)
            })

            $('.shipping_address').on('click', function() {
                $('#shipping_address_id').val($(this).data('id'));
                let zip = $(this).data('zip');

                // check if shipping method is available for this address
                $.ajax({
                    url: "{{ route('user.check.pincode') }}",
                    method: 'POST',
                    data: {
                        zip: zip
                    },
                    success: function(data) {
                        if (data.status === 'success') {
                            // $('#delivery_time').text('Express delivery: '+data.data.b_time).css('color', 'green');
                            toastr.success('Delivery is available in your area');
                        } else {
                            // $('#delivery_time').text('Standard delivery: 2-5 business days');
                            toastr.error('Delivery is not available in your area');
                        }
                    },
                    error: function(data) {
                        // console.log(data);
                    }
                });
            });

            // submit checkout form
            $('#submitCheckoutForm').on('click', function(e) {
                e.preventDefault();
                if ($('#shipping_method_id').val() == "") {
                    toastr.error('Shipping method is requred');
                } else if ($('.addressField').filter(function() { return $(this).val() != ""; }).length == 0) {
                    toastr.error('Shipping address is requred');
                } else if (!$('.agree_term').prop('checked')) {
                    toastr.error('You have to agree website terms and conditions');
                    } else {
                        // move inputs from gift-box into the form
                        $('.gift-box').find('input, select, textarea').each(function() {
                            $('#checkOutForm').append($(this).clone());
                        });

                        $.ajax({
                            url: "{{ route('user.checkout.form-submit') }}",
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: $('#checkOutForm').serialize(),
                            beforeSend: function() {
                                $('#submitCheckoutForm').html(
                                    '<i class="fas fa-spinner fa-spin fa-1x"></i>')
                            },
                            success: function(data) {
                                if (data.status === 'success') {
                                    $('#submitCheckoutForm').text('Place Order')
                                    // redirect user to next page
                                    window.location.href = data.redirect_url;
                                }
                            },
                            error: function(data) {
                                // console.log(data);
                            }
                        })
                    }



            });

            $(".addNewAddress").on('click', function() {

                $("#addressForm").attr("data-mode", "add");
                $("#addressForm").attr("data-id", "");
                $("#addressForm input[name='name']").val("");
                $("#addressForm input[name='email']").val("");
                $("#addressForm input[name='phone']").val("");
                $("#addressForm input[name='address']").val("");
                $("#addressForm input[name='landmark']").val("");

                $("#addressForm input[name='type']").prop("checked", false);

                $pincode = $(this).attr('data-pincode');
                $sector = $(this).attr('data-sector');
                if($sector){
                    // fetch sector data
                    $.ajax({
                        url: "{{ route('cart.get-sector') }}",
                        method: 'POST',
                        data: {sector_id: $sector},
                        success: function(data) {
                            if (data.status === 'success') {
                                // console.log(data.sector);
                                $("#sector").val(data.sector.sector);
                                // you can use data.sector to prefill any other fields if needed
                            }
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    })
                }
                $("#zipInput").val($pincode);
                $('#exampleModal').modal('show');
            });

            $("#addressForm").on('submit', function(e) {
                e.preventDefault();

                let mode = $(this).data('mode');
                let id   = $(".addressField").val();
                let formData = $(this).serialize();

                let url = (mode == "update")
                    ? "{{ route('user.checkout.address.update') }}"
                    : "{{ route('user.checkout.address.create') }}";

                $.ajax({
                    url: url,
                    method: "POST",
                    data: formData + "&id=" + id,
                    success: function(data) {
                        if (data.status === 'success') {
                            $('#exampleModal').modal('hide');
                            $('.addressField').html(data.data); // Refresh address list everywhere
                            toastr.success(data.message);
                        }
                    }
                });
            });


            // applay coupon on cart

            $('#coupon_form').on('submit', function(e){
                e.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    method: 'GET',
                    url: "{{ route('apply-coupon') }}",
                    data: formData,
                    success: function(data) {
                    if(data.status === 'error'){
                        toastr.error(data.message)
                    }else if (data.status === 'success'){
                        calculateCouponDescount()
                        toastr.success(data.message)

                        // Update UI to show coupon applied
                        $('#coupon_form button').text('Applied').prop('disabled', true).addClass('btn-success');
                        $('#coupon_form input[name="coupon_code"]').prop('disabled', true);
                    }
                    },
                    error: function(data) {
                        // console.log(data);
                    }
                })

            })

            // calculate discount amount
            function calculateCouponDescount(){
                $.ajax({
                    method: 'GET',
                    url: "{{ route('coupon-calculation') }}",
                    success: function(data) {
                        if(data.status === 'success'){
                            $('#discount').text('{{$settings->currency_icon}}'+data.discount);
                            $('#total_amount').text('{{$settings->currency_icon}}'+data.cart_total);
                        }
                    },
                    error: function(data) {
                        // console.log(data);
                    }
                })
            }

        })

        $('.addressField').on('change', function() {
    let selectedAddress = $(this).val();
    $(this).closest('.input-group').find('.editAddressBtn').attr('data-address-id', selectedAddress);
});

$(".editAddressBtn").on('click', function () {
    let addressId = $(".addressField").val();

    if (!addressId) {
        toastr.error("Please select an address to edit");
        return;
    }
    let url = "/user/address/" + addressId;
    $.ajax({
        url: url,
        method: "GET",
        success: function (res) {
            if (res.status == 'success') {
                // Fill modal fields
                $("#addressForm input[name='name']").val(res.data.name);
                $("#addressForm input[name='email']").val(res.data.email);
                $("#addressForm input[name='phone']").val(res.data.phone);
                $("#addressForm input[name='address']").val(res.data.address);
                $("#addressForm input[name='pincode']").val(res.data.pincode);
                $("#addressForm input[name='sector']").val(res.data.sector);
                $("#addressForm input[name='landmark']").val(res.data.landmark);
                $("#addressForm input[name='city']").val(res.data.city);
                $("#addressForm input[name='country']").val(res.data.country);

                $("#addressForm input[name='type'][value='" + res.data.type + "']").prop("checked", true);

                // Change form mode to "update"
                $("#addressForm").attr("data-mode", "update");
                $("#addressForm").attr("data-id", addressId);

                $('#exampleModal').modal('show');
            }
        }
    });
});

    </script>
@endpush
