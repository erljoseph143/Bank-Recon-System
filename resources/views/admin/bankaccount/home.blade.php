@extends('admin.layouts.main3')

@section('crumb')

    <li class='breadcrumb-item active'>{{ $pagetitle }}</li>

@endsection

@section('content')
    <form id="control-form" action="{{ url('admin/bankaccounts') }}" method="POST">
        <div class="row">
        <div class="col-sm-6">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="">Select Company</label>
                <select data-url="{{ url('admin/bankaccounts?p=getbu') }}" name="company" id="company-js" class="form-control">
                    <option value="-1">( SELECT COMPANY )</option>
                    @foreach($companies as $company)

                        <option value="{{ $company->company->company_code }}">{{ $company->company->company }}</option>

                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="">Select Business Unit</label>
                <select data-url="{{ url('admin/bankaccounts?p=getacc') }}" name="bu" id="bu-js" class="form-control">
                    <option value="-1">( SELECT BUSINESS UNIT )</option>
                </select>
            </div>
        </div>
        </div>
    </form>
@endsection

@section('content2')

    <table id="display-accounts" class="table table-hover">
        <thead>
        <tr>
            <th>
                <div class="checkbox checkbox-primary checkbox-circle">

                    <input id="checkbox-all" type="checkbox">

                    <label for="checkbox-all"></label>

                </div>
            </th>
            <th>Bank Code</th>
            <th>Bank</th>
            <th>Account #</th>
            <th>Account Name</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

@endsection

@section('top-buttons')
    <a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a>
    <span class="divider"></span>
    <a data-toggle="collapse" data-parent="#accordion1" href="#bg-default"><i class="ion-minus-round"></i></a>
@endsection

@section('top-buttons2')

    <a href="#add" id="add" page-title="addbankaccount" data-page="bankaccounts" data-url="{{ url('admin/bankaccounts/add') }}"><i class="ion-plus"></i></a>

    <span class="divider"></span>

    <a data-url="{{ url("admin/bankaccounts/delete-selected") }}" href="#trashallselected" class="selected-row"><i class="ion-trash-b"></i></a>

@endsection

@section('modal')

    <div class="modal-demo" id="modalTable">
        <button type="button" class="close" onclick="Custombox.modal.close();">
            <span>&times;</span><span class="sr-only">Close</span>
        </button>
        <h4 class="custom-modal-title" id="modal-title">Generated title</h4>
        <div class="custom-modal-text">
            <div class="row" id="modal-content">
                <form id="form-1437" name="" class="" novalidate="" style="display: inherit; width: 100%;">
                    <div class="col-sm-4">
                        {{--<form id="form-1437" name="" class="form-horizontal" novalidate="">--}}
                        <div class="form-group">
                            <label for="bankcode" class="control-label">Bank Code</label>
                            <select class="form-control" id="bankcode" title="Bank Code" name="bankcode">
                                <option value="-1">(Select Bank Code)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="bankname" class="control-label">Bank Name</label>
                            <input type="text" class="form-control" id="bankname" placeholder="Input Bank">
                        </div>
                        <div class="form-group">
                            <label for="accountno" class="control-label">Account Number</label>
                            <input type="text" class="form-control" id="accountno" placeholder="Input Account #">
                        </div>
                        <div class="form-group">
                            <label for="accountname" class="control-label">Account Name</label>
                            <input type="text" class="form-control" id="accountname" placeholder="Input Account Name">
                        </div>
                        <div class="form-group">
                            <label for="currency" class="control-label">Currency</label>
                            <select class="form-control" id="currency" title="Currency" name="currency">
                                <option value="-1">(Select Currency)</option>
                            </select>
                        </div>
                        {{--</form>--}}
                    </div>

                    <div class="col-sm-4">

                        <div class="form-group">
                            <label for="company" class="control-label">Company</label>
                            <select class="form-control" id="company" title="Company" name="company">
                                <option value="-1">(Select Company)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="businessunit" class="control-label">Business Unit</label>
                            <select class="form-control" id="businessunit" title="Business Unit" name="businessunit">
                                <option value="-1">(Select Business Unit)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="status" class="control-label">Status</label>
                            <select class="form-control" id="status" title="Status" name="status">
                                <option value="0">Active</option>
                                <option value="1">Inactive</option>
                                <option value="2">Unknown</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="remarks" class="control-label">Remarks</label>
                            <input type="text" class="form-control" id="remarks" placeholder="Input Remarks">
                        </div>

                        <div class="form-group">
                            <label for="branchname" class="control-label">Branch Name</label>
                            <input type="text" class="form-control" id="branchname" placeholder="Input Branch Name">
                        </div>

                    </div>

                    <div class="col-sm-4">

                        <div class="form-group">
                            <label for="address" class="control-label">Address</label>
                            <input type="text" class="form-control" id="address" placeholder="Input Address">
                        </div>

                        <div class="form-group">
                            <label for="contact" class="control-label">Contact Person</label>
                            <input type="text" class="form-control" id="contact" placeholder="Input Contact Person">
                        </div>

                    </div>
                </form>

            </div>
        </div>
        <div class="modal-footer">
            <button data-url="{{ url("admin/bankaccounts") }}" id="save" type="button" class="btn btn-primary waves-effect waves-light">Save</button>
            <input type="hidden" id="code" name="code" value="0">
        </div>
    </div>

@endsection

@push('styles')

    <link href="{{ asset('admin/minton/plugins/jquery-circliful/css/jquery.circliful.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('admin/assets/css/main.css') }}" rel="stylesheet">

@endpush

@push('scripts')
    <script src="{{ asset('admin/assets/js/bankaccountfunction.js') }}"></script>
    <script src="{{ asset('admin/assets/js/bankaccount.js') }}"></script>
@endpush