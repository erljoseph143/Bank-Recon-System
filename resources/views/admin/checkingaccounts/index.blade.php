@extends('admin.layouts.table')

@section('crumb')

    <li class='breadcrumb-item'><a href='home'>Dashboard</a></li><li class='breadcrumb-item active'>{{ $pagetitle }}</li>

@endsection

@section('mode')

    {{ $mode }}

@endsection

@section('content')

    <table id="demo-foo-filtering" class="table m-b-0 table-bordered toggle-arrow-tiny" data-page-size="8" data-limit-navigation="3">
        <thead>
        <tr>
            <th data-toggle="true"> Business Unit </th>
            <th data-hide="phone"> Number of users uploaded checking accounts </th>
            <th data-hide="phone" data-sort-ignore="true"> Action </th>
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
                    <input id="demo-foo-search" type="text" placeholder="Search" class="form-control" autocomplete="on">
                </div>
            </div>
            {{--</div>--}}
        </div>
        <tbody>

        @foreach($checks as $key => $check)
            <tr>
                <td>{{ $check->bname }}</td>
                <td>{{ $users_no[$key] }}</td>
                <td class="actions">

                    <a data-url="{{ url("admin/users") }}" href="{{ url('admin/checking-accounts/bu/'.$check->unitid) }}" class="on-default view-checks" title="view" data-id=""><i class="fa fa-eye"></i></a>

                </td>
            </tr>
        @endforeach

        </tbody>
        <tfoot>
        <tr class="active">
            <td colspan="6">
                <div class="text-right">
                    <ul class="pagination pagination-split justify-content-end footable-pagination m-t-10 m-b-0"></ul>
                </div>
            </td>
        </tr>
        </tfoot>
    </table>

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
    {{--<script src="{{ asset('admin/assets/js/checkingaccounts.js') }}"></script>--}}
@endpush