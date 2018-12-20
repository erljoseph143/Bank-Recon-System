@extends('admin.layouts.main')

@section('crumb')
    <li class='breadcrumb-item'><a href='{{ route('adminhome') }}'>Dashboard</a></li>
    <li class='breadcrumb-item active'>{{ $pagetitle }}</li>
@endsection

@section('mode')
    {{ $mode }}
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
                        <table id="disburse1" class="table m-b-0 table-bordered toggle-arrow-tiny" data-page-size="8" data-limit-navigation="3">
                            <thead>
                            <tr>
                                <th data-toggle="true"> Business Unit </th>
                                <th data-hide="phone" data-sort-ignore="true"> Action </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($bus as $key => $bu)
                                <tr>
                                    <td>{{ $bu->bname }}</td>
                                    <td class="actions">
                                        <a href="{{ route('disburselistusers',$bu->unitid) }}" class="on-default view-checks" title="view" data-id=""><i class="fa fa-eye"></i></a>
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
            $('#disburse1').DataTable();
        });
    </script>
@endpush