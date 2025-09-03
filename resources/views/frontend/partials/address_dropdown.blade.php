@foreach($addresses as $address)
    <option value="{{ $address->id }}">{{ $address->full_address }}</option>
@endforeach
