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
                        <h4>our latest events</h4>
                        <ul>
                            <li><a href="#">home</a></li>
                            <li><a href="#">events category</a></li>
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
    <section id="wsus__blogs" class="py-5 bg-white">
        <div class="container">
            <div class="row g-4 justify-content-center">
                @foreach ($categories as $category)
                    <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                        <a href="{{ url('blog') . '?category=' . urlencode($category->name) }}"
                        class="text-decoration-none text-dark d-block text-center blog-card">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                                <img src="{{ $category->image }}" alt="blog" class="img-fluid w-100" style="height:150px; object-fit:cover;">
                            </div>
                            <p class="fw-semibold mt-2 mb-0">{!! limitText($category->name, 45) !!}</p>
                        </a>
                    </div>
                @endforeach

                @if (count($categories) === 0)
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body text-center py-5">
                                <h3 class="fw-bold text-muted">😔 Sorry, No Blog Found!</h3>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Pagination --}}
            <div id="pagination" class="mt-5 d-flex justify-content-center">
                @if ($categories->hasPages())
                    {{ $categories->withQueryString()->links() }}
                @endif
            </div>
        </div>
    </section>



    <!--============================
        BLOGS PAGE END
    ==============================-->
@endsection

@push('styles')
<style>
    #wsus__blogs .blog-card img {
        border-radius: 15px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    #wsus__blogs .blog-card:hover img {
        transform: scale(1.05);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    #wsus__blogs p {
        font-size: 15px;
        color: #333;
    }
</style>
@endpush
