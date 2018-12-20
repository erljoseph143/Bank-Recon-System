@extends('admin.layouts.main')

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <h4 class="header-title">List of bank statements with duplicate entry</h4>
                <table id="duplicatebstable" class="table">
                    <thead>
                    <tr>
                        <th>Bank Date</th>
                        <th>Description</th>
                        <th>Bank Code</th>
                        <th>Check #</th>
                        <th>Amount</th>
                        <th>Balance</th>
                        <th>Stats</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($duplicatebs as $duplicate)
                        <tr>
                            <td>{{ $duplicate->bank_date->format('M d, Y') }}</td>
                            <td>{{ $duplicate->description }}</td>
                            <td>{{ $duplicate->bank_account_no }}</td>
                            <td>{{ $duplicate->bank_check_no }}</td>
                            <td>{{ number_format($duplicate->bank_amount, 2) }}</td>
                            <td>{{ number_format($duplicate->bank_balance, 2) }}</td>
                            <td><span class="badge badge-warning">{{ $duplicate->duplicate_count . " duplicate" }}</span></td>
                            <td><a id="" href="{{ route("adminviewduplicatebs") }}" data-value="{{ $duplicate->bank_account_no . "|" . $duplicate->bank_check_no . "|" . $duplicate->bank_amount . "|" . $duplicate->bank_balance }}" class="view-duplicate-bs-btn text-purple" data-toggle="modal" data-target=".bs-example-modal-lg"><i class="fa fa-eye"></i></a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-lg modal-full">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="myLargeModalLabel">Duplicate bank statement List</h4>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                            <table id="duplicate-bs-table" class="table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Account #</th>
                                    <th>Check #</th>
                                    <th>Amount</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                    <th>Type</th>
                                    <th>Business unit</th>
                                    <th>Company</th>
                                    <th>Date Uploaded</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            </div>
                            <div class="view-disabled" style="display: none"><div class="loader-1"></div></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/minton/plugins/datatables/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/minton/plugins/datatables/responsive.bootstrap4.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('admin/minton/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/minton/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script type="text/javascript">
        table = $('#duplicatebstable').DataTable();

        $('body').on('click', '.view-duplicate-bs-btn', function (e) {

            var data = $(this).data('value'),
                url = $(this).attr('href');

            $(this).addClass('text-success').removeClass('text-purple');

            $('#duplicate-bs-table tbody').html("");
            $('.view-disabled').show();

            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') } });

            $.post(url, {data:data}, function (e) {
                $('#duplicate-bs-table tbody').html(e);
                $('.view-disabled').hide();
            }).fail(function(err1, err2, err3) {
                alert( "error reload the page " + err1 + err2 + err3 );
            });

        });

        $('body').on('click', '.trash-dup-bs-btn', function (e) {

            var con = confirm("Are you sure?"),
                url = $(this).data('url'),
                id = $(this).closest('tr').data('id'),
                action = $(this).attr('data-action'),
                elem = $(this);

            if (con == true) {

                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') } });

                $.post(url, {id:id, action:action}, function (e) {
                    console.log(e);
                    if (e.action == 'trash') {
                        elem.closest('tr').css('color', 'red');
                        elem.html('<i class="fa fa-undo"></i>');
                        elem.attr('data-action', 'restore');
                    } else {
                        elem.closest('tr').css('color', 'black');
                        elem.html('<i class="fa fa-trash-o"></i>');
                        elem.attr('data-action', 'trash');
                    }
                }).fail(function(err1,err2,err3) {
                    alert( "error reload the page" + err1 + err2 + err3 );
                });
            }

        });

        $('body').on('click', '.prev-dup-bs-btn', function (e) {

            var id = $(this).closest('tr').data('id'),
                url = $(this).data('url');

            $.post(url, {id:id}, function (data) {
                $("body").find("tr[data-id='"+id+"']").before(data);
            }).fail(function() {
                alert( "error reload the page" );
            });

            $(this).prop('disabled', true);

        });

        $('body').on('click', '.next-dup-bs-btn', function (e) {

            var id = $(this).closest('tr').data('id'),
                url = $(this).data('url');

            $.post(url, {id:id}, function (data) {
                $("body").find("tr[data-id='"+id+"']").after(data);
            }).fail(function() {
                alert( "error reload the page" );
            });

            $(this).prop('disabled', true);

        });

    </script>
@endpush