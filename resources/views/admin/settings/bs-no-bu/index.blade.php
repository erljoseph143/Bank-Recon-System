@extends('admin.layouts.main')

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <form id="no-bu-bs-form" action="{{ route('admin.trashbsnobu.index') }}" action="POST">
                <h4 class="header-title" style="float: left">No business unit bank statement</h4>

                <div class="card-widgets m-b-20" style="display: inline-block;
    float: right;
    font-size: 15px;
    line-height: 30px;
    padding-left: 15px;
    position: relative;
    text-align: right;
    top: 0;
    margin: 0;
    padding: 0;">
                    <input type="hidden" value="{{ ($template=="trash")?"delete":"trash" }}" name="action">
                    @if($template == "trash")
                        <button data-url="{{ route('admin.trashbsnobu.index') }}" type="submit" title="trash" class="btn btn-danger waves-effect waves-light selected-row" style="padding: .5rem 1rem;">Delete forever <i class="ion-trash-b"></i></button>
                    @else
                        <button data-url="{{ route('admin.trashbsnobu.index') }}" type="submit" title="trash" class="btn btn-success waves-effect waves-light selected-row" style="padding: .5rem 1rem;">
                            Delete selected <i class="ion-trash-b"></i>
                        </button>
                    @endif
                </div>
                    <div class="clearfix"></div>
                    <button onclick="location.href='{{ route('admin.bsnobu.index', ["p"=>"all"]) }}';" type="button" class="btn @if($template == 'all') btn-primary @else btn-default @endif btn-custom waves-effect w-md waves-light m-b-5">Active  &#40;<span class="all-count">{{ $countall }}</span>&#41;</button>
                    <button onclick="location.href='{{ route('admin.bsnobu.index', ["p"=>"trash"]) }}';" type="button" class="btn @if($template == 'trash') btn-primary @else btn-default @endif btn-custom waves-effect w-md waves-light m-b-5">Trash  &#40;<span class="trash-count">{{ $counttrash }}</span>&#41;</button>

                <div class="table-responsive">
                    <table id="nobubstable" class="table">
                        <thead>
                        <tr>
                            <th>
                                {{--<input id="check-all-box" type="checkbox">--}}
                                <input name="select_all" value="1" type="checkbox">
                            </th>
                            <th>ID</th>
                            <th>Bank Date</th>
                            <th>Description</th>
                            <th>Check #</th>
                            <th>Amount</th>
                            <th>Balance</th>
                            <th>Account #</th>
                            {{--<th>BU</th>--}}
                            <th>Controls</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($bankstatements as $bankstatement)
                        <tr>
                            <td>
                                {{  $bankstatement->bank_id }}
                            </td>
                            <td>{{ $bankstatement->bank_id }}</td>
                            <td>{{ $bankstatement->bank_date->format('M d, Y') }}</td>
                            <td>{{ $bankstatement->description }}</td>
                            <td>{{ $bankstatement->bank_check_no }}</td>
                            <td>{{ number_format($bankstatement->bank_amount, 2) }}</td>
                            <td>{{ (is_numeric($bankstatement->bank_balance))?number_format($bankstatement->bank_balance, 2):$bankstatement->bank_balance }}</td>
                            <td>{{ $bankstatement->bank_account_no }}</td>
                            {{--<td>--}}
                                {{--<select name="" id="">--}}
                                    {{--<option value="">( Select business unit )</option>--}}
                                {{--</select>--}}
                            {{--</td>--}}
                            <td>
                                <a class="text-dark view-bank-account-btn m-r-10" href="{{ route('admin.bsbankaccountlist.view', ['code' => $bankstatement->bank_account_no]) }}" title="view related bank accounts" data-toggle="modal" data-target=".bs-example-modal-sm"><i class="fa fa-eye"></i></a>
                                <a class="text-dark view-near-bs-btn" href=""><i class="fa fa-list"></i></a>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Bank Date</th>
                            <th>Description</th>
                            <th>Check #</th>
                            <th>Amount</th>
                            <th>Balance</th>
                            <th>Account #</th>
                            <th>Controls</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                </form>
            </div>
            <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="mySmallModalLabel">Possible Bank Accounts</h4>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table id="bank-accounts-table" class="table">
                                    <thead>
                                    <tr>
                                        <th>Bank</th>
                                        <th>Account #</th>
                                        <th>Account Name</th>
                                        <th>Business unit</th>
                                        <th>Company</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/minton/plugins/datatables/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/minton/plugins/datatables/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/plugins/datatables/select.dataTables.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('admin/minton/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/minton/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/assets/plugins/datatables/dataTables.select.min.js') }}"></script>

    <script type="text/javascript">

        function updateDataTableSelectAllCtrl(table) {
            var $table = table.table().node();
            var $chkbox_all = $('tbody input[type="checkbox"]', $table);
            var $chkbox_checked = $('tbody input[type="checkbox"]:checked', $table);
            var chkbox_select_all  = $('thead input[name="select_all"]', $table).get(0);

            if($chkbox_checked.length === 0){
                chkbox_select_all.checked = false;
                if('indeterminate' in chkbox_select_all){
                    chkbox_select_all.indeterminate = false;
                }
            } else if ($chkbox_checked.length === $chkbox_all.length){
                chkbox_select_all.checked = true;
                if('indeterminate' in chkbox_select_all){
                    chkbox_select_all.indeterminate = false;
                }
            } else {
                chkbox_select_all.checked = true;
                if('indeterminate' in chkbox_select_all){
                    chkbox_select_all.indeterminate = true;
                }
            }
        }

        $(document).ready(function() {

            var rows_selected = [];

            var table = $('#nobubstable').DataTable({
                'columnDefs': [{
                    'targets': 0,
                    'searchable':false,
                    'orderable':false,
                    'className': 'dt-body-center',
                    'render': function (data, type, full, meta){
                        return '<input type="checkbox">';
                    }
                }],
                'order': [1, 'asc'],
                'rowCallback': function(row, data, dataIndex){
                    var rowId = data[0];
                    if($.inArray(rowId, rows_selected) !== -1){
                        $(row).find('input[type="checkbox"]').prop('checked', true);
                        $(row).addClass('selected');
                    }
                },
                'lengthMenu': [[8, 10, 25, 50, -1], [8, 10, 25, 50, "All"]],
                'pageLength': 8
            });

            $('#nobubstable tbody').on('click', 'input[type="checkbox"]', function(e){

                var $row = $(this).closest('tr'),
                    data = table.row($row).data(),
                    rowId = data[0],
                    index = $.inArray(rowId, rows_selected);


                if(this.checked && index === -1) {
                    rows_selected.push(rowId);
                } else if (!this.checked && index !== -1){
                    rows_selected.splice(index, 1);
                }

                if(this.checked){
                    $row.addClass('selected');
                } else {
                    $row.removeClass('selected');
                }

                updateDataTableSelectAllCtrl(table);

                e.stopPropagation();

            });

            $('#nobubstable').on('click', 'tbody td, thead th:first-child', function(e){
                $(this).parent().find('input[type="checkbox"]').trigger('click');
            });

            $('thead input[name="select_all"]', table.table().container()).on('click', function(e){

                if(this.checked){
                    $('tbody input[type="checkbox"]:not(:checked)', table.table().container()).trigger('click');
                } else {
                    $('tbody input[type="checkbox"]:checked', table.table().container()).trigger('click');
                }

                e.stopPropagation();

            });

            table.on('draw', function(){
                updateDataTableSelectAllCtrl(table);
            });

            $('#no-bu-bs-form').on('submit', function (e) {
                var form = this,
                url = $(this).attr('action');

                e.preventDefault();

                $.each(rows_selected, function(index, rowId){
                    $(form).append(
                        $('<input>')
                            .attr('type', 'hidden')
                            .attr('name', 'id[]')
                            .val(rowId)
                    );
                });

                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') } });

                $.post(url, $(form).serialize(), function (data) {
                    console.log(data);
                    $('input[name="id\[\]"]', form).remove();
                    table.rows('.selected').remove().draw( false );
                    alert('trashed');
                }).fail(function (error, error2, error3) {
                    alert('error=>'+error+error2+error3);
                });

            });

        });

        $('.view-bank-account-btn').on('click', function (e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') } });

            $.post(url, function (data) {
//                console.log(data);
                $('#bank-accounts-table tbody').html(data);
            });

        });

        $('.view-near-bs-btn').on('click', function (e) {
            e.preventDefault();

            alert('processing...');

        });

    </script>
@endpush