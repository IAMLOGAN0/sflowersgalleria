@extends('frontend.dashboard.layouts.master')

@section('title')
  {{ $settings->site_name }} || Dashboard
@endsection

@section('content')
  <div class="container-fluid">
    <h3 class="fw-bold mb-4">Welcome Back, {{ auth()->user()->name }} 👋</h3>
    <div class="row g-4">
      <div class="col-sm-6 col-md-4 col-xl-2">
        <div class="dashboard-card">
          <i class="fas fa-shopping-bag"></i>
          <p>Total Orders</p>
          <h4>{{ $totalOrder }}</h4>
        </div>
      </div>

      <div class="col-sm-6 col-md-4 col-xl-2">
        <div class="dashboard-card">
          <i class="fas fa-clock"></i>
          <p>Pending Orders</p>
          <h4>{{ $pendingOrder }}</h4>
        </div>
      </div>

      <div class="col-sm-6 col-md-4 col-xl-2">
        <div class="dashboard-card">
          <i class="fas fa-check-circle"></i>
          <p>Completed Orders</p>
          <h4>{{ $completeOrder }}</h4>
        </div>
      </div>

      <div class="col-sm-6 col-md-4 col-xl-2">
        <div class="dashboard-card">
          <i class="fas fa-star"></i>
          <p>Reviews</p>
          <h4>{{ $reviews }}</h4>
        </div>
      </div>

      <div class="col-sm-6 col-md-4 col-xl-2">
        <div class="dashboard-card">
          <i class="fas fa-heart"></i>
          <p>Wishlist</p>
          <h4>{{ $wishlist }}</h4>
        </div>
      </div>

      <div class="col-sm-6 col-md-4 col-xl-2">
        <div class="dashboard-card">
          <i class="fas fa-user-shield"></i>
          <p>Profile</p>
          <h4>-</h4>
        </div>
      </div>
    </div>
  </div>
@endsection
