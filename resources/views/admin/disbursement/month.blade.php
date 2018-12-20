@extends('admin.layouts.main')

@section('crumb')
    <li class='breadcrumb-item'><a href='{{ route('adminhome') }}'>Dashboard</a></li>
    <li class='breadcrumb-item active'><a href="{{ route('admindisburse') }}">Disbursements</a></li>
    <li class='breadcrumb-item active'><a href="{{ route('disburselistusers',$id) }}">Users uploaded</a></li>
    <li class='breadcrumb-item active'><a href="{{ route('disburselistaccounts', [$id, $userid]) }}">Bank Accounts</a></li>
    <li class='breadcrumb-item active'>{{ $pagetitle }}</li>
@endsection

@section('badge')
    <span class="badge badge-success">BU : {{ $bu->bname }}</span> <span class="badge badge-purple">Uploader : {{ $user->firstname . ' ' . $user->lastname }}</span> <span class="badge badge-danger">Account : {{ $accountname->bank . '-' . $accountname->accountno . '-' . $accountname->accountname }}</span>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="portlet">
            <div class="portlet-heading portlet-default">
                <h3 class="portlet-title">List of {{ $doctitle }} @yield('badge')</h3>
                <div class="clearfix"></div>
            </div>
            <div class="panel-collapse collapse show">
                <div class="portlet-body">
                    <table id="monthlist" class="table m-b-0 table-bordered toggle-arrow-tiny" data-page-size="8" data-limit-navigation="3">
                        <thead>
                        <tr>
                            <th data-toggle="true"> CV Date </th>
                            <th data-hide="phone"> Uploaded By </th>
                            <th data-hide="phone"> Date Uploaded </th>
                            <th data-hide="phone" data-sort-ignore="true"> Action </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($months as $month)
                            <tr>
                                <td>
                                    @if($month->cv_date != null)
                                        {{ $month->cv_date->format('F Y') }}
                                    @endif
                                </td>
                                <td>{{ $month->user1->firstname . ' ' . $month->user1->lastname }}</td>
                                <td>
                                    @if($month->date_upload != null)
                                        {{ $month->date_upload->format('F d, Y') }}
                                    @endif
                                </td>
                                <td class="actions">
                                    <a href="{{ route('deletemonth',['bu'=>$id,'userid'=>$userid,'account'=>$account,'code'=>$code,'year'=>$month->cv_date->format('Y'),'month'=>$month->cv_date->format('m')]) }}" title="trash" class="remove-row" onclick="$.Notification.notify('white','top left', '', 'Successfully move to trash!')"><i class="fa fa-trash"></i></a>
                                    <a href="{{ route('deletemonth',['bu'=>$id,'userid'=>$userid,'account'=>$account,'code'=>$code,'year'=>$month->cv_date->format('Y'),'month'=>$month->cv_date->format('m')]) }}" title="restore" class="remove-row" onclick="$.Notification.notify('white','top left', '', 'Successfully restore!')"><i class="fa fa-mail-reply"></i></a>
                                    <a href="{{ route('disburselists', [$id, $userid, $account, $code, $month->cv_date->format('Y'), $month->cv_date->format('m'), 'p'=>'all']) }}" class="on-default view-checks" title="view"><i class="fa fa-eye"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
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
    <script src="{{ asset('admin/minton/plugins/notifyjs/dist/notify.min.js') }}"></script>
    <script src="{{ asset('admin/minton/plugins/notifications/notify-metro.js') }}"></script>
    <script src="{{ asset('admin/minton/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/minton/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/disbursement_month.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function (e) {
            $('#monthlist').DataTable();
        });
    </script>
@endpush