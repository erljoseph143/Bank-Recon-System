<tr id="code-{{ $view->id }}">
    <td><span class="editable-cvdate-{{ $view->id }}">{{ $view->cv_date->format('F d, Y') }}</span></td>
    <td><span class="editable-checkno-{{ $view->id }}">{{ $view->check_no }}</span></td>
    <td><span class="editable-checkamount-{{ $view->id }}">{{ $view->check_amount }}</span></td>
    <td><span class="editable-labelmatch-{{ $view->id }}">{{ $view->label_match }}</span></td>
    <td class="actions">
        <a data-url="{{ url('admin/disbursements') }}" href="#edit" class="on-default open-modal" title="edit" data-id="{{ $view->id }}"><i class="fa fa-pencil"></i></a>
        <a data-url="{{ url("admin/disbursements") }}" href="#trash" class="on-default remove-row" title="move to trash" data-id="{{ $view->id }}" onclick="$.Notification.notify('white','top left', '', 'Successfully moved to trash!')"><i class="fa fa-trash"></i></a>
    </td>
</tr>