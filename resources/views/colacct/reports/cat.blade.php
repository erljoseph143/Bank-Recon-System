@extends('colacct.layouts.main')

@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Reports<small>Disbursement Summary</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="{{ url('colacct/reports/disbursement_summary') }}">Reports</a></li>
                <li class="active">Code Categories</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content" data-page-title="{{ $title }}">

            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">{{ $bank . " - " . $accountno . " - " . $accountname }}</h3>
                        </div>
                        <div class="box-body">
                            <table id="checks-table" class="table table-bordered table-hover view-report-table">
                                <thead>
                                <tr>
                                    <th>Dates</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>Dates</th>
                                    <th></th>
                                </tr>
                                </tfoot>
                                <tbody>

                                @foreach($months as $month)

                                    <tr>
                                        <td>{{ $month->datein }}</td>
                                        <td>
                                            <div class="dpdown">
                                                <span class="my-button"><i class="fa fa-ellipsis-h"></i></span>
                                                <ul class="dropdown-menu animated flipInX">
                                                    <li>
                                                        <a target="_blank" href="{{ url("colacct/reports/disbursement_summary/excel_report/$month->nav_setup_no/$month->datein/$bank/$accountno/$accountname") }}" data-info=".json_encode($data, JSON_FORCE_OBJECT)." data-url="{{ url("colacct/reports/disbursement_summary/excel_report/$month->nav_setup_no/$month->datein/$bank/$accountno/$accountname") }}" data-url2="{{ url("assets/reports") }}" class="xport-xcel" title="Export to Excel"><i class="fa fa-file-excel-o"></i> Export to Excel</a>
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
    </div>

@endsection