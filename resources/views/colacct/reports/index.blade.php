@extends('colacct.layouts.main')

@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Reports <small>Disbursement Summary</small></h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Reports</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content" data-page-title="{{ $title }}">
            <div class="row">

                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Bank accounts under Colonnade</h3>
                        </div>
                        <div class="box-body">
                            <table id="checks-table" class="table table-bordered table-hover report-table">
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
                                @foreach($accounts as $account)
                                    <tr>
                                        <td>{{ $account->bank }}</td>
                                        <td>{{ $account->accountno }}</td>
                                        <td>{{ $account->accountname }}</td>
                                        <td>
                                            <div class="dpdown">
                                                <span class="my-button"><i class="fa fa-ellipsis-h"></i></span>
                                                <ul class="dropdown-menu animated flipInX">
                                                    <li>
                                                        <a data-url="{{ url("colacct/reports/disbursement_summary/categories/$account->id/$account->bankno/$account->bank/$account->accountno/$account->accountname") }}" data-info=".json_encode($data, JSON_FORCE_OBJECT)." href="{{ url("colacct/reports/disbursement_summary/categories/$account->id/$account->bankno/$account->bank/$account->accountno/$account->accountname") }}" class="view-report-month" title="View each month"><i class="fa fa-eye"></i> View</a>
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