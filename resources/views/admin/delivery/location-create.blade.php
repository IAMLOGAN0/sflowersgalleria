@extends('admin.layouts.master')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Create Pincode with Sectors</h1>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.locations.store') }}" method="POST">
                    @csrf

                    {{-- Pincode --}}
                    <div class="form-group">
                        <label>Pincode</label>
                        <input type="text" class="form-control" name="pin" value="{{ old('pin') }}">
                    </div>

                    {{-- Sectors --}}
                    <div id="sectors-wrapper">
                        <div class="sector-block border p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5>Sector</h5>
                                <button type="button" class="btn btn-danger btn-sm remove-sector">Remove Sector</button>
                            </div>

                            <div class="form-group">
                                <label>Sector Name</label>
                                <input type="text" class="form-control" name="sectors[0][name]">
                            </div>

                            <div class="form-group">
                                <label>Delivery Taken Time</label>
                                <input type="text" class="form-control" name="sectors[0][delivery_time]">
                            </div>

                            <div class="form-group">
                                <label>Time Slots</label>
                                <div class="time-slots">
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" name="sectors[0][slots][]" placeholder="e.g. 9:00pm-10:00pm">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-success add-slot">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Add Sector Button --}}
                    <div class="text-center mb-3">
                        <button type="button" class="btn btn-info" id="add-sector">+ Add Sector</button>
                    </div>

                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    let sectorIndex = 0;

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
                <input type="text" class="form-control" name="sectors[${sectorIndex}][name]">
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

    // Handle dynamic add/remove slots & remove sector
    document.addEventListener('click', function(e) {

        // Add new time slot
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

        // Remove a time slot
        if(e.target.classList.contains('remove-slot')){
            e.target.closest('.input-group').remove();
        }

        // Remove a sector
        if(e.target.classList.contains('remove-sector')){
            e.target.closest('.sector-block').remove();
        }
    });
</script>
@endpush
