@foreach($trans_types as $trans_type)
<tr id="trans-type-{{ $trans_type->id }}">
    <td>{{ $trans_type->code }}</td>
    <td>{{ $trans_type->name }}</td>
    <td></td>
</tr>
@endforeach