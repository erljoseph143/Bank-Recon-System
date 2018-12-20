@extends('designex.layouts.snoopy')
@section('content')
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">proof lists</h6>
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
                    <div id="searchbox" class="collapse aria-slide">
                        <div class="row">
                            <div class="col-sm-12 ">
                                <span class="tag label label-primary">
                                    prooflist
                                    <span>
                                        <i class="fa fa-close"></i>
                                    </span>
                                </span>
                                <hr class="light-grey-hr">
                            </div>
                        </div>
                        <form id="search-form" action="{{ route('xgetprooflist') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="key" value="transact">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="">Fields</label>
                                        <div class="radio radio-primary">
                                            <input type="radio" name="plradio" id="docno" value="doc_no" checked>
                                            <label for="docno"> Document no </label>
                                        </div>
                                        <div class="radio radio-primary">
                                            <input type="radio" name="plradio" id="payee" value="payee">
                                            <label for="payee"> Payee </label>
                                        </div>
                                        <div class="radio radio-primary">
                                            <input type="radio" name="plradio" id="amount" value="amount">
                                            <label for="amount"> Amount </label>
                                        </div>
                                        <div class="radio radio-primary">
                                            <input type="radio" name="plradio" id="checkno" value="check_no">
                                            <label for="checkno"> Check No </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-9">
                                    <label for="">Banks</label>
                                    <div class="banks-cont" data-url="{{ route('xsearchprooflist') }}">
                                        @foreach($checkbanks as $key => $checkbank)
                                        <div class="checkbox checkbox-primary">
                                            <input name="banks" id="banks-{{ $key }}" type="checkbox" value="{{ $checkbank->check_bank }}">
                                            <label for="banks-{{ $key }}"> {{ $checkbank->check_bank }} </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                {{--<div class="col-sm-2">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label for="searchcheckbank">bank</label>--}}
                                        {{--<div class="ui fluid search selection dropdown">--}}
                                            {{--<i class="search-spinner fa fa-circle-o-notch fa-spin" style="display: none;"></i>--}}
                                            {{--<input name="bank" id="search-bank" data-search-url="{{ route('xsearchprooflist') }}" type="text" value="" placeholder="Search banks" class="form-control" autocomplete="off">--}}
                                            {{--<ul id="search-menu" class="menu" data-search-url="{{ route('xsearchprooflist') }}">--}}
                                            {{--</ul>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="searchcheckbank">Search fields</label>
                                        <div class="ui fluid search selection dropdown">
                                            {{--<i class="search-spinner fa fa-circle-o-notch fa-spin" style="display: none;"></i>--}}
                                            <input name="fields" id="search-field" data-search-url="{{ route('xsearchprooflist') }}" type="text" value="" placeholder="Search fields" class="form-control" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="year">document date</label>
                                        <select class="form-control" name="date" id="date-option" disabled></select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <button type="button" class="btn btn-xs btn-default btn-anim slide-toggle"><i class="icon-close"></i><span class="btn-text">close filter</span></button>
                                    <button type="button" class="btn btn-xs btn-default btn-anim clear-filter" value="clearfilter"><i class="icon-close"></i><span class="btn-text">clear filter</span></button>
                                    <button id="search" type="submit" name="search" class="btn btn-xs btn-primary btn-anim"><i class="icon-rocket"></i><span class="btn-text">filter</span></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-wrap">
                                {{--<div class="clearfix"></div>--}}
                                {{--<div class="tbl-spinner-cont">--}}
                                    {{--<div class="tbl-spinner-wrap">--}}
                                        {{--<div class="tbl-spinner" style="text-align: center;"><i style="font-size: 40px; color: #ed8739;" class="fa fa-circle-o-notch fa-spin"></i></div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                <div class="table-responsive">
                                    <table id="prooflists" class="table table-hover display  pb-30">
                                        <thead>
                                        <tr>
                                            <th>check_date</th>
                                            <th>check number</th>
                                            <th>bank</th>
                                            <th>document no</th>
                                            <th>document date</th>
                                            <th>payee</th>
                                            <th>amount</th>
                                            {{--<th>action</th>--}}
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th>check_date</th>
                                            <th>check number</th>
                                            <th>bank</th>
                                            <th>document no</th>
                                            <th>document date</th>
                                            <th>payee</th>
                                            <th>amount</th>
                                            {{--<th>action</th>--}}
                                        </tr>
                                        </tfoot>
                                        <tbody>
                                            @if(count($prooflists) > 0)
                                                @include('designex.prooflist.load')
                                            @endif
                                        </tbody>
                                    </table>
                                    <div class="pull-left">Showing <span id="from">{{ number_format($prooflists->firstItem()) }}</span> to <span id="to">{{ number_format($prooflists->lastItem()) }}</span> of <span id="total-entries">{{ number_format($prooflists->total()) }}</span> entries</div>
                                    <div class="pull-right paginator-container">
                                        @include('designex.prooflist.pagination')
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
    <!--alerts CSS -->
{{--    <link href="{{ asset('snoopy/vendors/bower_components/datatables/media/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css"/>--}}
@endsection
@section('endstyles')
    <link rel="stylesheet" href="{{ url('designex/css/main.css') }}">
@endsection
@section('scripts')
    <!-- Data table JavaScript -->
{{--    <script src="{{ asset('snoopy/vendors/bower_components/datatables/media/js/jquery.dataTables.min.js') }}"></script>--}}
@endsection
@section('endscripts')
    <script src="{{ asset('designex/larry-scripts/myfunc.js') }}"></script>
    <script src="{{ asset('designex/larry-scripts/prooflist.js') }}"></script>
    <script src="{{ asset('designex/larry-scripts/table.js') }}"></script>
@endsection