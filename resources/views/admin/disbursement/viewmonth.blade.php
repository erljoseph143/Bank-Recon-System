@extends('admin.layouts.main')

@section('crumb')
    <li class='breadcrumb-item'><a href='{{ route('adminhome') }}'>Dashboard</a></li>
    <li class='breadcrumb-item active'><a href="{{ route('admindisburse') }}">Disbursements</a></li>
    <li class='breadcrumb-item active'><a href="{{ route('disburselistusers',$id) }}">Users uploaded</a></li>
    <li class='breadcrumb-item active'><a href="{{ route('disburselistaccounts', [$id, $userid]) }}">Bank Accounts</a></li>
    <li class='breadcrumb-item active'><a href="{{ route('disbursemonth', [$id, $userid, $account, $code]) }}">Disbursement month list</a></li>
    <li class='breadcrumb-item active'>{{ $pagetitle }}</li>
@endsection

@section('table-nav')
    <button onclick="location.href='{{ route('disburselists', [$id, $userid, $account, $code, $year, $month, 'p'=>'all']) }}';" type="button" class="btn @if($template == 'all') btn-primary @else btn-default @endif btn-custom waves-effect w-md waves-light m-b-5">Active &#40;<span class="all-count">{{ $countall }}</span>&#41;</button>
    <button onclick="location.href='{{ route('disburselists', [$id, $userid, $account, $code, $year, $month, 'p'=>'trash']) }}';" type="button" class="btn @if($template == 'trash') btn-primary @else btn-default @endif btn-custom waves-effect w-md waves-light m-b-5">Trash &#40;<span class="trash-count">{{ $counttrash }}</span>&#41;</button>
@endsection

@section('badge')
    <span class="badge label-table badge-primary">{{ $newdate }}!</span> <span class="badge badge-success"> BU : {{ $bu->bname }} </span> <span class="badge badge-purple"> Uploader : {{ $uploader->firstname . ' ' . $uploader->lastname }}</span> <span class="badge badge-danger"> BU {{ $accountdata->bank . '-'.$accountdata->accountno.'-'.$accountdata->accountname }}</span>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="portlet">
            <div class="portlet-heading portlet-default">
                <h3 class="portlet-title">Lists of {{ $doctitle }} @yield('badge')</h3>
                <div class="portlet-widgets">
                    @yield('top-buttons')
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-collapse collapse show">
                <div class="portlet-body">
                    @yield('table-nav')
                    <table id="disburse" data-action="{{ route('listdisburseajax',['code' => $code, 'userid' => $userid, 'bu' => $id, 'year' => $year, 'month' => $month, 'page' => $template]) }}" class="table m-b-0 table-bordered toggle-arrow-tiny checks" data-limit-navigation="3">
                        <thead>
                        <tr>
                            <th data-toggle="true"> CV Date </th>
                            <th data-hide="phone"> Check # </th>
                            <th data-hide="phone"> Amount </th>
                            <th data-hide="phone"> Label Match </th>
                            <th data-sort-ignore="true"> Action </th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/minton/plugins/datatables/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/minton/plugins/datatables/responsive.bootstrap4.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('admin/minton/plugins/notifyjs/dist/notify.min.js') }}"></script>
    <script src="{{ asset('admin/minton/plugins/notifications/notify-metro.js') }}"></script>
    <script src="{{ asset('admin/assets/plugins/swal/sweetalert.min.js') }}"></script>
    <script src="{{ asset('admin/minton/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/minton/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/disbursement.js') }}"></script>
@endpush