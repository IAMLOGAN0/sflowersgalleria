<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>

    <meta name="author" content="https://www.sflowersgalleria.com/">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="modern gift shop">

    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('frontend_v1/fonts/fonts.css') }}">

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('frontend_v1/fonts/font-icons.css') }}">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('frontend_v1/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend_v1/css/image-compare-viewer.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend_v1/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend_v1/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend_v1/css/styles.css') }}">

    <!-- Dynamic Favicon -->
    <link rel="shortcut icon" href="{{ asset($logoSetting->favicon ?? 'frontend_v1/images/logo/favicon.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset($logoSetting->favicon ?? 'frontend_v1/images/logo/favicon.png') }}">
</head>


<body class="preload-wrapper color-primary-8 color-main-text-2">

    <!-- RTL Toggle -->
    <a href="#" id="toggle-rtl" class="tf-btn animate-hover-btn btn-fill">RTL</a>

    <!-- Preloader -->
    <div class="preload preload-container">
        <div class="preload-logo">
            <div class="spinner"></div>
        </div>
    </div>
    <!--top bar-->
     @include('frontend_v1.components.topbar')

    <!-- Header + Menu -->
    @include('frontend_v1.components.header')
    @include('frontend_v1.components.menu')

    <!-- Main Content -->
    @yield('content')

    <!-- Footer & Components -->
    @include('frontend_v1.components.footer')
    @include('frontend_v1.components.gotop')
    @include('frontend_v1.components.toolbarbottom')
    @include('frontend_v1.components.mobile_menu')

    <!-- Javascript -->
    <script src="{{ asset('frontend_v1/js/jquery.min.js') }}"></script>
    <script src="{{ asset('frontend_v1/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('frontend_v1/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('frontend_v1/js/carousel.js') }}"></script>
    <script src="{{ asset('frontend_v1/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('frontend_v1/js/lazysize.min.js') }}"></script>
    <script src="{{ asset('frontend_v1/js/count-down.js') }}"></script>
    <script src="{{ asset('frontend_v1/js/wow.min.js') }}"></script>
    <script src="{{ asset('frontend_v1/js/multiple-modal.js') }}"></script>
    <script src="{{ asset('frontend_v1/js/main.js') }}"></script>

</body>
</html>
