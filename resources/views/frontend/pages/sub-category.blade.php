@extends('frontend.layouts.master')

@section('title')
{{$settings->site_name}} || About
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
            <section id="wsus__sub_categories" class="py-2 bg-white">
                <div class="container">
                    <!-- Sub Categories -->
                    <div class="row g-4 justify-content-center text-center">
                        @foreach ($sub_categories as $category)
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
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
