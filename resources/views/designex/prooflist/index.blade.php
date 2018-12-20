@extends('designex.layouts.snoopy')
@section('content')
<div class="col-sm-12">
    <div class="panel panel-default card-view">
        <div class="panel-heading">
            <div class="pull-left">
                <h6 class="panel-title txt-dark">proof lists</h6>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="panel-wrapper collapse in">
            <div class="panel-body">
                <div class="table-wrap">
                    <div class="table-responsive">
                        <table id="pl_1" class="table table-hover display  pb-30" data-action="{{ route('xgetprooflist') }}">
                            <thead>
                            <tr>
                                {{--<th>Document Date</th>--}}
                                <th>Document Number</th>
                                <th>Payee</th>
                                {{--<th>Description</th>--}}
                                <th>Amount</th>
                                <th>Check Date</th>
                                <th>Check Number</th>
                                <th>action</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                {{--<th>Document Date</th>--}}
                                <th>Document Number</th>
                                <th>Payee</th>
                                {{--<th>Description</th>--}}
                                <th>Amount</th>
                                <th>Check Date</th>
                                <th>Check Number</th>
                                <th>action</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('styles')
    <!--alerts CSS -->
    <link href="{{ asset('snoopy/vendors/bower_components/datatables/media/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('snoopy/vendors/bower_components/sweetalert/dist/sweetalert.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('scripts')
    <!-- Data table JavaScript -->
    <script src="{{ asset('snoopy/vendors/bower_components/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('designex/vendor/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <!-- Sweet-Alert  -->
    <script src="{{ asset('snoopy/vendors/bower_components/sweetalert/dist/sweetalert.min.js') }}"></script>
@endsection
@section('endscripts')
    <script>
        var token = $('meta[name="csrf-token"]').attr('content'),
            table = $('#pl_1'),
            url = table.data('action');
        console.log(url);

        table.DataTable({
            "iDisplayLength": 8,
            "aLengthMenu": [[8, 10, 25, 50, -1], [8, 10, 25, 50, "All"]],
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": url,
                "dataType": "json",
                "type": "POST",
                "data":{ _token: token}
            },
            "columns": [
//                { "data": "doc_date" },
                { "data": "doc_no" },
                { "data": "payee" },
//                { "data": "description" },
                { "data": "amount" },
                { "data": "check_date" },
                { "data": "check_no" },
                { "data": "action" },
            ]
        });
    </script>
@endsection