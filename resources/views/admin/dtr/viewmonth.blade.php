@extends('admin.layouts.main')

@section('crumb')
    <li class='breadcrumb-item'><a href='{{ route('adminhome') }}'>Dashboard</a></li>
    <li class='breadcrumb-item active'><a href="{{ route('dtr.index') }}">Dtr</a></li>
    <li class='breadcrumb-item active'><a href="{{ route('admindtraccounts', [$id]) }}">Bank Accounts</a></li>
    <li class='breadcrumb-item active'><a href="{{ route('admindtrmonths', [$id, $account, $code]) }}">Dtr in a month list</a></li>
    <li class='breadcrumb-item active'>{{ $pagetitle }}</li>
@endsection

@section('table-nav')
    <button onclick="location.href='{{ route('admindtrview', [$id, $account, $code, $year, $month, 'p'=>'all']) }}';" type="button" class="btn @if($template == 'all') btn-primary @else btn-default @endif btn-custom waves-effect w-md waves-light m-b-5">Active &#40;<span class="all-count">{{ $countall }}</span>&#41;</button>
    <button onclick="location.href='{{ route('admindtrview', [$id, $account, $code, $year, $month, 'p'=>'trash']) }}';" type="button" class="btn @if($template == 'trash') btn-primary @else btn-default @endif btn-custom waves-effect w-md waves-light m-b-5">Trash &#40;<span class="trash-count">{{ $counttrash }}</span>&#41;</button>
@endsection

@section('badge')
    {{ $newdate }}!
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="portlet">
                <div class="portlet-heading portlet-default">
                    <h3 class="portlet-title">Lists of {{ $doctitle }} @yield('badge')</h3>
                    <p class="text-muted font-10 m-b-10 inline">Dtr under {{ $bu->bname }} with bank account details {{ $accountdata->bank . '-'.$accountdata->accountno.'-'.$accountdata->accountname }}</p>
                    <div class="portlet-widgets">
                        @yield('top-buttons')
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-collapse collapse show">
                    <div class="portlet-body">
                        @yield('table-nav')
                        <table id="bs" class="table m-b-0 table-hover table-bordered toggle-arrow-tiny checks" data-action="{{ route('admindtrviewajax', ['code' => $code, 'bu' => $id,'year' => $year, 'month' => $month, 'page' => $template]) }}">
                            <thead>
                            <tr>
                                <th data-toggle="true"> Bank Date </th>
                                <th data-hide="phone, tablet"> Check No. </th>
                                <th data-hide="phone"> Description </th>
                                <th data-hide="phone, tablet"> Bank Amount </th>
                                <th data-hide="all"> Bank Balance </th>
                                <th data-hide="all"> Action </th>
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
    <script src="{{ asset('admin/assets/js/dtr.js') }}"></script>
@endpush