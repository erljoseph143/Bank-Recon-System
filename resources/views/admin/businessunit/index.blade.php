@extends('admin.layouts.table')

@section('crumb')

    <li class='breadcrumb-item'><a href='{{ url('admin/home') }}'>Dashboard</a></li>
    <li class='breadcrumb-item'><a href='{{ url('admin/companies') }}'>Company</a></li>
    <li class='breadcrumb-item active'>{{ $pagetitle }}</li>

@endsection

@section('top-buttons')
    @if($template == "trash")
        <a data-url="{{ url("admin/businessunits/delete-selected") }}" href="#restoreallselected" class="selected-row" title="restore selected" onclick="$.Notification.notify('white','top left', '', 'Successfully restored!')"><i class="ion-reply"></i></a>

        <span class="divider"></span>

        <a data-url="{{ url("admin/businessunits/delete-selected") }}" href="#deleteallselected" class="selected-row" title="delete permanently selected"><i class="ion-trash-b"></i></a>
    @else

        <a href="#add" id="add" page-title="addcompany" data-page="companies" data-toggle="modal" data-target="#modalTable"><i class="ion-plus"></i></a>

        <span class="divider"></span>

        <a data-url="{{ url("admin/businessunits/edit-selected") }}" href="#editallselected" class="selected-row"><i class="ion-compose"></i></a>

        <span class="divider"></span>

        <a data-url="{{ url("admin/businessunits/delete-selected") }}" href="#trashallselected" class="selected-row"><i class="ion-trash-b"></i></a>

    @endif

@endsection

@section('table-nav')

    {{--<div class="btn-group m-b-10">--}}
    <button onclick="location.href='{{ $url }}';" type="button" class="btn @if($template == 'all') btn-primary @else btn-default @endif btn-custom waves-effect w-md waves-light m-b-5">Active &#40;<span class="all-count">{{ $countall }}</span>&#41;</button>
    <button onclick="location.href='{{ $trashurl }}';" type="button" class="btn @if($template == 'trash') btn-primary @else btn-default @endif btn-custom waves-effect w-md waves-light m-b-5">Trash &#40;<span class="trash-count">{{ $counttrash }}</span>&#41;</button>
    {{--</div>--}}

@endsection

@section('badge')
    <span class="badge label-table badge-success">BU under {{ $companies->company }}!</span>
@endsection

@section('content')
    <table id="demo-foo-filtering" class="table m-b-0 table-bordered toggle-arrow-tiny" data-page-size="8" data-limit-navigation="3">
        <thead>
        <tr>
            <th data-hide="phone" data-sort-ignore="true">
                <div class="checkbox checkbox-primary checkbox-circle">
                    <input id="checkbox-all" type="checkbox">
                    <label for="checkbox-all"></label>
                </div>
            </th>
            <th data-toggle="true"> Business unit </th>
            <th data-hide="phone, tablet"> Date Added </th>
            <th data-hide="phone, tablet"> Added By </th>
            <th data-hide="all"> Date Modified </th>
            <th data-hide="all"> Modified By </th>
            <th data-hide="phone" data-sort-ignore="true"> Action </th>
        </tr>
        </thead>
        <div class="form-inline m-b-20">
            <div class="col-md-6 text-xs-center">
                <div class="form-group">
                    <label class="control-label m-r-5">Added By</label>
                    <select id="" class="demo-foo-filter-status form-control input-sm">
                        <option value="">Show all</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6 text-center text-right">
                <div class="form-group pull-right">
                    <input id="demo-foo-search" type="text" placeholder="Search" class="form-control" autocomplete="on">
                </div>
            </div>
        </div>
        <tbody>

        @foreach($bus as $bu)
            <tr id="code-{{ $bu->unitid }}">
                <td>
                    <div class="checkbox checkbox-primary checkbox-circle">
                        <input id="checkbox-{{ $bu->unitid }}" type="checkbox" class="table-checkbox" value="{{ $bu->unitid }}">
                        <label for="checkbox-{{ $bu->unitid }}"></label>
                    </div>
                </td>
                <td><span class="editable-buname-{{ $bu->unitid }}">{{ $bu->bname }}</span></td>
                <td>
                    @if($bu->created_at != null)
                        @if($bu->created_at->year < 1)

                        @else
                            {{ $bu->created_at->format('F d, Y') }}
                        @endif
                    @else
                    @endif
                </td>
                <td>
                    @if(empty($bu->added_by))

                    @else
                        @if(is_null($bu->user1))
                            <span class="badge badge-danger">user deleted</span>
                        @else
                            {{ $bu->user1->firstname . ' ' . $bu->user1->lastname }}
                        @endif
                    @endif
                </td>
                <td>
                    <span class="editable-updatedat-{{ $bu->unitid }}">
                        @if($bu->updated_at != null)
                            @if($bu->updated_at->year < 1)

                            @else
                                {{ $bu->updated_at->format('F d, Y') }}
                            @endif
                        @else
                        @endif
                    </span>
                </td>
                <td>
                    <span class="editable-modifiedby-{{ $bu->unitid }}">
                    @if(empty($bu->modified_by))

                        @else
                            @if(is_null($bu->user2))
                                <span class="badge badge-danger">user deleted</span>
                            @else
                                {{ $bu->user2->firstname . ' ' . $bu->user2->lastname }}
                            @endif
                        @endif
                    </span>
                </td>
                <td class="actions">
                    @if($template == 'trash')
                        <a data-url="{{ url('admin/businessunits') }}" href="#restore" class="on-default open-modal" title="restore" data-id="{{ $bu->unitid }}" onclick="$.Notification.notify('white','top left', '', 'Successfully restored!')"><i class="fa fa-mail-reply"></i></a>
                        <a data-url="{{ url('admin/businessunits') }}" href="#delete" class="on-default remove-row" title="permanently delete" data-id="{{ $bu->unitid }}" class="remove-row"><i class="fa fa-trash-o"></i></a>
                    @else
                        <a data-url="{{ url('admin/businessunits') }}" href="#edit" class="on-default open-modal" title="edit" data-id="{{ $bu->unitid }}" data-toggle="modal" data-target="#modalTable"><i class="fa fa-pencil"></i></a>
                        <a data-url="{{ url('admin/businessunits') }}" href="#trash" class="on-default remove-row" title="move to trash" data-id="{{ $bu->unitid }}" onclick="$.Notification.notify('white','top left', '', 'Successfully moved to trash!')"><i class="fa fa-trash"></i></a>
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
                        <div class="col-sm-12">
                            <form id="form-1437" name="" class="form-horizontal" novalidate="">

                                <div class="form-group">
                                    <label for="businessunit" class="control-label">Businessunit</label>
                                    <input type="text" class="form-control" id="businessunit" placeholder="Input Businessunit">
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button data-url="{{ url('admin/businessunits') }}" id="save" type="button" class="btn btn-primary waves-effect waves-light">Save</button>
                    <input type="hidden" id="code" name="code" value="0">
                    <input type="hidden" id="company" name="company" value="{{ $companyid }}">
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
    <script src="{{ asset('admin/assets/js/bu.js') }}"></script>
@endpush