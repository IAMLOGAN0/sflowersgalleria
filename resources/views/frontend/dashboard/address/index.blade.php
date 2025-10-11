@extends('frontend.dashboard.layouts.master')

@section('content')
<section id="wsus__dashboard">
    <div class="container-fluid">
      <div class="row">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-9 col-md-10">
                <div class="dashboard_content mt-2 mt-md-0">
                    {{-- <h3><i class="fal fa-gift-card"></i> address</h3> --}}
                    <h3 class="dashboard-title text-center">
                        <i class="fal fa-gift-card"></i> address
                    </h3>
                    <div class="wsus__dashboard_add">
                        <div class="row">
                            <div class="col-12 mb-2 text-end">
                            <a href="{{route('user.address.create')}}" class="add_address_btn common_btn float-end"><i class="far fa-plus"></i>
                                add</a>
                            </div>
                            @foreach ($addresses as $address)
                            <div class="col-xl-6">
                            <div class="wsus__dash_add_single">
                                <h4>Billing Address</h4>
                                <ul>
                                <li><span>name :</span> {{$address->name}}</li>
                                <li><span>Phone :</span> {{$address->phone}}</li>
                                <li><span>email :</span> {{$address->email}}</li>
                                <li><span>country :</span> {{$address->country}}</li>
                                <li><span>state :</span> {{$address->state}}</li>
                                <li><span>city :</span> {{$address->city}}</li>
                                <li><span>zip code :</span> {{$address->zip}}</li>
                                <li><span>address :</span> {{$address->address}}</li>
                                </ul>
                                <div class="wsus__address_btn">
                                <a href="{{route('user.address.edit', $address->id)}}" class="edit"><i class="fal fa-edit"></i> edit</a>
                                <a href="{{route('user.address.destroy', $address->id)}}" class="del delete-item"><i class="fal fa-trash-alt"></i> delete</a>
                                </div>
                            </div>
                            </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </section>
@endsection
@push('styles')
<style>
    /* Make sure title is always visible */
    .dashboard-title {
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
        font-weight: 700;
        color: #333;
        word-wrap: break-word;
        white-space: normal;
    }

    .dashboard-title i {
        color: var(--bs-primary, #0d6efd);
    }

    /* Fix possible overflow/hidden issues */
    #wsus__dashboard .dashboard_content {
        overflow: visible !important;
    }

    /* Adjust for mobile screens */
    @media (max-width: 575.98px) {
        .dashboard-title {
            font-size: 1.25rem;
            text-align: center;
            padding: 10px 0;
            margin-top: 70px;
        }
    }
</style>
@endpush
