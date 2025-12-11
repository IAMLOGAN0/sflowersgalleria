<!-- slider -->
<div class="tf-slideshow slider-effect-fade slider-grocery position-relative flat-spacing-25 pb_0">
    <div class="container">
        <div dir="ltr"
             class="swiper tf-sw-slideshow radius-20"
             data-preview="1"
             data-tablet="1"
             data-mobile="1"
             data-centered="false"
             data-space="0"
             data-loop="true"
             data-auto-play="true"
             data-delay="3000"
             data-speed="1000">

            <div class="swiper-wrapper">

                @foreach ($sliders as $slider)
                    <div class="swiper-slide" lazy="true">
                        <div class="wrap-slider">

                            <!-- Background Image -->
                            <img class="lazyload"
                                 data-src="{{$slider->banner }}"
                                 src="{{$slider->banner }}"
                                 alt="slider-image">

                            <div class="box-content">
                                <div class="container">

                                    <!-- Dynamic Title / Text -->
                                    <h2 class="fade-item fade-item-2 fw-6 heading">
                                        {!! $slider->title !!}
                                    </h2>

                                    @if ($slider->type)
                                        <p class="fade-item fade-item-1 fw-6 d-block">
                                            {!! $slider->type !!}
                                        </p>
                                    @endif

                                    <!-- Button -->
                                    @if ($slider->btn_url)
                                        <div class="fade-item fade-item-3">
                                            <a href="{{ $slider->btn_url }}"
                                               class="tf-btn btn-fill animate-hover-btn btn-xl radius-60">
                                                <span>Shop collection</span>
                                                <i class="icon icon-arrow-right"></i>
                                            </a>
                                        </div>
                                    @endif

                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach

            </div><!-- /.swiper-wrapper -->

            <!-- Pagination Dots -->
            <div class="wrap-pagination">
                <div class="container">
                    <div class="sw-dots sw-pagination-slider justify-content-xl-start justify-content-center"></div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- /slider -->
