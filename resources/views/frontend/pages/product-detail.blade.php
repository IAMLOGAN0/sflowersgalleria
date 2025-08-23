@extends('frontend.layouts.master')

@section('title')
    {{ $settings->site_name }} || Product Details
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
                        <h4>products details</h4>
                        <ul>
                            <li><a href="#">home</a></li>
                            <li><a href="#">peoduct</a></li>
                            <li><a href="#">product details</a></li>
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
            PRODUCT DETAILS START
        ==============================-->
    <section id="wsus__product_details">
        <div class="container">
            <div class="wsus__details_bg">
                <div class="row">
                    <div class="col-xl-4 col-md-5 col-lg-5" style="z-index:900">
                        <div id="sticky_pro_zoom">
                            <div class="exzoom hidden" id="exzoom">
                                <div class="exzoom_img_box">
                                    @if ($product->video_link)
                                        <a class="venobox wsus__pro_det_video" data-autoplay="true" data-vbtype="video"
                                            href="{{ $product->video_link }}">
                                            <i class="fas fa-play"></i>
                                        </a>
                                    @endif
                                    <ul class='exzoom_img_ul'>
                                        <li><img class="zoom ing-fluid w-100" src="{{ asset($product->thumb_image) }}"
                                                alt="product"></li>
                                        @foreach ($product->productImageGalleries as $productImage)
                                            <li><img class="zoom ing-fluid w-100" src="{{ asset($productImage->image) }}"
                                                    alt="product"></li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="exzoom_nav"></div>
                                <p class="exzoom_btn">
                                    <a href="javascript:void(0);" class="exzoom_prev_btn"> <i
                                            class="far fa-chevron-left"></i> </a>
                                    <a href="javascript:void(0);" class="exzoom_next_btn"> <i
                                            class="far fa-chevron-right"></i> </a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-7">
                        <div class="product-details p-3">

                            <!-- Product Title -->
                            <h3 class="fw-bold mb-2">{{ $product->name }}</h3>

                            <!-- Stock Info -->
                            @if ($product->qty > 0)
                                <p class="text-success small mb-2">
                                    <i class="fas fa-check-circle"></i> In Stock ({{ $product->qty }} items)
                                </p>
                            @elseif ($product->qty === 0)
                                <p class="text-danger small mb-2">
                                    <i class="fas fa-times-circle"></i> Out of Stock
                                </p>
                            @endif

                            <!-- Price -->
                            <div class="price-box mb-3">
                                @if (checkDiscount($product))
                                    <h4 class="fw-bold text-dark">
                                        {{ $settings->currency_icon }}{{ $product->offer_price }}
                                        <span class="text-muted text-decoration-line-through fs-6">
                                            {{ $settings->currency_icon }}{{ $product->price }}
                                        </span>
                                    </h4>
                                @else
                                    <h4 class="fw-bold text-dark">{{ $settings->currency_icon }}{{ $product->price }}</h4>
                                @endif
                            </div>

                            <!-- Rating -->
                            <div class="d-flex align-items-center mb-3">
                                @php
                                    $avgRating = $product->reviews()->avg('rating');
                                    $fullRating = round($avgRating);
                                @endphp
                                <div class="stars me-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $fullRating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                                <small class="text-muted">({{ count($product->reviews) }} reviews)</small>
                            </div>

                            <!-- Short Description -->
                            <p class="text-muted mb-4">{!! $product->short_description !!}</p>

                            <!-- Variants -->
                            <form class="shopping-cart-form">
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="row">
                                    @foreach ($product->variants as $variant)
                                        @if ($variant->status != 0)
                                            <div class="col-sm-6 mb-3">
                                                <label class="fw-semibold mb-1">{{ $variant->name }}</label>
                                                <select class="form-select" name="variants_items[]">
                                                    @foreach ($variant->productVariantItems as $variantItem)
                                                        @if ($variantItem->status != 0)
                                                            <option value="{{ $variantItem->id }}"
                                                                {{ $variantItem->is_default == 1 ? 'selected' : '' }}>
                                                                {{ $variantItem->name }} (+ {{ $settings->currency_icon }}{{ $variantItem->price }})
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                                <!-- Delivery -->
                                <div class="delivery-box p-3 border rounded mb-4 bg-light">
                                    <h6 class="fw-bold">Delivery Options</h6>
                                    <input type="text" class="form-control my-2" placeholder="Enter Pincode" id="pincode" name="pincode">
                                    <input type="hidden" id="delivery-time" name="time">
                                    <div class="sector-box">
                                        <label class="fw-semibold mt-2">Choose Sector</label>
                                        <select class="form-select" id="sector" name="sector">
                                            <option disabled selected>-- Select Sector --</option>
                                        </select>
                                    </div>

                                    <label class="fw-semibold mt-2">Select Date & Time</label>
                                    <select class="form-select" id="slot" name="slot">
                                        <option disabled selected>-- Select Slot --</option>
                                    </select>
                                </div>

                                <!-- Quantity + Actions -->
                                <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-3 mt-4">

                                    <!-- Quantity Selector -->
                                    <div class="d-flex align-items-center border rounded-pill px-2 py-1 shadow-sm bg-light">
                                        <button type="button" class="btn btn-sm btn-light border-0 px-2" onclick="this.nextElementSibling.stepDown()">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number" class="form-control border-0 text-center shadow-none"
                                            name="qty" min="1" max="100" value="1"
                                            style="width: 60px; background: transparent;">
                                        <button type="button" class="btn btn-sm btn-light border-0 px-2" onclick="this.previousElementSibling.stepUp()">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="submit" class="btn btn-primary px-4 shadow-sm d-flex align-items-center">
                                            <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                                        </button>
                                        <button type="button" 
                                            class="btn btn-outline-secondary rounded-circle shadow-sm d-flex align-items-center justify-content-center"
                                            data-bs-toggle="modal" data-bs-target="#exampleModal"
                                            style="width:42px; height:42px;">
                                            <i class="far fa-comment-alt"></i>
                                        </button>
                                    </div>
                                    
                                      {{-- Wishlist Button (if enabled) --}}
                                        {{-- <button type="button" class="btn btn-outline-danger rounded-circle shadow-sm add_to_wishlist"
                                            data-id="{{ $product->id }}" style="width:42px; height:42px;">
                                            <i class="far fa-heart"></i>
                                        </button> --}}
                                </div>

                            </form>
                        </div>
                    </div>


                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="wsus__pro_det_description">
                        <div class="wsus__details_bg">
                            <ul class="nav nav-pills mb-3" id="pills-tab3" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pills-home-tab7" data-bs-toggle="pill"
                                        data-bs-target="#pills-home22" type="button" role="tab"
                                        aria-controls="pills-home" aria-selected="true">Description</button>
                                </li>

                                {{-- <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-contact" type="button" role="tab"
                                        aria-controls="pills-contact" aria-selected="false">Vendor Info</button>
                                </li> --}}
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-contact-tab2" data-bs-toggle="pill"
                                        data-bs-target="#pills-contact2" type="button" role="tab"
                                        aria-controls="pills-contact2" aria-selected="false">Reviews</button>
                                </li>

                            </ul>
                            <div class="tab-content" id="pills-tabContent4">
                                <div class="tab-pane fade  show active " id="pills-home22" role="tabpanel"
                                    aria-labelledby="pills-home-tab7">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="wsus__description_area">
                                                {!! $product->long_description !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                {{-- <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                                    aria-labelledby="pills-contact-tab">
                                    <div class="wsus__pro_det_vendor">
                                        <div class="row">
                                            <div class="col-xl-6 col-xxl-5 col-md-6">
                                                <div class="wsus__vebdor_img">
                                                    <img src="{{ asset($product->vendor->banner) }}" alt="vensor"
                                                        class="img-fluid w-100">
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-xxl-7 col-md-6 mt-4 mt-md-0">
                                                <div class="wsus__pro_det_vendor_text">
                                                    <h4>{{ $product->vendor->user->name }}</h4>
                                                    <p class="rating">
                                                        @php
                                                            $avgRating = $product->reviews()->avg('rating');
                                                            $fullRating = round($avgRating);
                                                        @endphp

                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if ($i <= $fullRating)
                                                                <i class="fas fa-star"></i>
                                                            @else
                                                                <i class="far fa-star"></i>
                                                            @endif
                                                        @endfor

                                                        <span>({{ count($product->reviews) }} review)</span>
                                                    </p>
                                                    <p><span>Store Name:</span> {{ $product->vendor->shop_name }}</p>
                                                    <p><span>Address:</span> {{ $product->vendor->address }}</p>
                                                    <p><span>Phone:</span> {{ $product->vendor->phone }}</p>
                                                    <p><span>mail:</span> {{ $product->vendor->email }}</p>
                                                    <a href="vendor_details.html" class="see_btn">visit store</a>
                                                </div>
                                            </div>
                                            <div class="col-xl-12">
                                                <div class="wsus__vendor_details">
                                                    {!! $product->vendor->description !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="tab-pane fade" id="pills-contact2" role="tabpanel"
                                    aria-labelledby="pills-contact-tab2">
                                    <div class="wsus__pro_det_review">
                                        <div class="wsus__pro_det_review_single">
                                            <div class="row">
                                                <div class="col-xl-8 col-lg-7">
                                                    <div class="wsus__comment_area">
                                                        <h4>Reviews <span>{{ count($reviews) }}</span></h4>
                                                        @foreach ($reviews as $review)
                                                            <div class="wsus__main_comment">
                                                                <div class="wsus__comment_img">
                                                                    <img src="{{ asset($review->user->image) }}"
                                                                        alt="user" class="img-fluid w-100">
                                                                </div>
                                                                <div class="wsus__comment_text reply">
                                                                    <h6>{{ $review->user->name }}
                                                                        <span>{{ $review->rating }} <i
                                                                                class="fas fa-star"></i></span></h6>
                                                                    <span>{{ date('d M Y', strtotime($review->created_at)) }}</span>
                                                                    <p>{{ $review->review }}
                                                                    </p>
                                                                    <ul class="">
                                                                        @if (count($review->productReviewGalleries) > 0)
                                                                            @foreach ($review->productReviewGalleries as $image)
                                                                                <li><img src="{{ asset($image->image) }}"
                                                                                        alt="product" class="img-fluid ">
                                                                                </li>
                                                                            @endforeach
                                                                        @endif

                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                        <div class="mt-5">
                                                            @if ($reviews->hasPages())
                                                                {{ $reviews->links() }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-5 mt-4 mt-lg-0">
                                                    @auth
                                                        @php
                                                            $isBrought = false;
                                                            $orders = \App\Models\Order::where([
                                                                'user_id' => auth()->user()->id,
                                                                'order_status' => 'delivered',
                                                            ])->get();
                                                            foreach ($orders as $key => $order) {
                                                                $existItem = $order
                                                                    ->orderProducts()
                                                                    ->where('product_id', $product->id)
                                                                    ->first();

                                                                if ($existItem) {
                                                                    $isBrought = true;
                                                                }
                                                            }

                                                        @endphp

                                                        @if ($isBrought === true)
                                                            <div class="wsus__post_comment rev_mar" id="sticky_sidebar3">
                                                                <h4>write a Review</h4>
                                                                <form action="{{ route('user.review.create') }}"
                                                                    enctype="multipart/form-data" method="POST">
                                                                    @csrf
                                                                    <p class="rating">
                                                                        <span>select your rating : </span>
                                                                    </p>

                                                                    <div class="row">

                                                                        <div class="col-xl-12 mb-4">
                                                                            <div class="wsus__single_com">
                                                                                <select name="rating" id=""
                                                                                    class="form-control">
                                                                                    <option value="">Select</option>
                                                                                    <option value="1">1</option>
                                                                                    <option value="2">2</option>
                                                                                    <option value="3">3</option>
                                                                                    <option value="4">4</option>
                                                                                    <option value="5">5</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-xl-12">
                                                                            <div class="col-xl-12">
                                                                                <div class="wsus__single_com">
                                                                                    <textarea cols="3" rows="3" name="review" placeholder="Write your review"></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="img_upload">
                                                                        <div class="">
                                                                            <input type="file" name="images[]" multiple>
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" name="product_id" id=""
                                                                        value="{{ $product->id }}">
                                                                    <input type="hidden" name="vendor_id" id=""
                                                                        value="{{ $product->vendor_id }}">

                                                                    <button class="common_btn" type="submit">submit
                                                                        review</button>
                                                                </form>
                                                            </div>
                                                        @endif
                                                    @endauth

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!--============================
            PRODUCT DETAILS END
        ==============================-->
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Send Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" class="message_modal">
                        @csrf
                        <div class="form-group">
                            <label for="">Message</label>
                            <textarea name="message" class="form-control mt-2 message-box"></textarea>
                            <input type="hidden" name="receiver_id" value="{{ $product->vendor->user_id }}">
                        </div>

                        <button type="submit" class="btn add_cart mt-4 send-button">Send</button>

                    </form>

                </div>

            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .delivery-box{
        background: #fff;
    }
</style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.message_modal').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();

                $.ajax({
                    method: 'POST',
                    url: '{{ route('user.send-message') }}',
                    data: formData,
                    beforeSend: function() {
                        let html =
                            `<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span> Sending..`

                        $('.send-button').html(html);
                        $('.send-button').prop('disabled', true);


                    },
                    success: function(response) {
                        $('.message-box').val('');
                        $('.modal-body').append(
                            `<div class="alert alert-success mt-2"><a href="{{ route('user.messages.index') }}" class="text-primary">Click here</a> for go to messenger.</div>`
                            )
                        toastr.success(response.message);
                    },
                    error: function(xhr, status, error) {
                        toastr.error(xhr.responseJSON.message);
                        $('.send-button').html('Send');
                        $('.send-button').prop('disabled', false);
                    },
                    complete: function() {
                        $('.send-button').html('Send');
                        $('.send-button').prop('disabled', false);
                    }
                })
            })
            var sectors  = [];

            $("#pincode").on('input', function() {
                let pincode = $(this).val().trim();
                if(pincode.length === 6) { // only fetch if 6 digits
                    $.ajax({
                        url: "{{ route('get-sectors-by-pincode') }}",
                        type: 'POST',
                        data: { pincode: pincode },
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            // Clear previous options
                            $("#sector").html('<option disabled selected>-- Select Sector --</option>');
                            $("#slot").html('<option disabled selected>-- Select Slot --</option>');

                            if(response.sectors && response.sectors.length > 0) {
                                toastr.success("Delivery is available in your area. Please select a sector.");
                                sectors = response.sectors; // save to global variable
                                response.sectors.forEach(function(sector) {
                                    $("#sector").append('<option value="'+sector.id+'">'+sector.sector+'</option>');
                                });
                            }else{
                                toastr.error("Delivery is not available in your area.");
                            }
                        },
                        error: function(xhr) {
                            alert(xhr.responseText);
                        }
                    });
                } else {
                    $("#slot").html('<option disabled selected>-- Select Slot --</option>');
                }
            });

            $("#sector").on('change', function() {
                let sectorId = $(this).val();

                // find the selected sector from stored array
                let selectedSector = sectors.find(s => s.id == sectorId);

                // clear slot dropdown
                $("#slot").html('<option disabled selected>-- Select Slot --</option>');

                if(selectedSector && selectedSector.t_time) {
                    toastr.success("Delivery is available in your area. Please select a slot.");
                    let slots = JSON.parse(selectedSector.t_time); // ✅ FIXED
                    slots.forEach(function(slot) {
                        $("#slot").append('<option value="'+slot+'">'+slot+'</option>');
                    });
                    $("#delivery-time").val(selectedSector.b_time);
                }else{
                    toastr.error("Delivery is not available in your sector.");
                }
            });


        })
    </script>
@endpush
