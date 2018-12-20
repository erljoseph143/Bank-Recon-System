@foreach($purposes as $purpose)
    <tr>
        <td>
            {{ $purpose->description }}
        </td>
    </tr>
@endforeach