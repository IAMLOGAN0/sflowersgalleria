@php
    $categories = \App\Models\Category::where('status', 1)
        ->with([
            'subCategories' => function ($query) {
                $query->where('status', 1)->with([
                    'childCategories' => function ($query) {
                        $query->where('status', 1);
                    },
                ]);
            },
        ])
        ->get();
@endphp
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

    <nav class="wsus__main_menu d-none d-lg-block">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="relative_contect d-flex">
                        <div class="wsus_menu_category_bar">
                            <i class="far fa-bars"></i>
                        </div>
                        <ul class="wsus_menu_cat_item show_home toggle_menu">
                            @foreach ($categories as $category)
                                <li><a class="{{ count($category->subCategories) > 0 ? 'wsus__droap_arrow' : '' }}"
                                        href="{{ route('products.index', ['category' => $category->slug]) }}"><i
                                            class="{{ $category->icon }}"></i> {{ $category->name }} </a>
                                    @if (count($category->subCategories) > 0)
                                        <ul class="wsus_menu_cat_droapdown">
                                            @foreach ($category->subCategories as $subCategory)
                                                <li><a
                                                        href="{{ route('products.index', ['subcategory' => $subCategory->slug]) }}">{{ $subCategory->name }}
                                                        <i
                                                            class="{{ count($subCategory->childCategories) > 0 ? 'fas fa-angle-right' : '' }}"></i></a>
                                                    @if (count($subCategory->childCategories) > 0)
                                                        <ul class="wsus__sub_category">
                                                            @foreach ($subCategory->childCategories as $childCategory)
                                                                <li><a
                                                                        href="{{ route('products.index', ['childcategory' => $childCategory->slug]) }}">{{ $childCategory->name }}</a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endforeach

                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>

                        <ul class="wsus__menu_item">
                            <li><a class="{{ setActive(['home']) }}" href="{{ url('/') }}">home</a></li>
                            <li><a class="{{ setActive(['blog']) }}" href="{{ route('blog-category') }}">events</a></li>
                            <li><a class="{{ setActive(['about']) }}" href="{{ route('about') }}">about</a></li>
                            <li><a class="{{ setActive(['contact']) }}" href="{{ route('contact') }}">contact</a></li>
                        </ul>
                        <ul class="wsus__menu_item wsus__menu_item_right">
                            {{-- <li><a href="{{ route('product-traking.index') }}">Track order</a></li> --}}
                            @if (auth()->check())
                                @if (auth()->user()->role === 'user')
                                    {{-- <li><a href="{{ route('user.dashboard') }}">My Account</a></li> --}}
                                    <li class="dropdown custom-dropdown">
                                        <a class="dropdown-toggle" href="#" id="accountDropdown" role="button">
                                            <i class="fas fa-user"></i> My Account
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="accountDropdown">
                                            <li class="dropdown-item {{ setActive(['user.dashboard']) }}">
                                                <a  href="{{ route('user.dashboard') }}">
                                                    <i class="fas fa-chart-bar"></i> Dashboard
                                                </a>
                                            </li>
                                            <li class="dropdown-item {{ setActive(['user.orders.index']) }}">
                                                <a  href="{{ route('user.orders.index') }}">
                                                    <i class="fas fa-list-ul"></i> Orders
                                                </a>
                                            </li>
                                            <li class="dropdown-item {{ setActive(['user.profile']) }}">
                                                <a  href="{{ route('user.profile') }}">
                                                    <i class="fas fa-user-circle"></i> My Profile
                                                </a>
                                            </li>
                                            <li class="dropdown-item {{ setActive(['user.address.index']) }}">
                                                <a  href="{{ route('user.address.index') }}">
                                                    <i class="fas fa-map-marker-alt"></i> Addresses
                                                </a>
                                            </li>
                                            <li><hr></li>
                                            <li>
                                                <form method="POST" action="{{ route('logout') }}">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item logout" style="margin-top: 0px; margin-left: 15px;">
                                                        <i class="fas fa-sign-out-alt"></i> Logout
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </li>

                                @elseif (auth()->user()->role === 'vendor')
                                    <li><a href="{{ route('vendor.dashbaord') }}">Vendor Dashboard</a></li>
                                @elseif (auth()->user()->role === 'admin')
                                    <li><a href="{{ route('admin.dashbaord') }}">Admin Dashboard</a></li>
                                @endif
                            @else
                                <li><a href="{{ route('login') }}">login</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <section id="wsus__mobile_menu">
        <span class="wsus__mobile_menu_close"><i class="fal fa-times"></i></span>
        <ul class="wsus__mobile_menu_header_icon d-inline-flex">

            @if (auth()->check())
                @if (auth()->user()->role === 'user')
                    <li><a href="{{ route('user.dashboard') }}"><i class="fal fa-user"></i></a></li>
                {{-- @elseif (auth()->user()->role === 'vendor')
                    <li><a href="{{ route('vendor.dashbaord') }}"><i class="fal fa-user"></i></a></li> --}}
                @elseif (auth()->user()->role === 'admin')
                    <li><a href="{{ route('admin.dashbaord') }}"><i class="fal fa-user"></i></a></li>
                @endif
            @else
                <li><a href="{{ route('login') }}"><i class="fal fa-user"></i></a></li>
            @endif
        </ul>
        <form action="{{ route('products.index') }}">
            <input type="text" placeholder="Search..." name="search" value="{{ request()->search }}">
            <button type="submit"><i class="far fa-search"></i></button>
        </form>

        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home"
                    role="tab" aria-controls="pills-home" aria-selected="true">User Menu</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile"
                    role="tab" aria-controls="pills-profile" aria-selected="false">Main Menu</button>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <div class="wsus__mobile_menu_main_menu">
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <ul class="wsus_mobile_menu_category">
                            <li class="orders"><a href="{{ route('user.orders.index') }}" class="{{ setActive(['user.orders.*']) }}"><i class="fas fa-list-ul"></i> Orders</a></li>
                            {{-- <li><a href="{{ route('user.review.index') }}" class="{{ setActive(['user.review.*']) }}"><i class="fas fa-star"></i> Reviews</a></li> --}}
                            <li><a href="{{ route('user.profile') }}" class="{{ setActive(['user.profile']) }}"><i class="fas fa-user-circle"></i> My Profile</a></li>
                            <li><a href="{{ route('user.address.index') }}" class="{{ setActive(['user.address.*']) }}"><i class="fas fa-map-marker-alt"></i> Addresses</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                <div class="wsus__mobile_menu_main_menu">
                    <div class="accordion accordion-flush" id="accordionFlushExample2">
                        <ul>
                            <li><a href="{{ route('home') }}">home</a></li>

                            {{-- <li><a href="{{ route('vendor.index') }}">vendor</a></li> --}}

                            <li><a href="{{ route('blog-category') }}">Events</a></li>
                            <li><a href="{{ route('about') }}">about us</a></li>
                            <li><a href="{{ route('contact') }}">contact</a></li>
                            <li><a href="{{ route('product-traking.index') }}">track order</a></li>
                            {{-- <li><a href="{{ route('flash-sale') }}">flash sale</a></li> --}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
