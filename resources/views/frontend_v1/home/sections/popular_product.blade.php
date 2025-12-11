@php
    $popularCategories = json_decode($popularCategory->value, true);
    $tabs = [];
    $tabProducts = [];

    foreach ($popularCategories as $key => $popularCategoryItem) {

        $lastKey = [];
        foreach ($popularCategoryItem as $k => $c) {
            if ($c === null) break;
            $lastKey = [$k => $c];
        }

        if (array_key_first($lastKey) === 'category') {
            $cat = \App\Models\Category::find($lastKey['category']);
            $tabs[] = $cat->name;

            $tabProducts[] = \App\Models\Product::withAvg('reviews', 'rating')
                ->with(['variants', 'category', 'productImageGalleries'])
                ->where('category_id', $cat->id)
                ->where('status', 1)
                ->orderBy('id', 'DESC')->take(12)->get();

        } elseif (array_key_first($lastKey) === 'sub_category') {
            $cat = \App\Models\SubCategory::find($lastKey['sub_category']);
            $tabs[] = $cat->name;

            $tabProducts[] = \App\Models\Product::withAvg('reviews', 'rating')
                ->with(['variants', 'category', 'productImageGalleries'])
                ->where('sub_category_id', $cat->id)
                 ->where('status', 1)
                ->orderBy('id', 'DESC')->take(12)->get();

        } else {
            $cat = \App\Models\ChildCategory::find($lastKey['child_category']);
            $tabs[] = $cat->name;

            $tabProducts[] = \App\Models\Product::withAvg('reviews', 'rating')
                ->with(['variants', 'category', 'productImageGalleries'])
                ->where('child_category_id', $cat->id)
                 ->where('status', 1)
                ->orderBy('id', 'DESC')->take(12)->get();
        }
    }
@endphp


<section class="flat-spacing-5 pt_0">
    <div class="container">
        <div class="flat-animate-tab">

            <!-- section title -->
            <div class="flat-title flat-title-tab flex-row justify-content-between px-0">
                <span class="title text-nowrap fw-6 wow fadeInUp" data-wow-delay="0s">Popular Products</span>

                <!-- DYNAMIC TABS -->
                <ul class="widget-tab-5" role="tablist">
                    @foreach ($tabs as $index => $tabTitle)
                        <li class="nav-tab-item">
                            <a href="#tab-{{ $index }}"
                               class="{{ $index === 0 ? 'active' : '' }}"
                               data-bs-toggle="tab">
                               {{ $tabTitle }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- TAB CONTENT -->
            <div class="tab-content">

                @foreach ($tabProducts as $index => $products)
                    <div class="tab-pane {{ $index === 0 ? 'active show' : '' }}" id="tab-{{ $index }}">

                        <div class="tf-grid-layout tf-col-2 xl-col-4">

                            @foreach ($products as $item)
                                <div class="card-product style-9">
                                    <div class="card-product-wrapper">

                                        <a href="{{ route('product-detail', $item->slug) }}" class="product-img">
                                            <img class="lazyload img-product"
                                                 src="{{ asset($item->thumb_image) }}"
                                                 data-src="{{ asset($item->thumb_image) }}"
                                                 alt="{{ $item->name }}">

                                            @if ($item->productImageGalleries->first())
                                                <img class="lazyload img-hover"
                                                     src="{{ asset($item->productImageGalleries->first()->image) }}"
                                                     data-src="{{ asset($item->productImageGalleries->first()->image) }}"
                                                     alt="{{ $item->name }}">
                                            @endif
                                        </a>

                                        {{-- <div class="list-product-btn absolute-2">
                                            <a href="#" class="box-icon bg_white wishlist btn-icon-action">
                                                <span class="icon icon-heart"></span>
                                                <span class="tooltip">Add to Wishlist</span>
                                            </a>

                                            <a href="#compare" data-bs-toggle="offcanvas" class="box-icon bg_white compare btn-icon-action">
                                                <span class="icon icon-compare"></span>
                                                <span class="tooltip">Add to Compare</span>
                                            </a>

                                            <a href="#quick_view" data-bs-toggle="modal" class="box-icon bg_white quickview tf-btn-loading">
                                                <span class="icon icon-view"></span>
                                                <span class="tooltip">Quick View</span>
                                            </a>
                                        </div> --}}

                                    </div>

                                    <div class="card-product-info">
                                        <div class="inner-info">
                                            <a href="{{ route('product-detail', $item->slug) }}" class="title link fw-6">
                                                {{ $item->name }}
                                            </a>

                                            @if (checkDiscount($item))
                                                <span class="price fw-6">
                                                    {{ $settings->currency_icon }}{{ $item->offer_price }}
                                                    <del>{{ $settings->currency_icon }}{{ $item->price }}</del>
                                                </span>
                                            @else
                                                <span class="price fw-6">
                                                    {{ $settings->currency_icon }}{{ $item->price }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="list-product-btn">
                                            {{-- <a href="#quick_add" data-bs-toggle="modal" class="box-icon quick-add tf-btn-loading">
                                                <span class="icon icon-bag"></span>
                                                <span class="tooltip">Add to cart</span>
                                            </a> --}}
                                            <a href="javascript:void(0)"  class="box-icon add-to-cart-btn tf-btn-loading">
                                                <span class="icon icon-bag"></span>
                                                <span class="tooltip">Add to cart</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                @endforeach

            </div> <!-- /tab-content -->
        </div>
    </div>
</section>
