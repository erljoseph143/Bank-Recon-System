@foreach($codes as $code)
    <tr id="code-{{ $code->id }}">
        <td>
            <div class="checkbox checkbox-primary checkbox-circle">
                <input id="checkbox-{{ $code->id }}" type="checkbox" class="table-checkbox" value="{{ $code->id }}">
                <label for="checkbox-{{ $code->id }}"></label>
            </div>
        </td>
        <td>
            <span class="editable-{{ $code->id }}">
                {{ $code->bankno }}
            </span>
        </td>
        <td>
            @if(empty($code->added_by))

            @else
                @if(is_null($code->user1))
                    <span class="badge badge-danger">user deleted</span>
                @else
                    {{ $code->user1->firstname . ' ' . $code->user1->lastname }}
                @endif
            @endif
        </td>
        <td>
            @if($code->created_at->year < 1)

            @else
                {{ $code->created_at->format('F d, Y') }}
            @endif
        </td>
        <td>
            @if(empty($code->modified_by))

            @else
                @if(is_null($code->user2))
                    <span class="badge badge-danger">user deleted</span>
                @else
                    {{ $code->user2->firstname . ' ' . $code->user2->lastname }}
                @endif
            @endif
        </td>
        <td>
            @if($code->updated_at != null)
                @if($code->updated_at->year < 1)

                @else
                    {{ $code->updated_at->format('F d, Y') }}
                @endif
            @else
            @endif
        </td>
        <td class="actions">
            @if($template == 'trash')
                <a href="{{ route("bankcodes.update",$code->id) }}" class="on-default open-modal" title="restore" onclick="$.Notification.notify('white','top left', '', 'Successfully restored!')"><i class="fa fa-mail-reply"></i></a>
                <a href="{{ route("bankcodes.destroy", $code->id) }}" class="on-default remove-row" title="delete" class="remove-row"><i class="fa fa-trash-o"></i></a>
            @else
                <a href="{{ route("bankcodes.edit",$code->id) }}" class="on-default open-modal" title="edit"><i class="fa fa-pencil" data-toggle="modal" data-target="#modalTable"></i></a>
                <a href="{{ route("bankcodes.destroy",$code->id) }}" class="on-default remove-row" title="trash" onclick="$.Notification.notify('white','top left', '', 'Successfully moved to trash!')"><i class="fa fa-trash"></i></a>
            @endif
        </td>
    </tr>
@endforeach