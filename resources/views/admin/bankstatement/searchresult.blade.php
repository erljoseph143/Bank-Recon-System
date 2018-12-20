<tr id="code-{{ $searchbs->bank_id }}">
    <td><span class="editable-bankdate-{{ $searchbs->bank_id }}">{{ $searchbs->bank_date->format('F d, Y') }}</span></td>
    <td><span class="editable-desc-{{ $searchbs->bank_id }}">{{ $searchbs->description }}</span></td>
    <td><span class="editable-checkno-{{ $searchbs->bank_id }}">{{ $searchbs->bank_check_no }}</span></td>
    <td><span class="editable-bankamount-{{ $searchbs->bank_id }}">{{ $searchbs->bank_amount }}</span></td>
    <td><span class="editable-balance-{{ $searchbs->bank_id }}">{{ $searchbs->bank_balance }}</span></td>
    <td class="actions">
        <a data-url="{{ url('admin/bank-statements') }}" href="#edit" class="on-default open-modal" title="edit" data-id="{{ $searchbs->bank_id }}"><i class="fa fa-pencil"></i></a>
        <a data-url="{{ url("admin/bank-statements") }}" href="#trash" class="on-default remove-row" title="move to trash" data-id="{{ $searchbs->bank_id }}" onclick="$.Notification.notify('white','top left', '', 'Successfully moved to trash!')"><i class="fa fa-trash"></i></a>

    </td>
</tr>