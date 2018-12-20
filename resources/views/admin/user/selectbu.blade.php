<option value="-1">( Select Department )</option>
@foreach($ins as $in)
    <option value="{{ $in->depid }}">{{ $in->dep_name }}</option>
@endforeach