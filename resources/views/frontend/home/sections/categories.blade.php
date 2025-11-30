<section id="wsus__categories" class="py-5 bg-light">
    <div class="container">

        <!-- Section Header -->
        <div class="text-center mb-4">
            <h2 class="section-title">Shop by Categories</h2>
            <div class="section-divider">
                <span></span>
                <i class="fas fa-th-large"></i>
                <span></span>
            </div>
            <p class="section-subtitle">Browse our wide range of products</p>
        </div>

        <!-- Category Grid -->
        <div class="row g-4 justify-content-center text-center">
            @foreach ($categories as $category)
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-4 col-4">
                <a href="{{ route('sub-categories', $category->id) }}" class="text-decoration-none">
                    <div class="category-card">
                        <div class="category-img">
                            <img src="{{ $category->image }}" alt="{{ $category->name }}" class="img-fluid">
                        </div>
                        <h6 class="mt-2 fw-semibold text-dark">{{ $category->name }}</h6>
                    </div>
                </a>
            </div>

            @endforeach
        </div>
    </div>
</section>

@push('styles')
<style>
    /* Section Header */
    .section-title {
        font-size: 26px;
        font-weight: 700;
        color: #333;
    }
    .section-subtitle {
        font-size: 14px;
        color: #666;
        margin-top: 5px;
    }
    .section-divider {
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 10px 0;
    }
    .section-divider span {
        display: block;
        height: 2px;
        width: 50px;
        background: #ff6f61;
        margin: 0 10px;
        border-radius: 5px;
    }
    .section-divider i {
        color: #ff6f61;
        font-size: 16px;
    }

    /* Category Card */
    .category-card {
        transition: all 0.3s ease-in-out;
    }
    .category-img {
        width: 100px;
        height: 100px;
        border-radius: 16px; /* Curvy corners */
        overflow: hidden;
        margin: 0 auto;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease-in-out;
        border: 2px solid #f1f1f1;
    }
    .category-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Hover Effect */
    .category-card:hover {
        transform: translateY(-6px);
    }
    .category-card:hover .category-img {
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        border-color: #ff6f61;
    }
    .category-card:hover h6 {
        color: #ff6f61;
    }

    .category-card h6 {
        font-size: 14px;
        margin-top: 8px;
        transition: color 0.3s ease;
    }
</style>
@endpush
