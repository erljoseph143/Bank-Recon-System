@foreach($results as $result)

    <option value="{{ $result->bank_id }}">{{ $result->result }}</option>
    
@endforeach