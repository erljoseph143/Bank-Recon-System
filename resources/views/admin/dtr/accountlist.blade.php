@extends('admin.layouts.table')

@section('crumb')
    <li class='breadcrumb-item'><a href='{{ route('adminhome') }}'>Dashboard</a></li>
    <li class='breadcrumb-item active'><a href="{{ route('dtr.index') }}">Dtr</a></li>
    <li class='breadcrumb-item active'>{{ $crumbtitle }}</li>
@endsection

@section('mode')
    {{ $mode }}
@endsection

@section('badge')
@endsection

@section('subtitle')
    <p class="text-muted font-10 m-b-10 inline">Accounts under {{ $bu->bname }} business unit</p>
@endsection

@section('content')
    <table id="account" class="table m-b-0 table-bordered toggle-arrow-tiny" data-page-size="8" data-limit-navigation="3">
        <thead>
        <tr>
            <th data-toggle="true"> Bank account name </th>
            <th data-hide="phone" data-sort-ignore="true"> Action </th>
        </tr>
        </thead>
        <tbody>
        @foreach($accounts as $key => $acc)
            <tr>
                <td>{{ $acc->bank . '-' . $acc->accountno . '-' . $acc->accountname }}</td>
                <td class="actions">
                    <a href="{{ route('admindtrmonths',[$id, $acc->id, $acc->bank_account_no]) }}" class="on-default view-checks" title="view"><i class="fa fa-television font-18"></i></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
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
            $('#account').DataTable();
        });
    </script>
@endpush