@extends('admin.layouts.table')

@section('crumb')
    <li class='breadcrumb-item'><a href="{{ route('adminhome') }}">Dashboard</a></li><li class='breadcrumb-item active'>{{ $pagetitle }}</li>
@endsection

@section('mode')
    {{ $mode }}
@endsection

@section('content')
    <table id="bs" class="table m-b-0 table-bordered toggle-arrow-tiny" data-page-size="8" data-limit-navigation="3">
        <thead>
        <tr>
            <th data-toggle="true"> Business Unit </th>
            <th data-hide="phone" data-sort-ignore="true"> Action </th>
        </tr>
        </thead>
        <tbody>
            @include('admin.bankstatement.table')
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
            $('#bs').DataTable();
        });
    </script>
@endpush