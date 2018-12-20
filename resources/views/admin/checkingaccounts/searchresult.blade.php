<tr id="code-{{ $view->id }}">
    <td><span class="editable-date-{{ $view->id }}">{{ $view->date_posted->format('F d, Y') }}</span></td>
    <td><span class="editable-desc-{{ $view->id }}">{{ $view->transaction_desc }}</span></td>
    <td><span class="editable-checkno-{{ $view->id }}">{{ $view->check_no }}</span></td>
    <td><span class="editable-amount-{{ $view->id }}">{{ $view->trans_amount }}</span></td>
    <td><span class="editable-balance-{{ $view->id }}">{{ $view->balance }}</span></td>
    <td class="actions">
            <a data-url="{{ url('admin/checking-accounts') }}" href="#edit" class="on-default open-modal" title="edit" data-id="{{ $view->id }}"><i class="fa fa-pencil"></i></a>
            <a data-url="{{ url("admin/checking-accounts") }}" href="#trash" class="on-default remove-row" title="move to trash" data-id="{{ $view->id }}" onclick="$.Notification.notify('white','top left', '', 'Successfully moved to trash!')"><i class="fa fa-trash"></i></a>
    </td>
</tr>