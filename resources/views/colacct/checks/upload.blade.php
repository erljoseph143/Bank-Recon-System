@extends('colacct.layouts.main')

{{--@section('crumb')--}}

{{--<li class='breadcrumb-item active'>{{ $title }}</li>--}}

{{--@endsection--}}

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Dashboard<small>Control panel</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="callout callout-default">
                <h4><i class="fa fa-exclamation-circle"></i>&nbsp;Note</h4>
                <p>Only checking account from colonnade can
                    only be uploaded.</p>
                <p>Each excel file must be by month.</p>
            </div>
            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <section class="col-lg-12 connectedSortable">
                    <!-- TO DO List -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <i class="ion ion-clipboard"></i>
                            <h3 class="box-title">Upload Check</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">

                            <div class="form-group">
                            <label>Select Bank Account</label>
                                <select class="form-control bankno" name="bankno">
                                    @foreach($bankaccounts as $bankaccount)
                                        <option value="{{ $bankaccount->id }}">{{ $bankaccount->bank . ' - ' . $bankaccount->accountno . ' - ' . $bankaccount->accountname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <form action="{{ url('colacct/upload_progress') }}" method="post" enctype="multipart/form-data" accept-charset="utf-8" class="drag-drop-area csv-upload-form" data-success="{{ url('colacct/checking_accounts/{code}') }}" data-error="{{ url('colacct/upload_error') }}">
                                {{ csrf_field() }}
                                <div class="box__input">
                                    <input type="hidden" id="prog-url" value="">
                                    <input type="hidden" id="prog-status" value="">
                                    <p class="drag-drop-info">Drop files here</p>
                                    <p>or</p>
                                    <p class="drag-drop-buttons">
                                        <input type="file" class="box__file" id="file" data-ses="{{ $login_user_id }}" name="excelfile[]" accept=".xlsx, .xls" multiple="" data-multiple-caption="{count} files selected">
                                        <label for="file" class="btn btn-sm bg-olive btn-flat">Select Files</label>
                                    </p>
                            {{--/**--}}
                             {{--* for testing only--}}
                             {{--*/--}}
                            {{--// echo form_input('bankno', 'NDZTRGtmZEtnY3hzckFCdg==');--}}
                            {{--// echo form_submit('mysubmit', 'Upload');--}}

                                </div>
                            </form>

                        </div>
                    </div>
                    <!-- /.box -->
                </section>
                <!-- /.Left col -->
            </div>
            <!-- /.row (main row) -->

        </section><!-- /.content -->
    </div>

@endsection

@push('styles')
@endpush

@push('scripts')

@endpush