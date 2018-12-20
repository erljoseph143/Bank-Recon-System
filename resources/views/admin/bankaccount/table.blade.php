@foreach($accs as $acc)
<tr>
    <td>

        <div class="checkbox checkbox-primary checkbox-circle">

            <input id="checkbox-{{ $acc->id }}" type="checkbox" class="table-checkbox" value="{{ $acc->id }}">

            <label for="checkbox-{{ $acc->id }}"></label>

        </div>

    </td>
    <td>{{ $acc->bankcode->bankno }}</td>
    <td>{{ $acc->bank }}</td>
    <td>{{ $acc->accountno }}</td>
    <td>{{ $acc->accountname }}</td>
    <td>

        @if(strtolower($acc->status) == 'active')

            <span class="editable-status-{{ $acc->id }} badge label-table badge-success">{{ $acc->status }}</span>

        @elseif(strtolower($acc->status) == 'inactive')
            <span class="editable-status-{{ $acc->id }} badge label-table badge-danger">{{ $acc->status }}</span>
        @else
            <span class="editable-status-{{ $acc->id }} badge label-table badge-danger">No Status</span>
        @endif

    </td>

    <td class="actions">
            <a data-url="{{ url("admin/bankaccounts") }}" href="#edit" class="on-default open-modal" title="edit" data-id="{{ $acc->id }}"><i class="fa fa-pencil"></i></a>

            <a data-url="{{ url("admin/bankaccounts") }}" href="#trash" class="on-default remove-row" title="move to trash" data-id="{{ $acc->id }}" onclick="$.Notification.notify('white','top left', '', 'Successfully moved to trash!')"><i class="fa fa-trash"></i></a>

    </td>
</tr>
@endforeach