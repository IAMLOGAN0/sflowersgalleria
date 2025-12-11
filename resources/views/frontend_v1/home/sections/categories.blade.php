<!-- Categories -->
<section class="flat-spacing-30 flat-control-sw">
    <div class="container">
        <div class="flat-title flex-row justify-content-between px-0">
            <span class="title fw-6 wow fadeInUp" data-wow-delay="0s">Featured Categories</span>
            <div class="box-sw-navigation">
                <div class="sw-dots style-2 medium sw-pagination-recent justify-content-center"></div>
            </div>
        </div>

        <div dir="ltr" class="swiper tf-sw-recent wow fadeInUp" data-preview="6" data-tablet="3" data-mobile="2"
            data-space-lg="30" data-space-md="30" data-space="15" data-pagination="2" data-pagination-md="3"
            data-pagination-lg="3">

            <div class="swiper-wrapper">

@foreach ($categories as $category)
                    <div class="swiper-slide">
                        <div class="collection-item-circle has-bg has-bg-2 hover-img">

                            <a href="{{ route('sub-categories', $category->id) }}"
                               class="collection-image img-style">

                                <img class="lazyload"
                                     data-src="{{$category->image}}"
                                     src="{{$category->image}}"
                                     alt="{{$category->name}}">
                            </a>

                            <div class="collection-content text-center">
                                <a href="{{ route('sub-categories', $category->id) }}"
                                   class="link title fw-5">
                                    {{ $category->name }}
                                </a>
                            </div>

                        </div>
                    </div>

                @endforeach



            </div><!-- swiper-wrapper -->
        </div>
    </div>
</section>
<!-- /Categories -->
