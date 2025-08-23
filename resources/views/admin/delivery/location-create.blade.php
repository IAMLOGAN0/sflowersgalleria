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
                                    <label>Time Slots</label>
                                    <div id="time-slots">
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" name="t_time[]" placeholder="e.g. 9:00pm-10:00pm">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-success add-slot">+</button>
                                            </div>
                                        </div>
                                    </div>
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
@push('scripts')
<script>
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-slot')) {
            let slotDiv = document.createElement('div');
            slotDiv.classList.add('input-group', 'mb-2');
            slotDiv.innerHTML = `
                <input type="text" class="form-control" name="t_time[]" placeholder="e.g. 11:00pm-12:00pm">
                <div class="input-group-append">
                    <button type="button" class="btn btn-danger remove-slot">-</button>
                </div>
            `;
            document.getElementById('time-slots').appendChild(slotDiv);
        }

        if (e.target.classList.contains('remove-slot')) {
            e.target.closest('.input-group').remove();
        }
    });
</script>
@endpush


