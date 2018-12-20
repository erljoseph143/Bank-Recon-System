@extends('admin.layouts.table')

@section('crumb')

    <li class='breadcrumb-item'><a href='home'>Dashboard</a></li><li class='breadcrumb-item active'>{{ $pagetitle }}</li>

@endsection

@section('content')

    <table id="demo-foo-filtering" class="table m-b-0 table-bordered toggle-arrow-tiny" data-page-size="8" data-limit-navigation="3">
        <thead>
        <tr>
            <th data-hide="phone" data-sort-ignore="true">
                <div class="checkbox checkbox-primary checkbox-circle">
                    <input id="checkbox-all" type="checkbox">
                    <label for="checkbox-all"></label>
                </div>
            </th>
            <th data-hide="all"> Archived </th>
            <th data-hide="all"> Date Deleted </th>
            <th data-hide="phone" data-toggle="true"> Location </th>
            <th data-hide="all"> Subject </th>
            <th data-hide="phone" data-sort-ignore="true"> Action </th>
        </tr>
        </thead>
        <div class="form-inline m-b-20">
            {{--<div class="row">--}}
            <div class="col-md-6 text-xs-center">
                <div class="form-group">
                    <label class="control-label m-r-5">Table</label>
                    <select id="" class="demo-foo-filter-status form-control input-sm">
                        <option value="">Show all</option>
                        @foreach($tables as $table)

                            <option value="{{ strtolower($table->thetable) }}">{{ $table->thetable }}</option>

                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6 text-center text-right">
                <div class="form-group float-right">
                    <input id="demo-foo-search" type="text" placeholder="Search" class="form-control" autocomplete="on">
                </div>
            </div>
        </div>
        <tbody>

        @foreach($archives as $archive)
            <tr>
                <td>
                    <div class="checkbox checkbox-primary checkbox-circle">
                        <input id="checkbox-{{ $archive->id }}" type="checkbox" class="table-checkbox">
                        <label for="checkbox-{{ $archive->id }}"></label>
                    </div>
                </td>
                <td>{{ $archive->columnvalues }}</td>
                <td>{{ $archive->datearchived->format('F d, Y') }}</td>
                <td>{{ $archive->thetable }}</td>
                <td>{{ $archive->title }}</td>
                <td class="actions">
                    <a href="#edit" class="on-default edit-row" title="restore"><i class="fa fa-refresh"></i></a>
                </td>
            </tr>
        @endforeach

        </tbody>
        <tfoot>
        <tr class="active">
            <td colspan="5">
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
@endpush