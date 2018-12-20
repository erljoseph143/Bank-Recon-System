@extends('admin.layouts.table')

@section('crumb')

    <li class='breadcrumb-item'><a href='home'>Dashboard</a></li><li class='breadcrumb-item active'>{{ $pagetitle }}</li>

@endsection

@section('top-buttons')

    @if($template == "trash")

        <a data-url="{{ route("adminselected") }}" data-page="bankaccounts" href="#restoreselected" class="selected-row" title="restore selected" onclick="$.Notification.notify('white','top left', '', 'Successfully restored!')"><i class="ion-reply"></i></a>

        <span class="divider"></span>

        <a data-url="{{ url("admin/users/delete-selected") }}" href="#deleteallselected" class="selected-row" title="delete permanently selected"><i class="ion-trash-b"></i></a>
    @else
        <a href="#add" id="add" page-title="addbankaccount" data-page="bankaccounts" data-url="{{ route('users.create') }}" data-target="#modalTable" data-toggle="modal"><i class="ion-plus"></i></a>

        <span class="divider"></span>

        <a data-url="{{ url("admin/users/edit-selected") }}" href="#editallselected" class="selected-row"><i class="ion-compose"></i></a>

        <span class="divider"></span>

        <a data-url="{{ route("adminselected") }}" href="#trashselected" class="selected-row"><i class="ion-trash-b"></i></a>
    @endif

@endsection

@section('table-nav')
    <button onclick="location.href='{{ route('users.index',['p'=>'all']) }}';" type="button" class="btn @if($template == 'all') btn-primary @else btn-default @endif btn-custom waves-effect w-md waves-light m-b-5">Active &#40;<span class="all-count">{{ $countall }}</span>&#41;</button>
    <button onclick="location.href='{{ route('users.index',['p'=>'trash']) }}';" type="button" class="btn @if($template == 'trash') btn-primary @else btn-default @endif btn-custom waves-effect w-md waves-light m-b-5">Trash &#40;<span class="trash-count">{{ $counttrash }}</span>&#41;</button>

@endsection

@section('content')
    <table id="demo-foo-filtering" class="table m-b-0 table-bordered toggle-arrow-tiny" data-page-size="15" data-limit-navigation="3">
        <thead>
        <tr>
            <th data-hide="phone" data-sort-ignore="true">
                <div class="checkbox checkbox-primary checkbox-circle">
                    <input id="checkbox-all" type="checkbox">
                    <label for="checkbox-all"></label>
                </div>
            </th>
            <th data-toggle="true"> Name </th>
            <th data-hide="phone"> Username </th>
            <th data-hide="phone"> Privilege </th>
            <th data-hide="phone"> Business Unit </th>
            <th data-hide="all"> Date Added </th>
            <th data-hide="all"> Added By </th>
            <th data-hide="all"> Date Modified </th>
            <th data-hide="all"> Modified By </th>
            <th data-hide="phone" data-sort-ignore="true"> Action </th>
        </tr>
        </thead>
        <div class="form-inline m-b-20">
            <div class="col-md-6 text-xs-center">
                <div class="form-group">
                    <label class="control-label m-r-5">BU</label>
                    <select id="" class="demo-foo-filter-status form-control input-sm">
                        <option value="">Show all</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6 text-center text-right">
                <div class="form-group float-right">
                    <input id="demo-foo-search" type="text" placeholder="Search" class="form-control" autocomplete="on">
                </div>
            </div>
            {{--</div>--}}
        </div>
        <tbody>

        @foreach($users as $user)

            <tr id="code-{{ $user->user_id }}">
                <td>
                    <div class="checkbox checkbox-primary checkbox-circle">
                        <input id="checkbox-{{ $user->user_id }}" type="checkbox" class="table-checkbox" value="{{ $user->user_id }}">
                        <label for="checkbox-{{ $user->user_id }}"></label>
                    </div>
                </td>
                <td><span class="editable-name-{{ $user->user_id }}">{{ $user->firstname . ' ' . $user->lastname }}</span></td>
                <td><span class="editable-username-{{ $user->user_id }}">{{ $user->username }}</span></td>
                <td><span class="editable-privilege-{{ $user->user_id }}">{{ $user->usertype->user_type_name }}</span></td>
                <td>
                    <span class="editable-businessunit-{{ $user->user_id }}">
                    @if(empty($user->bunitid))
                            NONE
                        @elseif($user->bunitid == '10000000')
                            NONE
                        @elseif($user->businessunit->bname == '')
                            NOT EXIST
                        @else
                            {{ $user->businessunit->bname }}
                        @endif
                    </span>
                </td>
                <td>

                    @if($user->created_at == null)

                        {{ 'NULL' }}

                    @elseif($user->created_at->year < 1)

                    @else
                        {{ $user->created_at->format('F d, Y') }}
                    @endif
                </td>
                <td>
                    @if(empty($user->added_by))

                    @else
                        @if(!is_null($user->user1))
                            {{ $user->user1->firstname . ' ' . $user->user1->lastname }}
                        @endif

                    @endif
                </td>
                <td>
                    @if($user->updated_at != null)
                        @if($user->updated_at->year < 1)

                        @else
                            {{ $user->updated_at->format('F d, Y') }}
                        @endif
                    @endif
                </td>
                <td>
                    <span class="editable-update-{{ $user->user_id }}">
                    @if(!empty($user->modified_by))
                            @if(!is_null($user->user2))
                                {{ $user->user2->firstname . ' ' . $user->user2->lastname }}
                            @endif
                        @endif
                    </span>
                </td>
                <td class="actions">

                    @if($template == 'trash')

                        <a href="{{ route("users.show", $user->user_id) }}" class="on-default open-modal" title="restore" onclick="$.Notification.notify('white','top left', '', 'Successfully restored!')"><i class="fa fa-mail-reply"></i></a>

                        <a href="{{ route("users.destroy", $user->user_id) }}" class="on-default remove-row" title="delete" class="remove-row"><i class="fa fa-trash-o"></i></a>

                    @else

                        <a href="{{ route("users.show", $user->user_id) }}" class="on-default open-modal" title="edit" data-id="{{ $user->user_id }}" data-target="#modalTable" data-toggle="modal"><i class="fa fa-pencil"></i></a>

                        <a href="{{ route("users.destroy", $user->user_id) }}" class="on-default remove-row" title="trash" onclick="$.Notification.notify('white','top left', '', 'Successfully moved to trash!')"><i class="fa fa-trash"></i></a>

                        <a data-url="{{ route("adminresetpass", ['id' => $user->user_id]) }}" href="#reset" class="on-default reset-password" title="reset password"><i class="fa fa-refresh fa-spin"></i></a>

                    @endif

                </td>
            </tr>

        @endforeach
        </tbody>
        <tfoot>
        <tr class="active">
            <td colspan="6">
                <div class="text-right">
                    <ul class="pagination pagination-split justify-content-end footable-pagination m-t-10 m-b-0"></ul>
                </div>
            </td>
        </tr>
        </tfoot>
    </table>

@endsection

@section('modal')

    @include('admin.user.modal')

@endsection

@push('styles')

    <!--Footable-->
    <link href="{{ asset('admin/minton/plugins/footable/css/footable.core.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/assets/css/main.css') }}" rel="stylesheet">

@endpush

@push('scripts')
    <!--FooTable-->
    <script src="{{ asset('admin/minton/plugins/footable/js/footable.all.min.js') }}"></script>
    <!--FooTable Example-->
    <script src="{{ asset('admin/minton/assets/pages/jquery.footable.js') }}"></script>
    <script src="{{ asset('admin/assets/js/user.js') }}"></script>
@endpush