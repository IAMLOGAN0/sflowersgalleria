@extends('frontend_v1.layouts.app')
@section('title')
{{$settings->site_name}}
@endsection

@section('content')


        <!--slider-->

        @include('frontend_v1.home.sections.slider')
        <!--/slider-->

        <!-- Categories -->
        @include('frontend_v1.home.sections.categories')
        <!-- /Categories -->

        <!-- Banner Collection -->
         {{-- @include('frontend_v1.home.sections.banner_collection') --}}
        <!-- /Banner Collection -->

        <!-- Deals -->
        {{-- @include('frontend_v1.home.sections.deals') --}}
        <!-- /Deals -->

        <!-- Popular product -->
         @include('frontend_v1.home.sections.popular_product')
        <!-- /Popular product -->

        <!-- Iconbox -->
         @include('frontend_v1.home.sections.iconbox')
        <!-- /Iconbox -->

        <!-- text-image -->
        @include('frontend_v1.home.sections.textImage')
        <!-- /text-image -->



@endsection
