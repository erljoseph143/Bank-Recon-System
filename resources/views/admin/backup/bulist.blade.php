@foreach($bus as $bu)
    <option value="{{ $bu->unitid }}">{{ $bu->bname }}</option>
@endforeach