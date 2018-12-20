@extends('designex.layouts.snoopy')
@section('content')
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">subsidiary ledgers</h6>
                </div>
                <div class="pull-right">
                    <a href="javascript:void(0);" class="slide-toggle pull-left inline-block search mr-15">
                        <i class="zmdi zmdi-search"></i>
                    </a>
                    <a href="#" class="pull-left inline-block full-screen mr-15">
                        <i class="zmdi zmdi-fullscreen"></i>
                    </a>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                    @include('designex.sl.searchbox')
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-wrap">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="sl-table">
                                        <thead>
                                        <th>doc date</th>
                                        <th>doc type</th>
                                        <th>doc no</th>
                                        <th>account code</th>
                                        <th>ledger code</th>
                                        <th>debit</th>
                                        <th>credit</th>
                                        <th>balance</th>
                                        {{--<th>action</th>--}}
                                        </thead>
                                        <tfoot>
                                        <th>doc date</th>
                                        <th>doc type</th>
                                        <th>doc no</th>
                                        <th>account code</th>
                                        <th>ledger code</th>
                                        <th>debit</th>
                                        <th>credit</th>
                                        <th>balance</th>
                                        {{--<th>action</th>--}}
                                        </tfoot>
                                        <tbody>
                                        @include('designex.sl.load')
                                        </tbody>
                                    </table>
                                    <div class="pull-left">Showing <span id="from">{{ number_format($sls->firstItem()) }}</span> to <span id="to">{{ number_format($sls->lastItem()) }}</span> of <span id="total-entries">{{ number_format($sls->total()) }}</span> entries</div>
                                    <div class="pull-right paginator-container">
                                        @include('designex.sl.pagination')
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
@section('endstyles')
    <link rel="stylesheet" href="{{ asset('designex/vendor/jquery-ui/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('designex/vendor/month-picker/MonthPicker.min.css') }}">
    <link rel="stylesheet" href="{{ url('designex/css/main.css') }}">
@endsection
@section('endscripts')

    <script src="{{ asset('designex/vendor/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('designex/vendor/jquery-mask-input/jquery.maskedinput.min.js') }}"></script>
    <script src="{{ asset('designex/vendor/month-picker/MonthPicker.min.js') }}"></script>
    <script src="{{ asset('designex/vendor/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script>
        $('#DigitalBush').MonthPicker({
//            MinMonth: ,
//            +2y -6m
            MaxMonth: 0,
            UseInputMask: true,
//            StartYear: 2017
        });
    </script>
    <script type="text/javascript" src="{{ url('designex/larry-scripts/sl.js') }}"></script>
@endsection