<tr id="code-{{$user->user_id}}">
    <td>
        <div class="checkbox checkbox-primary checkbox-circle">
        <input id="checkbox-{{$user->user_id}}" type="checkbox" class="table-checkbox" value="{{$user->user_id}}">
        <label for="checkbox-{{$user->user_id}}"></label>
        </div>
    </td>
    <td>
        <span class="editable-{{$user->user_id}}">{{$user->firstname}} {{$user->lastname}}</span>
    </td>
    <td>
        <span class="editable-{{$user->user_id}}">{{$user->username}}</span>
    </td>
    <td>
        <span class="editable-{{$user->user_id}}">{{$user->usertype->user_type_name}}</span>
    </td>
    <td>
        <span class="editable-{{$user->user_id}}">{{ $user->businessunit->bname }}</span>
    </td>
    <td>
        {{$user->created_at->format('F d, Y')}}
    </td>
    <td>
{{--        {{ $name }}--}}
    </td>
    <td>
        {{$user->updated_at->format('F d, Y')}}
    </td>
    <td>
{{--        {{ $name }}--}}
    </td>
    <td class="actions">

        <a href="{{ route("users.show", $user->user_id) }}" class="on-default open-modal" title="edit" data-id="{{ $user->user_id }}"><i class="fa fa-pencil"></i></a>
        <a href="{{ route("users.destroy", $user->user_id) }}" class="on-default remove-row" title="trash" onclick="$.Notification.notify('white','top left', '', 'Successfully moved to trash!')"><i class="fa fa-trash"></i></a>

    </td>
</tr>