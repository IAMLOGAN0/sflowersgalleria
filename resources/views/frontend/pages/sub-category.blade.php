@extends('frontend.layouts.master')

@section('title')
{{$settings->site_name}} || About
@endsection
@push('styles')
<style>
    .fnp-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 4px 18px rgba(0,0,0,0.06);
        transition: all 0.25s ease;
        text-align: center;
    }

    .fnp-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 28px rgba(0,0,0,0.12);
    }

    .fnp-card img {
        /* width: 100%;
        height: 220px; */
        object-fit: cover;
        transition: 0.3s;
    }

    .fnp-card:hover img {
        transform: scale(1.04);
    }

    .fnp-name {
        font-size: 1.15rem;
        font-weight: 600;
        color: #222;
        padding: 16px 12px 20px;
        margin: 0;
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
                        <h4>Sub Categories</h4>
                        <ul>
                            <li><a href="/">home</a></li>
                            <li><a href="/sub-categories">Sub Categories</a></li>
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
        BLOGS PAGE START
    ==============================-->
    <section id="wsus__blogs">
        <div class="container">
            <section id="wsus__sub_categories" class="py-2">
                <div class="container">
                    <!-- Sub Categories -->
                    <div class="row g-4 justify-content-center text-center">
                        @foreach ($sub_categories as $category)
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 fnp-card">
                            <a href="{{ route('products.index', ['subcategory' => $category->slug]) }}" class="text-decoration-none">
                                <div class="subcategory-card">
                                    <div class="subcategory-img">
                                        <img src="{{ $category->image }}" alt="{{ $category->name }}" class="img-fluid">
                                    </div>
                                    <p class="subcategory-name">{{ $category->name }}</p>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>
    </section>
    <!--============================
        BLOGS PAGE END
    ==============================-->
@endsection
