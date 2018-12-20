<option value="-1">( SELECT BUSINESS UNIT )</option>

@foreach($bus as $bu)
    <option value="{{ $bu->businessunit->unitid }}">{{ $bu->businessunit->bname }}</option>
@endforeach