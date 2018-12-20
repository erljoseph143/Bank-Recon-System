@extends('designex.layouts.snoopy')
@section('content')
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">file settings</h6>
                </div>
                <div class="pull-right">
                    <a class="pull-left inline-block mr-15" data-toggle="collapse" href="#ledger_collapse" aria-expanded="true"><i class="zmdi zmdi-chevron-down"></i><i class="zmdi zmdi-chevron-up"></i></a>
                    <a href="#" class="pull-left inline-block full-screen mr-15"><i class="zmdi zmdi-fullscreen"></i></a>
                </div>
                <div class="clearfix"></div>
            </div>
            <div id="ledger_collapse" class="panel-wrapper collapse in">
                <div class="panel-body">
                    <div  class="pills-struct mt-10">
                        @include('designex.file-settings.tabnav')
                        <div class="tab-content" id="myTabContent_11">
                            <div  id="ledger_11" class="tab-pane fade active in" role="tabpanel">
                                <div class="row">
                                    <div class="col-sm-3">
                                        @include('designex.file-settings.ledger.setup')
                                        @include('designex.file-settings.ledger.search')
                                    </div>
                                    <div class="col-sm-9">
                                        {{--{{ $p }}--}}
                                        <table id="ledger-table" class="table table-hover table-bordered">
                                            <thead>
                                            <tr>
                                                <th>ledger code</th>
                                                <th>ledger name</th>
                                                <th>action</th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                <th>ledger code</th>
                                                <th>ledger name</th>
                                                <th>action</th>
                                            </tr>
                                            </tfoot>
                                            <tbody>
                                                @include('designex.file-settings.ledger.load')
                                            </tbody>
                                        </table>
                                        <div class="pull-left">Showing <span id="from">{{ number_format($ledgers->firstItem()) }}</span> to <span id="to">{{ number_format($ledgers->lastItem()) }}</span> of <span id="total-entries">{{ number_format($ledgers->total()) }}</span> entries</div>
                                        <div class="pull-right paginator-container">
                                            @include('designex.file-settings.ledger.pagination')
                                        </div>
                                        @include('designex.file-settings.ledger.modal')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('styles')
    <link href="{{ asset('snoopy/vendors/bower_components/sweetalert/dist/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('snoopy/vendors/bower_components/jquery-toast-plugin/dist/jquery.toast.min.css') }}">
@endsection
@section('endstyles')
    <link rel="stylesheet" href="{{ url('designex/css/main.css') }}">
@endsection
@section('scripts')
    <script src="{{ asset('snoopy/vendors/bower_components/jquery-toast-plugin/dist/jquery.toast.min.js') }}"></script>
    <script src="{{ asset('snoopy/vendors/bower_components/sweetalert/dist/sweetalert.min.js') }}"></script>
@endsection
@section('endscripts')
    <script src="{{ asset('designex/larry-scripts/ledger.js') }}"></script>
@endsection