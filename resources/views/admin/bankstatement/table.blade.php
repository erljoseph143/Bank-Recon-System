@foreach($bus as $key => $bu)
    <tr>
        <td>{{ $bu->bname }}</td>
        <td class="actions">
            <a href="{{ route('bsaccounts',$bu->unitid) }}" class="btn waves-effect on-default view-checks" title="view"><i class="fa fa-television"></i></a>
        </td>
    </tr>
@endforeach