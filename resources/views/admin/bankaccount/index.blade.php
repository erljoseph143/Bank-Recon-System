@extends('admin.layouts.table')

@section('crumb')
    <li class='breadcrumb-item'><a href='{{ url('admin/home') }}'>Dashboard</a></li><li class='breadcrumb-item active'>{{ $pagetitle }}</li>
@endsection

@section('top-buttons')
    @if($template == "trash")
        <a href="{{ route('selectedaccounts') }}" class="selected-row" title="restore" onclick="$.Notification.notify('white','top left', '', 'Successfully restored!')"><i class="ion-reply"></i></a>
        <span class="divider"></span>
        <a href="{{ route('selectedaccounts') }}" class="selected-row" title="delete"><i class="ion-trash-b"></i></a>
    @else
        <a href="{{ route('bankaccounts.create') }}" id="add" page-title="addbankaccount" data-toggle="modal" data-target="#modalTable"><i class="ion-plus"></i></a>
        <span class="divider"></span>
        <a href="{{ route('selectedaccounts') }}" title="trash" class="selected-row"><i class="ion-trash-b"></i></a>
    @endif
@endsection

@section('table-nav')
    <button onclick="location.href='{{ url("admin/bankaccounts") }}';" type="button" class="btn @if($template == 'all') btn-primary @else btn-default @endif btn-custom waves-effect w-md waves-light m-b-5">Active &#40;<span class="all-count">{{ $countall }}</span>&#41;</button>
    <button onclick="location.href='{{ url("admin/bankaccounts?p=trash") }}';" type="button" class="btn @if($template == 'trash') btn-primary @else btn-default @endif btn-custom waves-effect w-md waves-light m-b-5">Trash &#40;<span class="trash-count">{{ $counttrash }}</span>&#41;</button>
@endsection

@section('content')
    <table id="demo-foo-filtering" class="table m-b-0 table-bordered toggle-arrow-tiny" data-page-size="15" data-limit-navigation="3">
        <thead>
        <tr>
            <th data-hide="phone" data-sort-ignore="true">
                <div class="checkbox checkbox-primary checkbox-circle">
                    <input id="checkbox-all" type="checkbox">
                    <label for="checkbox-all"></label>
                </div>
            </th>
            <th data-toggle="true"> Bank Code </th>
            <th data-hide="phone"> Bank </th>
            <th data-hide="phone"> Account # </th>
            <th data-hide="phone"> Account Name </th>
            <th data-hide="all"> Company </th>
            <th data-hide="all"> Business Unit </th>
            <th data-hide="all"> Date Added </th>
            <th data-hide="all"> Added By </th>
            <th data-hide="all"> Date Modified </th>
            <th data-hide="all"> Modified By </th>
            <th data-hide="phone, tablet"> Status </th>
            <th data-hide="phone" data-sort-ignore="true"> Action </th>
        </tr>
        </thead>

        <div class="form-inline m-b-20">
            <div class="col-md-6 text-xs-center">
                <div class="form-group">
                    <label class="control-label m-r-5">Bank</label>
                    <select id="" class="demo-foo-filter-status form-control input-sm">
                        <option value="">Show all</option>
                        @foreach($banks as $bank)
                            <option value="{{ $bank->bank }}">{{ $bank->bank }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6 text-center text-right">
                <div class="form-group float-right">
                    <input id="demo-foo-search" type="text" placeholder="Search" class="form-control" autocomplete="on">
                </div>
            </div>
        </div>

        <tbody>

        @foreach($accounts as $account)
            <tr id="code-{{ $account->id }}">
                <td>
                    <div class="checkbox checkbox-primary checkbox-circle">
                        <input id="checkbox-{{ $account->id }}" type="checkbox" class="table-checkbox" value="{{ $account->id }}">
                        <label for="checkbox-{{ $account->id }}"></label>
                    </div>
                </td>
                <td><span class="editable-code-{{ $account->id }}">{{ $account->bankcode->bankno }}</span></td>
                <td><span class="editable-bank-{{ $account->id }}">{{ $account->bank }}</span></td>
                <td><span class="editable-accountno-{{ $account->id }}">{{ $account->accountno }}</span></td>
                <td><span class="editable-accountname-{{ $account->id }}">{{ $account->accountname }}</span></td>
                <td>
                    <span class="editable-company-{{ $account->id }}">
                        @if(empty($account->company->company))
                        @else
                            {{ $account->company->company }}
                        @endif
                    </span>
                </td>
                <td>
                    <span class="editable-bname-{{ $account->id }}">
                        @if(empty($account->businessunit))
                        @else
                            {{ $account->businessunit->bname }}
                        @endif
                    </span>
                </td>
                <td>
                    @if($account->created_at != null)
                        @if($account->created_at->year < 1)
                        @else
                            {{ $account->created_at->format('F d, Y') }}
                        @endif
                    @else
                    @endif
                </td>
                <td>
                    @if(empty($account->added_by))
                    @else
                        @if(is_null($account->user1))
                            <span class="badge badge-danger">user deleted</span>
                        @else
                            {{ $account->user1->firstname . ' ' . $account->user1->lastname }}
                        @endif
                    @endif
                </td>
                <td>
                    @if($account->updated_at->year < 1)
                    @else
                        {{ $account->updated_at->format('F d, Y') }}
                    @endif
                </td>
                <td>
                    <span class="editable-modifiedby-{{ $account->id }}">
                    @if(empty($account->modified_by))
                    @else
                        @if(is_null($account->user2))
                            <span class="badge badge-danger">user deleted</span>
                        @else
                            {{ $account->user2->firstname . ' ' . $account->user2->lastname }}
                        @endif
                    @endif
                    </span>
                </td>
                <td>
                    @if(strtolower($account->status) == 'active')
                        <span class="editable-status-{{ $account->id }} badge label-table badge-success">{{ $account->status }}</span>
                    @elseif(strtolower($account->status) == 'inactive')
                        <span class="editable-status-{{ $account->id }} badge label-table badge-danger">{{ $account->status }}</span>
                    @else
                        <span class="editable-status-{{ $account->id }} badge label-table badge-danger">No Status</span>
                    @endif
                </td>

                <td class="actions">

                    @if($template == 'trash')

                        <a href="{{ route('bankaccounts.update', $account->id) }}" class="on-default open-modal" title="restore" onclick="$.Notification.notify('white','top left', '', 'Successfully restored!')"><i class="fa fa-mail-reply"></i></a>
                        <a href="{{ route('bankaccounts.destroy', $account->id) }}" class="on-default remove-row" title="delete" class="remove-row"><i class="fa fa-trash-o"></i></a>

                    @else
                        <a href="{{ route('bankaccounts.edit', $account->id) }}" class="on-default open-modal" title="edit" data-toggle="modal" data-target="#modalTable"><i class="fa fa-pencil"></i></a>
                        <a href="{{ route('bankaccounts.destroy', $account->id) }}" class="on-default remove-row" title="trash" onclick="$.Notification.notify('white','top left', '', 'Successfully moved to trash!')"><i class="fa fa-trash"></i></a>
                    @endif

                </td>

            </tr>

        @endforeach

        </tbody>

        <tfoot>
        <tr class="active">
            <td colspan="7">
                <div class="text-right">
                    <ul class="pagination pagination-split justify-content-end footable-pagination m-t-10 m-b-0"></ul>
                </div>
            </td>
        </tr>
        </tfoot>

    </table>

@endsection

@section('modal')
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalTable">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span>&times;</span>
                    </button>
                    <h4 class="modal-title" id="modal-title">Generated title</h4>
                </div>
                <div class="modal-body">
                    <form id="form-1437" name="" method="POST" action="{{ route('bankaccounts.index') }}" class="" novalidate="" style="display: inherit; width: 100%;">
                        {{ csrf_field() }}
                        <div class="custom-modal-text">
                            <div class="row" id="modal-content">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="bankcode" class="control-label">Bank Code</label>
                                        <select class="form-control" id="bankcode" title="Bank Code" name="bankcode">
                                            <option value="-1">(Select Bank Code)</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="bankname" class="control-label">Bank Name</label>
                                        <input type="text" class="form-control" id="bankname" name="bankname" placeholder="Input Bank">
                                    </div>
                                    <div class="form-group">
                                        <label for="accountno" class="control-label">Account Number</label>
                                        <input type="text" class="form-control" id="accountno" name="accountno" placeholder="Input Account #">
                                    </div>
                                    <div class="form-group">
                                        <label for="accountname" class="control-label">Account Name</label>
                                        <input type="text" class="form-control" id="accountname" name="accountname" placeholder="Input Account Name">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="currency" class="control-label">Currency</label>
                                        <select class="form-control" id="currency" title="Currency" name="currency">
                                            <option value="-1">(Select Currency)</option>
                                        </select>
                                    </div>
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
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="remarks" class="control-label">Remarks</label>
                                        <input type="text" class="form-control" id="remarks" name="remarks" placeholder="Input Remarks">
                                    </div>
                                    <div class="form-group">
                                        <label for="branchname" class="control-label">Branch Name</label>
                                        <input type="text" class="form-control" id="branchname" name="branchname" placeholder="Input Branch Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="address" class="control-label">Address</label>
                                        <input type="text" class="form-control" id="address" name="address" placeholder="Input Address">
                                    </div>
                                    <div class="form-group">
                                        <label for="contact" class="control-label">Contact Person</label>
                                        <input type="text" class="form-control" id="contact" name="contact" placeholder="Input Contact Person">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="save" type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                            <input type="hidden" id="code" name="code" value="0">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="{{ asset('admin/minton/plugins/footable/css/footable.core.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/assets/css/main.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('admin/minton/plugins/footable/js/footable.all.min.js') }}"></script>
    <script src="{{ asset('admin/minton/assets/pages/jquery.footable.js') }}"></script>
    <script src="{{ asset('admin/assets/js/bankaccountfunction.js') }}"></script>
    <script src="{{ asset('admin/assets/js/bankaccount.js') }}"></script>
@endpush