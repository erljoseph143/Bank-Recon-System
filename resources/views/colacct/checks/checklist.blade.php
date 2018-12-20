@extends('colacct.layouts.main')

@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Checking Accounts <small>Checking Accounts lists</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Banking</a></li>
                <li><a href="{{ url('colacct/checking_accounts') }}">Checking Accounts</a></li>
                <li><a href="{{ url("colacct/checking_accounts/$code") }}">Date Categories</a></li>
                <li class="active">View</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content" data-page-title="{{ $title }}">
            <div class="row">

                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">List of checks for the month of {{ $date }}! <span class="check-display-status badge bg-1">All</span></h3>
                            <div class="box-tools">
                                <div class="btn-group pull-right">
                                    <button data-info="all" data-trans="" data-url="{{ url("colacct/checking_accounts/month_checks/$code/$date") }}" class="checking-accounts-click btn bg-1 btn-flat btn-xs">Clear Filter</button>
                                    <button data-info="match" data-trans="" data-url="{{ url("colacct/checking_accounts/match_checks/$code/$date") }}" class="checking-accounts-click btn bg-1 btn-flat btn-xs">Match Check</button>
                                    {{--<button data-info="unmatch" data-trans="" data-url="{{ url('colacct/checking_accounts/all_checks_unmatch') }}" class="checking-accounts-click btn bg-1 btn-flat btn-xs">No Match Check</button>--}}
                                    {{--<button data-info="no-chk-num" data-trans="" data-url="{{ url('colacct/checking_accounts/all_no_checkno') }}" class="checking-accounts-click btn bg-1 btn-flat btn-xs">No Check Number</button>--}}
                                </div>
                            </div>
                        </div>
                        <div class="box-body">
                            <table class="table table-bordered table-hover dt-responsive no-wrap checks-table-detail">
                                <thead>
                                <tr>
                                    <th>Date Posted</th>
                                    <th>Check #</th>
                                    <th>Amount</th>
                                    <th>Balance</th>
                                    <th>Date Uploaded</th>
                                    <th>Uploaded By</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>Date Posted</th>
                                    <th>Check #</th>
                                    <th>Amount</th>
                                    <th>Balance</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </tfoot>
                                <tbody>
                                @foreach($checks as $check)
                                    <tr>
                                        <td>
                                            {{ $check->date_posted->format('M d, Y') }}
                                        </td>
                                        <td>{{ $check->check_no }}</td>
                                        <td>{{ $check->trans_amount }}</td>
                                        <td>{{ $check->balance }}</td>
                                        <td>{{ $check->created_at->format('M d, Y') }}</td>
                                        <td>{{ $check->firstname . ' ' . $check->lastname }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </section>
        <!-- /.content -->
    </div>

@endsection

@push('styles')

@endpush

@push('scripts')

@endpush