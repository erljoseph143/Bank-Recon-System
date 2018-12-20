@extends('admin.layouts.table')

@section('crumb')
    <li class='breadcrumb-item'><a href="{{ route('adminhome') }}">Dashboard</a></li>
    <li class='breadcrumb-item active'><a href="{{ route('bank-statements.index') }}">Bank Statements</a></li>
    <li class='breadcrumb-item active'>{{ $crumbtitle }}</li>
@endsection

@section('mode')
    {{ $mode }}
@endsection

@section('badge')
@endsection

@section('subtitle')
    <p class="text-muted m-b-10 font-12">Users under {{ $bu->bname }} business unit</p>
@endsection

@section('content')
    <table id="user" class="table m-b-0 table-bordered toggle-arrow-tiny" data-page-size="8" data-limit-navigation="3">
        <thead>
        <tr>
            <th data-toggle="true"> User </th>
            <th data-hide="phone"> User Role </th>
            <th data-hide="phone" data-sort-ignore="true"> Action </th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $key => $user)
            <tr>
                <td>{{ $user->firstname . ' ' . $user->lastname }}</td>
                <td>
                    {{ $user->user_type_name }}
                    @if ($user->deleted_at != null)
                        <span class="badge badge-warning">user is inside trash</span>
                    @endif
                </td>
                <td class="actions">
                    @if ($user->deleted_at != null)
                        <span class="badge badge-warning">cannot view user must be restore</span>
                    @else
                        <a href="{{ route('bsaccounts',[$id,$user->user_id]) }}" class="on-default view-checks" title="view"><i class="fa fa-television font-18"></i></a>
                    @endif
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
            $('#user').DataTable();
        });
    </script>
@endpush