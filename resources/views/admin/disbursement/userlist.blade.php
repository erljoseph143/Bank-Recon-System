@extends('admin.layouts.main')

@section('crumb')
    <li class='breadcrumb-item'><a href='{{ route('adminhome') }}'>Dashboard</a></li>
    <li class='breadcrumb-item active'><a href="{{ route('admindisburse') }}">Disbursements</a></li>
    <li class='breadcrumb-item active'>{{ $crumbtitle }}</li>
@endsection

@section('mode')
    {{ $mode }}
@endsection

@section('badge')
    <span class="badge badge-success">BU : {{ $bu->bname }}</span>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="portlet">
            <div class="portlet-heading portlet-default">
                <h3 class="portlet-title">Lists of {{ $doctitle }} @yield('badge')</h3>
                <div class="clearfix"></div>
            </div>
            <div class="panel-collapse collapse show">
                <div class="portlet-body">
                    <table id="userlist" class="table table-bordered m-b-0 toggle-arrow-tiny" data-page-size="8" data-limit-navigation="3">
                        <thead>
                        <tr>
                            <th data-toggle="true"> User name </th>
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
                                        <a href="{{ route('disburselistaccounts',[$id,$user->user_id]) }}" class="on-default view-checks" title="view" data-id=""><i class="fa fa-eye"></i></a>
                                    @endif
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
            $('#userlist').DataTable();
        });
    </script>
@endpush