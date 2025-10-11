<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densityDpi=device-dpi" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="fast2sms" content="kwqq9Y1YREJ0Swrm7IW2hHstQpCDDDsc">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <title>
        @yield('title')
    </title>
    <link rel="icon" type="image/png" href="{{ asset($logoSetting->favicon) }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/jquery.nice-number.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/jquery.calendar.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/add_row_custon.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/mobile_menu.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/jquery.exzoom.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/multiple-image-video.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/ranger_style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/jquery.classycountdown.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/venobox.min.css') }}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/responsive.css') }}">
    @if ($settings->layout === 'RTL')
        <link rel="stylesheet" href="{{ asset('frontend/css/rtl.css') }}">
    @endif
    @vite(['resources/js/app.js'])
    @stack('styles')
        <style>
        /* Main Account Button */
        .custom-dropdown > a {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 6px 10px;
            color: #333;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border-radius: 6px;
        }

        .custom-dropdown > a:hover {
            background: transparent;
            color: #007bff;
        }

        /* Dropdown Menu */
        .custom-dropdown .dropdown-menu {
            display: none;
            position: absolute;
            top: 78%;
            right: 0;
            background: #fff;
            min-width: 165px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            padding: 2px 0; /* very compact */
            list-style: none;
            z-index: 999;
        }

        /* Show Menu on Hover */
        .custom-dropdown:hover .dropdown-menu {
            display: block;
        }

        /* Dropdown Item */
        .custom-dropdown .dropdown-item {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px; /* tighter spacing */
            color: #333;
            font-size: 14px;
            line-height: 1.3;
            text-decoration: none;
            transition: all 0.2s ease;
            border-radius: 5px;
        }

        .custom-dropdown .dropdown-menu > li {
            margin-left: 0 !important;
        }

        /* Icon Style */
        .custom-dropdown .dropdown-item i {
            font-size: 14px;
            color: #6c757d;
            transition: color 0.2s ease;
        }

        /* Hover + Active States */
        .custom-dropdown .dropdown-item:hover {
            background: #f5f7fa;
            color: #007bff;
        }

        .custom-dropdown .dropdown-item:hover i,
        .custom-dropdown .dropdown-item.active i {
            color: #007bff;
        }

        .custom-dropdown .dropdown-item.active {
            color: #007bff !important;
            font-weight: 500;
            background: #e8eaec;
        }
        .custom-dropdown .dropdown-item.active,
        .custom-dropdown .dropdown-item.active a {
            color: #007bff !important;
            font-weight: 500;
            background: #e8eaec;
        }

        /* Divider */
        .custom-dropdown .dropdown-menu hr {
            margin: 4px 0;
            border: none;
            border-top: 1px solid #eee;
        }

        /* Logout Button */
        .custom-dropdown .dropdown-item.logout {
            color: #dc3545;
            font-weight: 500;
        }

        .custom-dropdown .dropdown-item.logout:hover {
            background: #dc3545;
            color: #fff;
        }

        .custom-dropdown .dropdown-item.logout:hover i {
            color: #fff;
        }




    </style>
</head>

<body>

    <!--============================
        HEADER START
    ==============================-->
    @include('frontend.layouts.header')
    <!--============================
        HEADER END
    ==============================-->


    <!--============================
        MAIN MENU START
    ==============================-->
    @include('frontend.layouts.menu')
    <!--============================
        MAIN MENU END
    ==============================-->


    <!--============================
        Main Content Start
    ==============================-->
    @yield('content')
    <!--============================
       Main Content End
    ==============================-->


    <section class="product_popup_modal">
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content product-modal-content">

                </div>
            </div>
        </div>
    </section>

    <!--============================
        FOOTER PART START
    ==============================-->
    @include('frontend.layouts.footer')
    <!--============================
        FOOTER PART END
    ==============================-->


    <!--============================
        SCROLL BUTTON START
    ==============================-->
    <div class="wsus__scroll_btn">
        <i class="fas fa-chevron-up"></i>
    </div>
    <!--============================
        SCROLL BUTTON  END
    ==============================-->


    <!--jquery library js-->
    <script src="{{ asset('frontend/js/jquery-3.6.0.min.js') }}"></script>
    <!--bootstrap js-->
    <script src="{{ asset('frontend/js/bootstrap.bundle.min.js') }}"></script>
    <!--font-awesome js-->
    <script src="{{ asset('frontend/js/Font-Awesome.js') }}"></script>
    <!--select2 js-->
    <script src="{{ asset('frontend/js/select2.min.js') }}"></script>
    <!--slick slider js-->
    <script src="{{ asset('frontend/js/slick.min.js') }}"></script>
    <!--simplyCountdown js-->
    <script src="{{ asset('frontend/js/simplyCountdown.js') }}"></script>
    <!--product zoomer js-->
    <script src="{{ asset('frontend/js/jquery.exzoom.js') }}"></script>
    <!--nice-number js-->
    <script src="{{ asset('frontend/js/jquery.nice-number.min.js') }}"></script>
    <!--counter js-->
    <script src="{{ asset('frontend/js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('frontend/js/jquery.countup.min.js') }}"></script>
    <!--add row js-->
    <script src="{{ asset('frontend/js/add_row_custon.js') }}"></script>
    <!--multiple-image-video js-->
    <script src="{{ asset('frontend/js/multiple-image-video.js') }}"></script>
    <!--sticky sidebar js-->
    <script src="{{ asset('frontend/js/sticky_sidebar.js') }}"></script>
    <!--price ranger js-->
    <script src="{{ asset('frontend/js/ranger_jquery-ui.min.js') }}"></script>
    <script src="{{ asset('frontend/js/ranger_slider.js') }}"></script>
    <!--isotope js-->
    <script src="{{ asset('frontend/js/isotope.pkgd.min.js') }}"></script>
    <!--venobox js-->
    <script src="{{ asset('frontend/js/venobox.min.js') }}"></script>
    <!--Toaster js-->
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!--Sweetalert js-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--classycountdown js-->
    <script src="{{ asset('frontend/js/jquery.classycountdown.js') }}"></script>


    <!--main/custom js-->
    <script src="{{ asset('frontend/js/main.js') }}"></script>


    @if ($errors->any())
        @foreach ($errors->all() as $error)
            toastr.error("{{ $error }}")
        @endforeach
    @endif

    <script>
        $(document).ready(function() {
            $('.auto_click').click();
        })
    </script>
    @include('frontend.layouts.scripts')
    @stack('scripts')
</body>

</html>
