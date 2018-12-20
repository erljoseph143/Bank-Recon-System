<option value="-1">( Select Bussiness Unit )</option>
@foreach($ins as $in)
    <option value="{{ $in->unitid }}">{{ $in->bname }}</option>
@endforeach