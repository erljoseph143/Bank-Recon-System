@extends('admin.layouts.main')

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="">
                <div class="card-box">
                    <ul class="nav nav-tabs tabs-bordered">
                        <li class="nav-item">
                            <a href="#largedatatables" data-toggle="tab" aria-expanded="false" class="nav-link">
                                Large Data Tables
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#smalldatatables" data-toggle="tab" aria-expanded="false" class="nav-link">
                                Other Tables
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane" id="largedatatables">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card-box">
                                        <h4 class="text-dark header-title m-t-0">Select Options</h4>
                                        <div class="row">
                                            <form action="{{ url('admin/backup/displaytable') }}" method="post" id="form-321352312">
                                                {{ csrf_field() }}
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="">Select Table</label>
                                                        <select name="table" data-url="{{ url('admin/backup/displaybu') }}" id="table" class="form-control">
                                                            <option value="-1">Select Table</option>
                                                            @foreach($tables as $key => $table)
                                                                <option value="{{ $table }}">{{ $key }}</option>
                                                            @endforeach
                                                        </select>
                                                        <i id="bu-loader" class="fa fa-spin fa-circle-o-notch"></i>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="">Select Business unit</label>
                                                        <select name="bu" data-url="{{ url('admin/backup/displaycodes') }}" id="bu" class="form-control" disabled>
                                                            <option value="-1">Select BU</option>
                                                            @foreach($bus as $bu)
                                                                <option value="{{ $bu->unitid }}">{{ $bu->bname }}</option>
                                                            @endforeach
                                                        </select>
                                                        <i id="codes-loader" class="fa fa-spin fa-circle-o-notch"></i>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        {{--<label for="">Select Bank Account</label>--}}
                                                        <label for="">Select Bank Code</label>
                                                        <select name="acc" id="acc" class="form-control" disabled>
                                                            {{--<option data-bu="" value="-1">Select Bank Account</option>--}}
                                                            <option data-bu="" value="-1">Select Bank Code</option>
                                                            {{--@foreach($accounts as $account)--}}
                                                            {{--<option data-bu="{{ $account->buid }}" value="{{ $account->id }}">{{ $account->bank . ' ' . $account->accountno . ' ' . $account->accountname }}</option>--}}
                                                            {{--@endforeach--}}
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="bankname">Bank Name</label>
                                                        <input id="bankname" class="form-control" name="bankname" type="text" disabled>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card-box">
                                        <h4 class="text-dark header-title m-t-0">Month Lists</h4>
                                        <table id="demo-foo-filtering" class="large-data-table backup-table-143134231 table m-b-0 table-bordered toggle-arrow-tiny" data-page-size="8" data-limit-navigation="3">
                                            <thead>
                                            <tr>
                                                <th data-sort-ignore="true">Tables</th>
                                                <th data-sort-ignore="true"> Action </th>
                                            </tr>
                                            </thead>
                                            <div class="form-inline m-b-20">
                                                <div class="col-md-6 text-xs-center">
                                                    <div class="form-group">
                                                        <label class="control-label m-r-5">Added By</label>
                                                        <select id="" class="demo-foo-filter-status form-control input-sm">
                                                            <option value="">Show all</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 text-center text-right">
                                                    <div class="form-group pull-right">
                                                        <input id="demo-foo-search" type="text" placeholder="Search" class="form-control" autocomplete="on">
                                                    </div>
                                                </div>
                                            </div>
                                            <i id="table-loader" class="fa fa-spin fa-circle-o-notch"></i>
                                            <tbody>
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
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane" id="smalldatatables">

                            <table id="demo-foo-filtering" class="small-data-table backup-table-609874 table m-b-0 table-bordered toggle-arrow-tiny" data-page-size="8" data-limit-navigation="3">
                                <thead>
                                    <tr>
                                        <th data-sort-ignore="true">Tables</th>
                                        <th data-sort-ignore="true"> Action </th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($newtables as $table)
                                    <tr>
                                        <td>{{ $table }}</td>
                                        <td>
                                            <a href="" data-table="{{ $table }}" data-url="{{ url('admin/backup/download-small-data-tables') }}" class="download-json-5445">
                                                <i class="fa fa-arrow-circle-o-down"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
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

                        </div>
                    </div>
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

    <script src="{{ asset('admin/assets/plugins/swal/sweetalert.min.js') }}"></script>

    <script src="{{ asset('admin/assets/js/backup.js') }}"></script>

@endpush