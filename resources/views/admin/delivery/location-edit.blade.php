@extends('admin.layouts.master')

@section('title', 'Edit Delivery Location')

@section('content')
<section class="section">
    <div class="section-header d-flex justify-content-between align-items-center">
        <h1>Edit Delivery Location</h1>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.locations.update', $location->id) }}" method="POST">
                    @csrf

                    {{-- Pincode --}}
                    <div class="form-group">
                        <label for="pin">Pincode <span class="text-danger">*</span></label>
                        <input type="text" id="pin" name="pin"
                               class="form-control @error('pin') is-invalid @enderror"
                               value="{{ old('pin', $location->pin) }}" required>
                        @error('pin')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Sectors --}}
                    <div id="sectors-wrapper">
                        @php
                            $sectors = old('sectors', json_decode($location->sectors, true) ?? [['name'=>$location->sector,'delivery_time'=>$location->b_time,'slots'=>json_decode($location->t_time, true) ?? []]]);
                        @endphp

                        @foreach($sectors as $index => $sector)
                        <div class="sector-block border p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5>Sector</h5>
                                <button type="button" class="btn btn-danger btn-sm remove-sector">Remove Sector</button>
                            </div>

                            <div class="form-group">
                                <label>Sector Name</label>
                                <input type="text" class="form-control" name="sectors[{{ $index }}][name]" value="{{ $sector['name'] ?? '' }}" required>
                            </div>

                            <div class="form-group">
                                <label>Delivery Taken Time</label>
                                <input type="text" class="form-control" name="sectors[{{ $index }}][delivery_time]" value="{{ $sector['delivery_time'] ?? '' }}">
                            </div>

                            <div class="form-group">
                                <label>Time Slots</label>
                                <div class="time-slots">
                                    @if(!empty($sector['slots']))
                                        @foreach($sector['slots'] as $slot)
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" name="sectors[{{ $index }}][slots][]" value="{{ $slot }}" placeholder="e.g. 9:00pm-10:00pm">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-danger remove-slot">-</button>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" name="sectors[{{ $index }}][slots][]" placeholder="e.g. 9:00pm-10:00pm">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-success add-slot">+</button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-sm btn-success mt-2 add-slot">+ Add Slot</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    {{-- Add Sector Button --}}
                    <div class="text-center mb-3">
                        <button type="button" class="btn btn-info" id="add-sector">+ Add Sector</button>
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Location</button>
                        <a href="{{ route('admin.locations') }}" class="btn btn-secondary ml-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    let sectorIndex = {{ count($sectors) - 1 }};

    // Add new sector
    document.getElementById('add-sector').addEventListener('click', function() {
        sectorIndex++;
        let sectorHTML = `
        <div class="sector-block border p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5>Sector</h5>
                <button type="button" class="btn btn-danger btn-sm remove-sector">Remove Sector</button>
            </div>
            <div class="form-group">
                <label>Sector Name</label>
                <input type="text" class="form-control" name="sectors[${sectorIndex}][name]" required>
            </div>
            <div class="form-group">
                <label>Delivery Taken Time</label>
                <input type="text" class="form-control" name="sectors[${sectorIndex}][delivery_time]">
            </div>
            <div class="form-group">
                <label>Time Slots</label>
                <div class="time-slots">
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" name="sectors[${sectorIndex}][slots][]" placeholder="e.g. 9:00pm-10:00pm">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-success add-slot">+</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
        document.getElementById('sectors-wrapper').insertAdjacentHTML('beforeend', sectorHTML);
    });

    // Handle dynamic add/remove slots & sectors
    document.addEventListener('click', function(e) {
        if(e.target.classList.contains('add-slot')){
            let slotsDiv = e.target.closest('.time-slots');
            let inputGroup = document.createElement('div');
            inputGroup.classList.add('input-group','mb-2');
            inputGroup.innerHTML = `
                <input type="text" class="form-control" name="${slotsDiv.closest('.sector-block').querySelector('input[name^="sectors"]').name.replace('[name]','[slots][]')}" placeholder="e.g. 10:00pm-11:00pm">
                <div class="input-group-append">
                    <button type="button" class="btn btn-danger remove-slot">-</button>
                </div>`;
            slotsDiv.appendChild(inputGroup);
        }

        if(e.target.classList.contains('remove-slot')){
            e.target.closest('.input-group').remove();
        }

        if(e.target.classList.contains('remove-sector')){
            e.target.closest('.sector-block').remove();
        }
    });
</script>
@endpush
