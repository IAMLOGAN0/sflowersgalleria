@extends('admin.layouts.master')

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
                            <h4>Create Sector</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.locations.store') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label>Sector</label>
                                    <input type="text" class="form-control" name="sector" value="{{ old('sector') }}">
                                </div>

                                <div class="form-group">
                                    <label>Pin Code</label>
                                    <input type="text" class="form-control" name="pin" value="{{ old('pin') }}">
                                </div>

                                <div class="form-group">
                                    <label>Booking Time</label>
                                    <input type="text" class="form-control" name="b_time" value="{{ old('b_time') }}">
                                </div>

                                <div class="form-group">
                                    <label>Time Slot</label>
                                    <input type="text" class="form-control" name="t_time" value="{{ old('t_time') }}">
                                </div>

                                <button type="submit" class="btn btn-primary">Create</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
