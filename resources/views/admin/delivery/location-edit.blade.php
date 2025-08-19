@extends('admin.layouts.master')

@section('title', 'Edit Delivery Location')

@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Location</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Edit Sector</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.locations.update', $location->id) }}" method="POST">
                                @csrf
                                {{-- sector --}}
                                <div class="form-group">
                                    <label for="sector">Sector Name <span class="text-danger">*</span></label>
                                    <input type="text" id="sector" name="sector" class="form-control @error('sector') is-invalid @enderror"
                                        value="{{ old('sector', $location->sector) }}" required>
                                    @error('sector')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- pin --}}
                                <div class="form-group">
                                    <label for="pin">Pincode <span class="text-danger">*</span></label>
                                    <input type="text" id="pin" name="pin" class="form-control @error('pin') is-invalid @enderror"
                                        value="{{ old('pin', $location->pin) }}" required>
                                    @error('pin')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- booking time --}}
                                <div class="form-group">
                                    <label for="b_time">Booking Time</label>
                                    <input type="text" id="b_time" name="b_time" class="form-control"
                                        value="{{ old('b_time', $location->b_time) }}">
                                </div>

                                {{-- transit time --}}
                                <div class="form-group">
                                    <label for="t_time">Transit Time</label>
                                    <input type="text" id="t_time" name="t_time" class="form-control"
                                        value="{{ old('t_time', $location->t_time) }}">
                                </div>

                                <div class="form-group mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Location
                                    </button>
                                    <a href="{{ route('admin.locations') }}" class="btn btn-secondary ml-2">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
