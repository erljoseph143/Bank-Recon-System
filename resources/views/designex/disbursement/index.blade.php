@extends('designex.layouts.metronic2')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="tabbable-custom tabbable-noborder">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#tab_1" data-toggle="tab">Tab one</a>
                    </li>
                    <li>
                        <a href="#tab_2" class="tab_2" data-toggle="tab">Tab two</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="portlet light">
                                    <div class="portlet-title">
                                        <div class="caption font-purple-plum">
                                            <i class="icon-speech font-purple-plum"></i>
                                            <span class="caption-subject bold uppercase"> Disbursements</span>
                                            <span class="caption-helper">View</span>
                                        </div>
                                        <div class="actions">
                                            <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;" data-original-title="" title="">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="portlet-body flip-scroll">
                                        <table id="transactions" data-action="{{ url('designex/accounting/disbursements?p=all') }}" class="table table-hover table-light gtreetable flip-content" id="gtreetable">
                                            <thead class="flip-content">
                                            <tr>
                                                <th>Document Date</th>
                                                <th>Document Number</th>
                                                <th>Payee</th>
                                                <th>Amount</th>
                                                <th>Check Date</th>
                                                <th>Check #</th>
                                                <th>Ledger Code</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab_2">
                        <div class="row">
                            <div class="col-md-6">
                                text2
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('logbook/metronic/assets/global/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('logbook/metronic/assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('logbook/metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}"/>
    <link rel="stylesheet" href="{{ url('designex/css/main.css') }}">
@endpush
@push('scripts')
{{--    <script src="{{ asset('admin/minton/plugins/datatables/jquery.dataTables.min.js') }}"></script>--}}
    <script type="text/javascript" src="{{ asset('logbook/metronic/assets/global/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('logbook/metronic/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('logbook/metronic/assets/global/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('logbook/metronic/assets/global/plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('logbook/metronic/assets/global/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('logbook/metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}"></script>

    <script type="text/javascript" src="{{ url('designex/larry-scripts/serversidedatatable.js') }}"></script>
@endpush
@push('scriptinit')
{{--    <script src="{{ asset('logbook/metronic/assets/admin/pages/scripts/table-advanced.js') }}"></script>--}}
    <script>
        jQuery(document).ready(function() {
            Metronic.init(); // init metronic core componets
            Layout.init(); // init layout
            ComponentsPickers.init();
            Index.init();
//            TableAdvanced.init();
        });

    </script>
@endpush