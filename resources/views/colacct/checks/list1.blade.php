@extends('colacct.layouts.main')

@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Checking Accounts <small>Bank Accounts</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Banking</a></li>
                <li class="active">Checking Accounts</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content" data-page-title="{{ $title }}">
            <div class="row">

                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Checking Accounts Under {{ $bu->bname }}</h3>
                        </div>
                        <div class="box-body">
                            <table id="checks-table" class="table table-bordered table-hover checks-table">
                                <thead>
                                <tr>
                                    <th>Bank Name</th>
                                    <th>Bank Account #</th>
                                    <th>Bank Account Name</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>Bank Name</th>
                                    <th>Bank Account #</th>
                                    <th>Bank Account Name</th>
                                    <th></th>
                                </tr>
                                </tfoot>
                                <tbody>
                                    @foreach($newaccounts as $account)
                                    <tr>
                                        <input id="bankname" type="hidden" value="{{ $account[0] }}">
                                        <input id="accountno" type="hidden" value="{{ $account[1] }}">
                                        <input id="accountname" type="hidden" value="{{ $account[2] }}">
                                        <input id="code" type="hidden" value="{{ $account[3] }}">
                                        <td>{{ $account[0] }}</td>
                                        <td>{{ $account[1] }}</td>
                                        <td>{{ $account[2] }}</td>
                                        <td>
                                            <div class="dpdown">
                                                <span class="my-button"><i class="fa fa-ellipsis-h"></i> </span>
                                                <ul class="dropdown-menu animated flipInX">
                                                    <li>
                                                        <a data-url="{{ url("colacct/checking_accounts/$account[3]") }}" data-info="" href="{{ url("colacct/checking_accounts/$account[3]") }}" class="view-check-month" title="View each month"><i class="fa fa-eye"></i> View</a>
                                                    </li>
                                                </ul>
                                            </div>
		                                </td>
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