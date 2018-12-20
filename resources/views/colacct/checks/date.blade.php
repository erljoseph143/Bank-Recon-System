@extends('colacct.layouts.main')

@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Checking Accounts <small>Date Categories</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Banking</a></li>
                <li class="active"><a href="{{ url('colacct/checking_accounts') }}">Checking Accounts</a></li>
                <li class="active">Date Categories</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content" data-page-title="{{ $title }}">
            <div class="row">

                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Checking Accounts Under {{ $bu->bname }}</h3>
                            <div class="btn-group pull-right">
                                <button class="btn btn-md bg-1 del-checkbox" disabled="" data-url="{{ url('checking_accounts/delete') }}" data-url-2="{{ url('checking_accounts') }}">Delete Checked</button>
                                <!-- <button class="btn btn-md bg-1">Delete All</button> -->
                            </div>
                        </div>
                        <div class="box-body">
                            <table id="checks-cat" class="table table-bordered table-hover checks-cat dataTable">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Check Date</th>
                                    <th>Uploaded By</th>
                                    <th>Date Uploaded</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th></th>
                                    <th>Check Date</th>
                                    <th>Uploaded By</th>
                                    <th>Date Uploaded</th>
                                    <th></th>
                                </tr>
                                </tfoot>
                                <tbody>
                                @foreach($check_dates as $dates)
                                    <tr>
                                        <td style="text-align: center;">
                                            <div style="position: relative;">
                                                <input type="checkbox" value="{{ json_encode(array($dates->datein, $dates->nav_setup_no), JSON_FORCE_OBJECT) }}" class="flat-red boxes" name="ids[]">
                                            </div>
                                        </td>
                                        <td>{{ $dates->datein }}</td>
                                        <td>{{ $dates->name }}</td>
                                        <td>
                                            {{ $dates->created_at->format('M d, Y') }}
                                        </td>
                                        <td>
                                            <div class="dpdown">
                                                <span class="my-button"><i class="fa fa-ellipsis-h"></i> </span>
                                                <ul class="dropdown-menu animated flipInX">
                                                    <li>
                                                        <a title="View data for the month of {{ $dates->datein }}" type="button" class="view-checks" data-url="{{ url("colacct/checking_accounts/$dates->nav_setup_no/$dates->datein") }}" data-info="">
                                                            <i class="fa fa-eye"></i> View
                                                        </a>
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