@extends('frontend.layouts.master')

@section('title')
    {{ $settings->site_name }} || Checkout
@endsection

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
                                <div class="d-flex align-items-center mb-4">
                                    <img src="{{ asset($item->options->image) }}"
                                        alt="{{ $item->name }}"
                                        class="rounded shadow-sm"
                                        width="100" height="100" style="object-fit: cover;">

                                    <div class="ms-3">
                                        <h6 class="fw-semibold mb-1">{{ $item->name }}</h6>
                                        <p class="text-muted mb-1 small">Price: <span class="fw-semibold text-dark">{{ $settings->currency_icon.$item->price }}</span></p>
                                        <p class="text-muted small mb-0">Qty: <span class="fw-semibold text-dark">{{ $item->qty }}</span></p>
                                    </div>
                                </div>

                                <!-- Delivery Date & Slot -->
                                <div class="mb-4">
                                    <label class="fw-semibold mb-2 d-block">Delivery Schedule</label>
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <input type="date"
                                                class="form-control"
                                                name="delivery_date[{{$item->rowId}}]"
                                                value="{{ date('Y-m-d') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-select" name="delivery_slot[{{$item->rowId}}]">
                                                <option value="12-1">12:00 - 13:00</option>
                                                <option value="13-2">13:00 - 14:00</option>
                                                <option value="18-20">18:00 - 20:00</option>
                                            </select>
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
                                        <button type="button" class="btn btn-outline-primary addNewAddress">+ Add New</button>
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
                        @php
                            $deliveryLocation = session('delivery_location');
                            $selectedDate = $deliveryLocation['date'] ?? date('Y-m-d');
                        @endphp

                        <p class="wsus__product">Shipping Methods</p>
                        @foreach ($shippingMethods as $method)
                            @if ($method->type == 'min_cost' && getCartTotal() >= $method->min_cost)
                                <div class="form-check">
                                    <input class="form-check-input shipping_method" type="radio" name="exampleRadios"
                                        id="exampleRadios1" value="{{ $method->id }}" data-id="{{ $method->cost }}">
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
                            <p>coupon(-): <span>{{ $settings->currency_icon }}{{ getCartDiscount() }}</span></p>
                            <p><b>total:</b> <span><b id="total_amount"
                                        data-id="{{ getMainCartTotal() }}">{{ $settings->currency_icon }}{{ getMainCartTotal() }}</b></span>
                            </p>
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
                        <form action="" id="checkOutForm">
                            <input type="hidden" name="shipping_method_id" value="" id="shipping_method_id">
                            <input type="hidden" name="shipping_address_id" value="" id="shipping_address_id">

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
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="wsus__check_single_form">
                                            <input type="text" placeholder="Name *" required name="name"
                                                value="{{ old('name') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="wsus__check_single_form">
                                            <input type="text" placeholder="Phone *" required name="phone"
                                                value="{{ old('phone') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="wsus__check_single_form">
                                            <input type="email" placeholder="Email *" required name="email"
                                                value="{{ old('email') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="wsus__check_single_form">
                                            <select class="select_2" name="country" required>
                                                <option value="">Country / Region *</option>
                                                @foreach (config('settings.country_list') as $key => $county)
                                                    <option {{ $county === old('country') ? 'selected' : '' }}
                                                        value="{{ $county }}">{{ $county }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="wsus__check_single_form">
                                            <input type="text" placeholder="State *" required name="state"
                                                value="{{ old('state') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="wsus__check_single_form">
                                            <input type="text" placeholder="Town / City *" required name="city"
                                                value="{{ old('city') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="wsus__check_single_form">
                                            <input id="zipInput" type="text" placeholder="Zip *" required
                                                name="zip" value="{{ old('zip') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="wsus__check_single_form">
                                            <input type="text" placeholder="Address *" required name="address"
                                                value="{{ old('address') }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-12">
                                        <div class="wsus__check_single_form">
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
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
                        console.log(data);
                    }
                });
            });

            // submit checkout form
            $('#submitCheckoutForm').on('click', function(e) {
                e.preventDefault();
                if ($('#shipping_method_id').val() == "") {
                    toastr.error('Shipping method is requred');
                } else if ($('#shipping_address_id').val() == "") {
                    toastr.error('Shipping address is requred');
                } else if (!$('.agree_term').prop('checked')) {
                    toastr.error('You have to agree website terms and conditions');
                } else {
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
                            console.log(data);
                        }
                    })
                }



            });

            $('#zipInput').on('blur', function() {
                let zip = $(this).val();
                $.ajax({
                    url: "{{ route('user.check.pincode') }}",
                    method: 'POST',
                    data: {
                        zip: zip
                    },
                    success: function(data) {
                        if (data.status === 'success') {
                            console.log(data);
                            toastr.success('Delivery is available in your area');
                        } else {
                            toastr.error('Delivery is not available in your area');
                        }
                    },
                    error: function(data) {
                        console.log(data);
                    }
                })
            });

            $(".addNewAddress").on('click', function() {
                $('#exampleModal').modal('show');
            });

            $("#addressForm").on('submit', function(e) {
                e.preventDefault();
                let data = $(this).serialize();
                $.ajax({
                    url: "{{ route('user.checkout.address.create') }}",
                    method: 'POST',
                    data: data,
                    success: function(data) {
                        if (data.status === 'success') {
                            $('#exampleModal').modal('hide');
                            $('.addressField').html(data.data);
                            toastr.success(data.message);
                        }
                    },
                    error: function(data) {
                        console.log(data);
                    }
                })
            });

        })
    </script>

    <script>
        $(document).ready(function() {
            var sectors = [];
            // Parse slot time helper
            function parseSlotTime(slotStart, selectedDate) {
                // Convert "9pm" → date object
                let hours = parseInt(slotStart);
                let isPM = slotStart.toLowerCase().includes("pm");
                if (isPM && hours < 12) hours += 12;
                if (!isPM && hours === 12) hours = 0;

                let dateTime = new Date(selectedDate + "T" + String(hours).padStart(2, '0') + ":00:00");
                return dateTime;
            }

            // Filter slots function
            function filterSlotsByDate(selectedSector) {
                let selectedDate = $("#delivery-date").val();
                let today = new Date().toISOString().split("T")[0]; // YYYY-MM-DD

                $("#slot").html('<option disabled selected>-- Select Slot --</option>');

                if (!selectedSector || !selectedSector.t_time) return;

                let slots = JSON.parse(selectedSector.t_time);
                let availableSlots = [];

                if (selectedDate === today) {
                    let now = new Date();

                    slots.forEach(function(slot) {
                        let slotStart = slot.split("-")[0].trim();
                        let slotDateTime = parseSlotTime(slotStart, selectedDate);

                        if (slotDateTime > now) {
                            availableSlots.push(slot);
                        }
                    });

                    if (availableSlots.length === 0) {
                        toastr.error("No slots left for today. Please choose another date.");
                        $("#slot").html('<option disabled selected>No slots available today</option>');
                        $("#delivery-time").val('');
                        $("#delivery-time-span")
                            .html('No slots available today')
                            .css("color", "red"); // 🔴 red text
                        return;
                    } else {
                        $("#delivery-time-span")
                            .html('Available slots for today')
                            .css("color", "green");
                    }
                } else {
                    availableSlots = slots; // Future date → show all slots
                    $("#delivery-time-span")
                        .html('Available slots for ' + selectedDate)
                        .css("color", "green");
                }

                availableSlots.forEach(function(slot) {
                    $("#slot").append('<option value="' + slot + '">' + slot + '</option>');
                });
            }

            /** 🔹 Pincode AJAX */
            $("#pincode").on('input', function() {
                let pincode = $(this).val().trim();
                if (pincode.length === 6) {
                    $.ajax({
                        url: "{{ route('get-sectors-by-pincode') }}",
                        type: 'POST',
                        data: {
                            pincode: pincode
                        },
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $("#sector").html(
                                '<option disabled selected>-- Select Sector --</option>');
                            $("#slot").html(
                                '<option disabled selected>-- Select Slot --</option>');

                            if (response.sectors && response.sectors.length > 0) {
                                toastr.success(
                                    "Delivery is available in your area. Please select a sector."
                                );
                                sectors = response.sectors;
                                response.sectors.forEach(function(sector) {
                                    $("#sector").append('<option value="' + sector.id +
                                        '">' + sector.sector + '</option>');
                                });
                            } else {
                                toastr.error("Delivery is not available in your area.");
                            }
                        }
                    });
                }
            });

            // On sector change → filter slots
            $("#sector").on('change', function() {
                let sectorId = $(this).val();
                let selectedSector = sectors.find(s => Number(s.id) === Number(sectorId));

                $("#slot").html('<option disabled selected>-- Select Slot --</option>');
                $("#delivery-time").val('');
                $("#delivery-time-span").html('');

                if (selectedSector && selectedSector.t_time) {
                    filterSlotsByDate(selectedSector);
                } else {
                    $("#delivery-time-span").html('Standard delivery: 2-5 business days');
                }
            });


            // On date change → re-run slot filter
            $("#delivery-date").on("change", function() {
                let sectorId = $("#sector").val();
                let selectedSector = sectors.find(s => s.id == sectorId);
                if (selectedSector) {
                    filterSlotsByDate(selectedSector);
                }
            });

            $("#slot").on("change", function() {
                let slot = $(this).val();
                let date = $("#delivery-date").val();
                if (slot && date) {
                    $("#delivery-time-span").html(
                            "<span style='color: green'>We will deliver your order on</span> <br>" + date +
                            " <span style='color: green'>at</span> " + slot + ".")
                        .css("font-weight", "bold")
                        .css("font-size", "1.1rem");
                }
            });

            let deliveryLocation = @json(session('delivery_location') ?: []);

            // If pincode already filled from session → auto load sectors
            let pincode = $("#pincode").val();
            if (pincode) {
                $.ajax({
                    url: "{{ route('get-sectors-by-pincode') }}",
                    type: 'POST',
                    data: {
                        pincode: pincode
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $("#sector").html('<option disabled selected>-- Select Sector --</option>');
                        $("#slot").html('<option disabled selected>-- Select Slot --</option>');

                        if (response.sectors && response.sectors.length > 0) {
                            sectors = response.sectors;
                            response.sectors.forEach(function(sector) {
                                $("#sector").append('<option value="' + sector.id +
                                    '" data-time=\'' + sector.t_time + '\'>' + sector
                                    .sector + '</option>');
                            });

                            // Restore session sector
                            if (deliveryLocation.sector) {
                                $("#sector").val(deliveryLocation.sector).trigger("change");
                            }

                            // Restore session date
                            if (deliveryLocation.date) {
                                $("#delivery-date").val(deliveryLocation.date);
                            }

                            // Restore session slot (select existing option, don’t append)
                            if (deliveryLocation.slot) {
                                $("#slot").val(deliveryLocation.slot);
                                $("#delivery-time-span").html(
                                        "<span style='color: green'>We will deliver your order on</span> <br>" +
                                        deliveryLocation.date +
                                        " <span style='color: green'>at</span> " + deliveryLocation
                                        .slot + ".")
                                    .css("font-weight", "bold")
                                    .css("font-size", "1.1rem");
                            }

                        } else {
                            toastr.error("Delivery is not available in your area.");
                        }
                    }
                });
            }

        });
    </script>
@endpush
