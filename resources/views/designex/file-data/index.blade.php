@extends('designex.layouts.snoopy')

@section('content')
    <div class="col-sm-12">
        <div class="panel panel-default card-view pa-0">
            <div class="panel-wrapper collapse in">
                <div class="panel-body pa-0">
                    <div class="contact-list">
                        <div class="row">
                            {{--<div class="col-sm-12">--}}
                                {{--<h5 class="txt-dark pr-20 pl-20 pb-20 pt-20">file data</h5>--}}
                            {{--</div>--}}
                            <aside class="col-lg-2 col-md-4 pr-0">
                                <ul class="inbox-nav mb-30" data-url="{{ route('file-data.index') }}">
                                    @if($banks->count())
                                        @foreach($banks as $key => $bank)
                                            <li class="{{ ($key==0)?'active':'' }}">
                                                <a href="#" data-value="{{ $bank->ledger_code }}">{{ $bank->ledger_code }} <span class="label label-{{ ($key % 2 == 0)?'warning':'success' }} ml-10">{{ $bank->data_count }}</span></a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </aside>
                            <aside class="col-lg-10 col-md-8 pl-0">
                                <div class="panel pa-0">
                                    <div class="panel-wrapper collapse in">
                                        <div class="panel-body  pa-0">
                                            <div class="table-responsive mb-30">
                                                <table id="file_table_1" class="table  display table-hover mb-30" data-page-size="10">
                                                    <thead>
                                                    <tr>
                                                        {{--<th>bank name</th>--}}
                                                        {{--<th>document date</th>--}}
                                                        <th>document date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        @include('designex.file-data.table')
                                                    </tbody>
                                                </table>
                                            </div>
                                            @include('designex.file-data.modal')
                                        </div>
                                    </div>
                                </div>
                            </aside>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('styles')
    <link href="{{ asset('snoopy/vendors/bower_components/sweetalert/dist/sweetalert.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('endstyles')
    <link rel="stylesheet" href="{{ url('designex/css/main.css') }}">
@endsection
@section('scripts')
    <script src="{{ asset('snoopy/vendors/bower_components/sweetalert/dist/sweetalert.min.js') }}"></script>
@endsection
@section('endscripts')
{{--    <script src="{{ asset('snoopy/vendors/bower_components/bootstrap-treeview/dist/bootstrap-treeview.min.js') }}"></script>--}}
    <script type="text/javascript" src="{{ asset('designex/larry-scripts/file-data.js') }}"></script>
@endsection