@extends('admin.layouts.table')

@section('crumb')

    <li class='breadcrumb-item'><a href='home'>Dashboard</a></li><li class='breadcrumb-item active'>{{ $pagetitle }}</li>

@endsection

@section('top-buttons')

    @if($template == "trash")

        <a data-url="{{ url("admin/users/delete-selected") }}" data-page="bankaccounts" href="#restoreallselected" class="selected-row" title="restore selected" onclick="$.Notification.notify('white','top left', '', 'Successfully restored!')"><i class="ion-reply"></i></a>

        <span class="divider"></span>

        <a data-url="{{ url("admin/users/delete-selected") }}" href="#deleteallselected" class="selected-row" title="delete permanently selected"><i class="ion-trash-b"></i></a>
    @else
        <a href="#add" id="add" page-title="addbankaccount" data-page="bankaccounts" data-url="{{ url('admin/usertypes/get') }}" data-target="#modalTable" data-toggle="modal"><i class="ion-plus"></i></a>

        <span class="divider"></span>

        <a data-url="{{ url("admin/users/edit-selected") }}" href="#editallselected" class="selected-row"><i class="ion-compose"></i></a>

        <span class="divider"></span>

        <a data-url="{{ url("admin/users/delete-selected") }}" href="#trashallselected" class="selected-row"><i class="ion-trash-b"></i></a>
    @endif

@endsection

@section('table-nav')

    <button onclick="location.href='{{ url("admin/usertypes") }}';" type="button" class="btn @if($template == 'all') btn-primary @else btn-default @endif btn-custom waves-effect w-md waves-light m-b-5">Active &#40;<span class="all-count">{{ $countall }}</span>&#41;</button>
    <button onclick="location.href='{{ url("admin/usertypes/trash") }}';" type="button" class="btn @if($template == 'trash') btn-primary @else btn-default @endif btn-custom waves-effect w-md waves-light m-b-5">Trash &#40;<span class="trash-count">{{ $counttrash }}</span>&#41;</button>

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
            <th data-toggle="true"> User Type Name </th>
            <th data-hide="phone"> Date Created </th>
            <th data-hide="phone"> Created By </th>
            <th data-hide="phone"> Date Updated </th>
            <th data-hide="all"> Updated By </th>
            <th data-hide="phone" data-sort-ignore="true"> Action </th>
        </tr>
        </thead>
        <div class="form-inline m-b-20">
            {{--<div class="row">--}}
            <div class="col-md-6 text-xs-center">
                <div class="form-group">
                    <label class="control-label m-r-5">BU</label>
                    <select id="" class="demo-foo-filter-status form-control input-sm">
                        <option value="">Show all</option>
                        {{--@foreach($bunits as $bunit)--}}
                        {{--@if(!empty($bunit->bunitid) OR $bunit->bunitid == '10000000')--}}
                        {{--<option value="{{ strtolower($bunit->businessunit->bname) }}">{{ $bunit->businessunit->bname }}</option>--}}
                        {{--@endif--}}
                        {{--@endforeach--}}
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

        @foreach($usertypes as $type)

            <tr id="code-{{ $type->user_type_id }}">
                <td>
                    <div class="checkbox checkbox-primary checkbox-circle">
                        <input id="checkbox-{{ $type->user_type_id }}" type="checkbox" class="table-checkbox" value="{{ $type->user_type_id }}">
                        <label for="checkbox-{{ $type->user_type_id }}"></label>
                    </div>
                </td>
                <td><span class="editable-usertype-{{ $type->user_type_id }}">{{ $type->user_type_name }}</span></td>
                <td>
                    @if(is_null($type->created_at))

                    @else
                        <span class="editable-createdat-{{ $type->user_type_id }}">{{ $type->created_at->format('F d, Y') }}</span>
                    @endif
                </td>
                <td>
                    @if(empty($type->created_by))

                    @else
                        @if(is_null($type->user1))
                            <span class="badge badge-danger">user deleted</span>
                        @else
                            <span class="editable-createdby-{{ $type->user_type_id }}">{{ $type->user1->firstname . ' ' . $type->user1->lastname }}</span>
                        @endif
                    @endif

                </td>
                <td>
                    @if(is_null($type->updated_at))
                    @else
                        <span class="editable-updatedat-{{ $type->user_type_id }}">{{ $type->updated_at->format('F d, Y') }}</span>
                    @endif
                </td>
                <td>
                    @if(empty($type->updated_by))

                    @else
                        @if(is_null($type->user2))
                            <span class="badge badge-danger">user deleted</span>
                        @else
                            <span class="editable-updatedby-{{ $type->user_type_id }}">{{ $type->user2->firstname . ' ' . $type->user2->lastname }}</span>
                        @endif
                    @endif

                </td>
                <td class="actions">

                    @if($template == 'trash')

                        <a data-url="{{ url("admin/usertypes") }}" href="#restore" class="on-default open-modal" title="restore" data-id="{{ $type->user_type_id }}" onclick="$.Notification.notify('white','top left', '', 'Successfully restored!')"><i class="fa fa-mail-reply"></i></a>

                        <a data-url="{{ url("admin/usertypes") }}" href="#delete" class="on-default remove-row" title="permanently delete" data-id="{{ $type->user_type_id }}" class="remove-row"><i class="fa fa-trash-o"></i></a>

                    @else

                        <a data-url="{{ url("admin/usertypes") }}" href="#edit" class="on-default open-modal" title="edit" data-id="{{ $type->user_type_id }}"><i class="fa fa-pencil" data-target="#modalTable" data-toggle="modal"></i></a>

                        <a data-url="{{ url("admin/usertypes") }}" href="#trash" class="on-default remove-row" title="move to trash" data-id="{{ $type->user_type_id }}" onclick="$.Notification.notify('white','top left', '', 'Successfully moved to trash!')"><i class="fa fa-trash"></i></a>

                    @endif

                </td>
            </tr>

        @endforeach
        </tbody>
        <tfoot>
        <tr class="active">
            <td colspan="7">
                <div class="text-right">
                    <ul class="pagination pagination-split justify-content-end footable-pagination m-t-10 m-b-0"></ul>
                </div>
            </td>
        </tr>
        </tfoot>
    </table>

@endsection

@section('modal')

    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalTable">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span>&times;</span></button>
                    <h4 class="modal-title" id="modal-title">Generated title</h4>
                </div>
                <div class="modal-body">
                    <div class="row" id="modal-content">
                        <form id="form-1437" name="" class="" novalidate="" style="display: inherit; width: 100%;">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="usertype" class="control-label">User Type</label>
                                    <input type="text" class="form-control" id="usertype" placeholder="Input User Type">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button data-url="{{ url("admin/usertypes") }}" id="save" type="button" class="btn btn-primary waves-effect waves-light">Save</button>
                    <input type="hidden" id="code" name="code" value="0">
                </div>
            </div>
        </div>
    </div>

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

    <script src="{{ asset('admin/assets/js/usertype.js') }}"></script>
@endpush