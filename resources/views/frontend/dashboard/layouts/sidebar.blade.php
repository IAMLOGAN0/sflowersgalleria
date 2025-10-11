<div class="dashboard_sidebar">
  <a href="javascript:;" class="dash_logo d-block text-center mb-4">
    <img src="{{ asset($logoSetting->logo) }}" alt="logo" class="img-fluid">
  </a>

  <ul>
    <li><a href="{{ route('user.dashboard') }}" class="{{ setActive(['user.dashboard']) }}"><i class="fas fa-home"></i> Dashboard</a></li>

    <li><a href="{{ url('/') }}"><i class="fas fa-store"></i> Go To Home Page</a></li>

    @if (auth()->user()->role === 'vendor')
      <li><a href="{{ route('vendor.dashbaord') }}" class="{{ setActive(['vendor.dashbaord']) }}"><i class="fas fa-briefcase"></i> Vendor Dashboard</a></li>
    @endif

    <li><a href="{{ route('user.orders.index') }}" class="{{ setActive(['user.orders.*']) }}"><i class="fas fa-list-ul"></i> Orders</a></li>
    <li><a href="{{ route('user.review.index') }}" class="{{ setActive(['user.review.*']) }}"><i class="fas fa-star"></i> Reviews</a></li>
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
