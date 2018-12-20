@foreach($results as $result)

    <option value="{{ $result->id }}">{{ $result->result }}</option>

@endforeach