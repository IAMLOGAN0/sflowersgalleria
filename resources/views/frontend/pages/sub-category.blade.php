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
        width: 100%;
        height: 220px;
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
           {{-- @if (request()->has('search'))
           <h5>Search: {{request()->search}}</h5>
           <hr>
           @elseif (request()->has('category'))
           <h5>Search: {{request()->category}}</h5>
           <hr>
           @endif --}}
            <div class="row">
@foreach ($sub_categories as $blog)
<div class="col-xl-3 col-lg-4 col-md-6 mb-4">
    <a href="{{ route('products.index', ['subcategory' => $blog->slug]) }}" class="text-decoration-none">
        <div class="fnp-card">
            <img src="{{ asset($blog->image) }}" alt="">
            <h4 class="fnp-name">{{ $blog->name }}</h4>
        </div>
    </a>
</div>
@endforeach
            </div>
            @if (count($sub_categories) === 0)
            <div class="row">
                <div class="card">
                    <div class="card-body text-center">
                        <h3>Sorry No Sub Categories Found!</h3>
                    </div>
                </div>
            </div>
            @endif
            <div id="pagination">
                <div class="mt-5">
                    @if ($sub_categories->hasPages())
                        {{$sub_categories->withQueryString()->links()}}
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!--============================
        BLOGS PAGE END
    ==============================-->
@endsection
