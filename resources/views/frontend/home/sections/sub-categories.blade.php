<section id="wsus__sub_categories" class="py-5 bg-white">
    <div class="container">
        <!-- Section Title with Divider -->
        <div class="text-center mb-4">
            <h2 class="section-title">Explore More</h2>
            <div class="section-divider">
                <span></span>
                <i class="fas fa-star"></i>
                <span></span>
            </div>
            <p class="section-subtitle">Discover our handpicked sub-categories</p>
        </div>

        <!-- Sub Categories -->
        <div class="row g-4 justify-content-center text-center">
            @foreach ($sub_categories as $category)
             <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="text-decoration-none">
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

@push('styles')
<style>
    /* Title */
    .section-title {
        font-size: 24px;
        font-weight: 700;
        color: #333;
    }
    .section-subtitle {
        font-size: 14px;
        color: #777;
        margin-top: 5px;
    }

    /* Divider */
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
        background: #ff6f61; /* accent color */
        margin: 0 10px;
        border-radius: 5px;
    }
    .section-divider i {
        color: #ff6f61;
        font-size: 16px;
    }
    .subcategory-img {
        border-radius: 16px; /* slightly curvy corners */
        overflow: hidden;
        margin: 0 auto;
        border: 2px solid #eee; /* light border */
        box-shadow: 0 6px 15px rgba(0,0,0,0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        transition: all 0.3s ease-in-out;
    }

    .subcategory-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .subcategory-img:hover {
        transform: translateY(-6px); /* lift effect */
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        border-color: #ff6f61; /* highlight border */
    }


</style>
@endpush
