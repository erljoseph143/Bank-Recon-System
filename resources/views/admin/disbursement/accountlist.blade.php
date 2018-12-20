@extends('admin.layouts.main')

@section('crumb')
    <li class='breadcrumb-item'><a href='{{ route('adminhome') }}'>Dashboard</a></li>
    <li class='breadcrumb-item active'><a href="{{ route('admindisburse') }}">Disbursements</a></li>
    <li class='breadcrumb-item active'><a href="{{ route('disburselistusers',$id) }}">Users uploaded</a></li>
    <li class='breadcrumb-item active'>{{ $crumbtitle }}</li>
@endsection

@section('mode')
    {{ $mode }}
@endsection

@section('badge')
    <span class="badge badge-success">BU : {{ $bu->bname }}</span> <span class="badge badge-purple">Uploader : {{ $user->firstname . ' ' . $user->lastname }}</span>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="portlet">
            <div class="portlet-heading portlet-default">
                <h3 class="portlet-title">List of {{ $doctitle }} @yield('badge')</h3>
                <div class="clearfix"></div>
            </div>
            <div class="panel-collapse collapse show">
                <div class="portlet-body">
                    <table id="bankaccount-tb" class="table m-b-0 table-bordered toggle-arrow-tiny" data-page-size="8" data-limit-navigation="3">
                        <thead>
                        <tr>
                            <th data-toggle="true"> Bank account name </th>
                            <th data-hide="phone" data-sort-ignore="true"> Action </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($accounts as $key => $account)
                            <tr>
                                <td>{{ $account->bank . '-' . $account->accountno . '-' . $account->accountname }}</td>
                                <td class="actions">
                                    <a href="{{ route('disbursemonth',[$id,$userid,$account->id,$account->baccount_no]) }}" class="on-default view-checks" title="view" data-id=""><i class="fa fa-eye"></i></a>
                                </td>
                            </tr>
                        @endforeach
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
    <script src="{{ asset('admin/minton/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/minton/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function (e) {
            $('#bankaccount-tb').DataTable();
        });
    </script>
@endpush