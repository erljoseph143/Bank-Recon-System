@extends('admin.layouts.table')

@section('crumb')
    <li class='breadcrumb-item'><a href='{{ route('adminhome') }}'>Dashboard</a></li>
    <li class='breadcrumb-item active'><a href="{{ route('bank-statements.index') }}">Bank Statements</a></li>
{{--    <li class='breadcrumb-item active'><a href="{{ route('bsusers',$id) }}">Users uploaded</a></li>--}}
    <li class='breadcrumb-item active'><a href="{{ route('bsaccounts',[$id]) }}">Bank Accounts</a></li>
    <li class='breadcrumb-item active'>{{ $pagetitle }}</li>
@endsection

@section('badge')
@endsection

@section('subtitle')
    <p class="text-muted inline m-b-10 font-10">Bank Statements under {{ $bu->bname }} business unit with bank account details {{ $accountname->bank . '-' . $accountname->accountno . '-' . $accountname->accountname }}</p>
@endsection

@section('content')
    <table id="months" class="table table-hover m-b-0 table-bordered toggle-arrow-tiny" data-page-size="8" data-limit-navigation="3">
        <thead>
        <tr>
            <th data-toggle="true"> Check Date </th>
            <th data-hide="phone"> Date Uploaded </th>
            <th data-hide="phone" data-sort-ignore="true"> Action </th>
        </tr>
        </thead>
        <tbody>
        @foreach($months as $month)
            <tr>
                <td>{{ $month->bank_date->format('F Y') }}</td>
                {{--<td>{{ $month->user1->firstname . ' ' . $month->user1->lastname }}</td>--}}
                <td>{{ $month->date_added->format('F d, Y') }}</td>
                <td class="actions">
                    <a href="{{ route('bstrashmonth',['bu'=>$id,'account'=>$account,'code'=>$code,'year'=>$month->bank_date->format('Y'),'month'=>$month->bank_date->format('m')]) }}" title="trash" class="btn waves-effect remove-row" onclick="$.Notification.notify('white','top left', '', 'Successfully move to trash!')"><i class="fa fa-trash font-15"></i></a>
                    <a href="{{ route('bstrashmonth',['bu'=>$id,'account'=>$account,'code'=>$code,'year'=>$month->bank_date->format('Y'),'month'=>$month->bank_date->format('m')]) }}" title="restore" class="btn waves-effect remove-row" onclick="$.Notification.notify('white','top left', '', 'Successfully restore!')"><i class="fa fa-mail-reply font-15"></i></a>
                    <a href="{{ route('bsview',[$id, $account, $code, $month->bank_date->format('Y'), $month->bank_date->format('m')]) }}" class="btn waves-effect on-default view-checks" title="view"><i class="fa fa-television font-15"></i></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/minton/plugins/datatables/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/minton/plugins/datatables/responsive.bootstrap4.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('admin/minton/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/minton/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/bankstatement_month.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function (e) {
            $('#months').DataTable();
        });
    </script>
@endpush