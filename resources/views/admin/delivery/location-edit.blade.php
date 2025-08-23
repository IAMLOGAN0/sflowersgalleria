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
                                    <input type="text" id="sector" name="sector"
                                           class="form-control @error('sector') is-invalid @enderror"
                                           value="{{ old('sector', $location->sector) }}" required>
                                    @error('sector')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- pin --}}
                                <div class="form-group">
                                    <label for="pin">Pincode <span class="text-danger">*</span></label>
                                    <input type="text" id="pin" name="pin"
                                           class="form-control @error('pin') is-invalid @enderror"
                                           value="{{ old('pin', $location->pin) }}" required>
                                    @error('pin')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- booking time --}}
                                <div class="form-group">
                                    <label for="b_time">Delivery Taken Time</label>
                                    <input type="text" id="b_time" name="b_time" class="form-control"
                                           value="{{ old('b_time', $location->b_time) }}">
                                </div>

                                {{-- time slots --}}
                                <div class="form-group">
                                    <label>Time Slots</label>
                                    <div id="time-slots">
                                        @php
                                            $timeSlots = old('t_time', json_decode($location->t_time, true) ?? []);
                                        @endphp

                                        @if(!empty($timeSlots))
                                            @foreach($timeSlots as $slot)
                                                <div class="input-group mb-2">
                                                    <input type="text" class="form-control" name="t_time[]" value="{{ $slot }}" placeholder="e.g. 9:00pm-10:00pm">
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-danger remove-slot">-</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="input-group mb-2">
                                                <input type="text" class="form-control" name="t_time[]" placeholder="e.g. 9:00pm-10:00pm">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-success add-slot">+</button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <button type="button" class="btn btn-sm btn-success mt-2 add-slot">+ Add Slot</button>
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
