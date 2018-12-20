@extends('admin.layouts.table')

@section('crumb')

    <li class='breadcrumb-item'><a href='{{ url('admin/home') }}'>Dashboard</a></li>
    <li class='breadcrumb-item active'><a href="{{ url('admin/checking-accounts') }}">Checking Accounts</a></li>
    <li class='breadcrumb-item active'><a href="{{ url('admin/checking-accounts/bu/' . $id) }}">Users uploaded</a></li>
    <li class='breadcrumb-item active'><a href="{{ url('admin/checking-accounts/bu/' . $id . '/' . $userid) }}">Bank Accounts</a></li>
    <li class='breadcrumb-item active'><a href="{{ url('admin/checking-accounts/bu/' . $id . '/' . $userid . '/' . $account . '/' . $code) }}">Checking Accounts in a month list</a></li>
    <li class='breadcrumb-item active'>{{ $pagetitle }}</li>

@endsection

@section('top-buttons')

    {{--@if($template == "trash")--}}

        {{--<a data-url="{{ url("admin/bank-statements/delete-selected") }}" data-page="bankaccounts" href="#restoreallselected" class="selected-row" title="restore selected" onclick="$.Notification.notify('white','top left', '', 'Successfully restored!')"><i class="ion-reply"></i></a>--}}

        {{--<span class="divider"></span>--}}

        {{--<a data-url="{{ url("admin/bank-statements/delete-selected") }}" href="#deleteallselected" class="selected-row" title="delete permanently selected"><i class="ion-trash-b"></i></a>--}}
    {{--@else--}}
        {{--<a data-url="{{ url("admin/bank-statements/delete-selected") }}" href="#trashallselected" class="selected-row"><i class="ion-trash-b"></i></a>--}}
    {{--@endif--}}

@endsection

@section('table-nav')

    <button onclick="location.href='{{ url('admin/checking-accounts/bu/'.$id.'/'.$userid.'/'.$account.'/'.$code.'/'.$year.'/'.$month) }}';" type="button" class="btn @if($template == 'all') btn-primary @else btn-default @endif btn-custom waves-effect w-md waves-light m-b-5">Active &#40;<span class="all-count">{{ $countall }}</span>&#41;</button>
    <button onclick="location.href='{{ url('admin/checking-accounts/bu/'.$id.'/'.$userid.'/'.$account.'/'.$code.'/'.$year.'/'.$month.'/'.'trash') }}';" type="button" class="btn @if($template == 'trash') btn-primary @else btn-default @endif btn-custom waves-effect w-md waves-light m-b-5">Trash &#40;<span class="trash-count">{{ $counttrash }}</span>&#41;</button>

@endsection

@section('badge')
    <span class="badge label-table badge-primary">{{ $newdate }}!</span> <span class="badge badge-success"> BU : {{ $bu->bname }} </span> <span class="badge badge-purple"> Uploader : {{ $uploader->firstname . ' ' . $uploader->lastname }}</span> <span class="badge badge-danger"> BU {{ $accountdata->bank . '-'.$accountdata->accountno.'-'.$accountdata->accountname }}</span>
@endsection

@section('content')

    <table id="demo-foo-filtering" class="table m-b-0 table-bordered toggle-arrow-tiny checks">
        <thead>
        <tr>
            {{--<th data-sort-ignore="true">--}}
                {{--<div class="checkbox checkbox-primary checkbox-circle">--}}

                    {{--<input id="checkbox-all" type="checkbox">--}}

                    {{--<label for="checkbox-all"></label>--}}

                {{--</div>--}}
            {{--</th>--}}
            <th data-toggle="true"> Date Posted </th>
            <th data-hide="phone"> Description </th>
            <th data-hide="phone, tablet"> Check No. </th>
            <th data-hide="phone, tablet"> Amount </th>
            <th data-hide="all"> Balance </th>
            <th data-sort-ignore="true"> Action </th>
        </tr>
        </thead>
        <div class="form-inline m-b-20">
            {{--<div class="row">--}}
            <div class="col-md-6 text-xs-center">
                <div class="form-group">
                    <label class="control-label m-r-5">Bank</label>
                    <select id="" class="demo-foo-filter-status form-control input-sm">
                        <option value="">Show all</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6 text-center text-right">
                <div class="form-group float-right">

                    <select data-url="{{ url("admin/checking-accounts/search/$id/$userid/$account/$code/$year/$month") }}" class="form-control mr-3" name="" id="filter-data">
                        <option value="-1">Filter By</option>
                        <option value="1">Check No</option>
                        <option value="2">Amount</option>
                        <option value="3">Balance</option>
                        <option value="4">Description</option>
                    </select>
                    <select data-url="{{ url("admin/checking-accounts/search/$id/$userid/$account/$code/$year/$month") }}" class="form-control select2" name="" id="search-data">
                        <option value="-1">Search me</option>
                    </select>
                    <button class="ml-3 btn btn-rounded btn-primary waves-effect waves-light" id="clearfilter">Clear Filter</button>
                    <div style="display: none" class="temp">

                    </div>

                </div>
            </div>
            {{--</div>--}}
        </div>
        <tbody>

        @foreach($views as $view)

            <tr id="code-{{ $view->id }}">
                {{--<td>--}}
                    {{--<div class="checkbox checkbox-primary checkbox-circle editable-date-{{ $view->id }}">--}}

                        {{--<input id="checkbox-{{ $view->id }}" type="checkbox" class="table-checkbox" value="{{ $view->id }}">--}}

                        {{--<label for="checkbox-{{ $view->id }}"></label>--}}

                    {{--</div>--}}
                {{--</td>--}}
                <td><span class="editable-date-{{ $view->id }}">{{ $view->date_posted->format('F d, Y') }}</span></td>
                <td><span class="editable-desc-{{ $view->id }}">{{ $view->transaction_desc }}</span></td>
                <td><span class="editable-checkno-{{ $view->id }}">{{ $view->check_no }}</span></td>
                <td><span class="editable-amount-{{ $view->id }}">{{ $view->trans_amount }}</span></td>
                <td><span class="editable-balance-{{ $view->id }}">{{ $view->balance }}</span></td>
                <td class="actions">

                    @if($template == 'trash')

                        <a data-url="{{ url("admin/checking-accounts") }}" href="#restore" class="on-default open-modal" title="restore" data-id="{{ $view->id }}" onclick="$.Notification.notify('white','top left', '', 'Successfully restored!')"><i class="fa fa-mail-reply"></i></a>
{{--                        <a data-url="{{ url("admin/checking-accounts") }}" href="#delete" class="on-default remove-row" title="permanently delete" data-id="{{ $view->id }}" class="remove-row"><i class="fa fa-trash-o"></i></a>--}}

                    @else

                        <a data-url="{{ url('admin/checking-accounts') }}" href="#edit" class="on-default open-modal" title="edit" data-id="{{ $view->id }}"><i class="fa fa-pencil"></i></a>
                        <a data-url="{{ url("admin/checking-accounts") }}" href="#trash" class="on-default remove-row" title="move to trash" data-id="{{ $view->id }}" onclick="$.Notification.notify('white','top left', '', 'Successfully moved to trash!')"><i class="fa fa-trash"></i></a>

                    @endif

                </td>
            </tr>
        @endforeach

        </tbody>
        <tfoot>
        <tr>

        </tr>
        </tfoot>
    </table>

    {{--<div class="form-group row">--}}
    {{--<label class="control-label col-sm-4">Auto Close</label>--}}
    {{--<div class="col-sm-8">--}}
    {{--<div class="input-group">--}}
    {{--<input type="text" class="form-control" placeholder="mm/dd/yyyy" id="datepicker-autoclose">--}}
    {{--<span class="input-group-addon bg-primary b-0 text-white"><i class="ion-calendar"></i></span>--}}
    {{--</div><!-- input-group -->--}}
    {{--</div>--}}
    {{--</div>--}}

@endsection

@section('modal')

    <div class="modal-demo" id="modalTable">
        <button type="button" class="close" onclick="Custombox.modal.close();">
            <span>&times;</span><span class="sr-only">Close</span>
        </button>
        <h4 class="custom-modal-title" id="modal-title">Generated title</h4>
        <div class="custom-modal-text">
            <div class="row" id="modal-content">
                <form id="form-1437" name="" class="" novalidate="" style="display: inherit; width: 100%;">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Date Posted</label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="mm/dd/yyyy" id="datePosted">
                                <span class="input-group-addon bg-primary b-0 text-white"><i class="ion-calendar"></i></span>
                            </div><!-- input-group -->
                        </div>
                        <div class="form-group">
                            <label for="desc" class="control-label">Description</label>
                            <input type="text" class="form-control" id="desc" placeholder="Input Description">
                        </div>
                        <div class="form-group">
                            <label for="checkno" class="control-label">Check No.</label>
                            <input type="text" class="form-control" id="checkno" placeholder="Input Check No.">
                        </div>
                        <div class="form-group" id="control-label">
                            <label for="bankamount" class="control-label">Amount</label>
                            <input type="text" class="form-control" id="amount" placeholder="Input Amount">
                        </div>
                        <div class="form-group">
                            <label for="balance" class="control-label">Balance</label>
                            <input type="text" class="form-control" id="balance" placeholder="Input Balace">
                        </div>
                    </div>
                </form>

            </div>
        </div>
        <div class="modal-footer">
            <button data-url="{{ url("admin/checking-accounts") }}" id="save" type="button" class="btn btn-primary waves-effect waves-light">Save</button>
            <input type="hidden" id="code" name="code" value="0">
        </div>
    </div>

@endsection

@section('pagination')
    <?php
    echo $views->links();
    ?>
@endsection

@push('styles')

    <link href="{{ asset('admin/minton/plugins/select2/select2.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/minton/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/minton/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/assets/css/main.css') }}" rel="stylesheet">

@endpush

@push('scripts')

    <script src="{{ asset('admin/minton/plugins/select2/select2.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('admin/minton/plugins/moment/moment.js') }}"></script>
    <script src="{{ asset('admin/minton/plugins/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('admin/minton/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('admin/minton/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

{{--    <script src="{{ asset('admin/minton/assets/pages/jquery.form-advanced.init.js') }}"></script>--}}

    <script src="{{ asset('admin/assets/js/methods.js') }}"></script>
    <script src="{{ asset('admin/assets/js/checkingaccounts.js') }}"></script>

@endpush