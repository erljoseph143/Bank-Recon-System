@extends('admin.layouts.table')

@section('crumb')

    <li class='breadcrumb-item'><a href='{{ url("admin/home") }}'>Dashboard</a></li><li class='breadcrumb-item active'>{{ $pagetitle }}</li>

@endsection

@section('top-buttons')
    @if($template == "trash")
        <a href="{{ route('selectedcodes') }}" class="selected-row" title="restore" onclick="$.Notification.notify('white','top left', '', 'Successfully restored!')"><i class="ion-reply"></i></a>
        <span class="divider"></span>
        <a href="{{ route('selectedcodes') }}" title="delete" class="selected-row" title="delete permanently selected"><i class="ion-trash-b"></i></a>
    @else
        <a href="#add" id="add" page-title="addbankcode" data-page="bankcodes" data-toggle="modal" data-target="#modalTable"><i class="ion-plus"></i></a>
        <a href="{{ route('selectedcodes') }}" title="trash" class="selected-row"><i class="ion-trash-b"></i></a>
    @endif

@endsection

@section('table-nav')

    <button onclick="location.href='{{ url("admin/bankcodes?p=all") }}';" type="button" class="btn @if($template == 'all') btn-primary @else btn-default @endif btn-custom waves-effect w-md waves-light m-b-5">Active &#40;<span class="all-count">{{ $countall }}</span>&#41;</button>
    <button onclick="location.href='{{ url("admin/bankcodes?p=trash") }}';" type="button" class="btn @if($template == 'trash') btn-primary @else btn-default @endif btn-custom waves-effect w-md waves-light m-b-5">Trash &#40;<span class="trash-count">{{ $counttrash }}</span>&#41;</button>

@endsection

@section('content')

    <table id="demo-foo-filtering" class="table table-bordered" data-page-size="15" data-limit-navigation="3">
        <thead>
        <tr>
            <th data-hide="phone" data-sort-ignore="true">
                <div class="checkbox checkbox-primary checkbox-circle">
                    <input id="checkbox-all" type="checkbox">
                    <label for="checkbox-all"></label>
                </div>
            </th>
            <th data-toggle="true">Bank Code</th>
            <th data-hide="phone">Added By</th>
            <th data-hide="phone">Date Added</th>
            <th data-hide="phone, tablet">Modified By</th>
            <th data-hide="phone, tablet">Date Modified</th>
            <th data-hide="phone" data-sort-ignore="true"> Action </th>
        </tr>
        </thead>
        <div class="form-inline m-b-20">
            <div class="col-md-6 text-xs-center">
                <div class="form-group">
                    <label class="control-label m-r-5">Filter Date</label>
                    <select id="" class="demo-foo-filter-status form-control input-sm">
                        <option value="">Show all</option>
                        @foreach($date as $filter)
                            <option value="{{ $filter->updated_at->format('F d, Y') }}">{{ $filter->updated_at->format('F d, Y') }}</option>
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
        @include('admin.bankcode.load')
        </tbody>
        <tfoot>
        <tr class="active">
            <td colspan="7">
                <div class="text-right">
                    <ul class="pagination pagination-split justify-content-end footable-pagination m-t-10 m-b-0"></ul>
                </div>
            </td>
        </tr>
        </tfoot>
    </table>
@endsection

@section('modal')
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalTable">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span>&times;</span></button>
                    <h4 class="modal-title" id="modal-title">Generated title</h4>
                </div>
                <div class="modal-body">
                    <form id="form-1437" method="POST" action="{{ route("bankcodes.index") }}" name="" class="form-horizontal" novalidate="">
                        {{--{{ csrf_field() }}--}}
                        <div class="row" id="modal-content">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="bankcode" class="control-label">Bank Code</label>
                                    <input type="text" name="bankcode" class="form-control" id="bankcode" placeholder="Input Code">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="save" type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                            <input type="hidden" id="code" name="code" value="0">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
    <script src="{{ asset('admin/assets/js/bankcode.js') }}"></script>

@endpush