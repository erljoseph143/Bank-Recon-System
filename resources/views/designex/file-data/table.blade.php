@foreach($lists as $key => $list)
    <tr>
{{--        <td><a href="#">{{ $list->check_bank }}</a></td>--}}
{{--        <td><a href="#">{{ $list->doc_date->format('F Y') }}</a></td>--}}
        <td>{{ $list->docs_date }} </td>
        <td>
            <a href="javascript:void(0)" class="text-inverse pr-10" title="view bank statement" data-toggle="tooltip"><i class="zmdi zmdi-view-column txt-danger"></i></a>
            <a href="javascript:void(0)" class="text-inverse pr-10" title="overview" data-toggle="tooltip"><i class="zmdi zmdi-eye txt-success"></i></a>
            <a href="{{ route('file-data.store', ['code'=>$list->ledger_code, 'doc_date'=>$list->docs_date, 'bank'=>$list->check_bank]) }}" data-value="{{ $list->docs_date }}" class="text-inverse match-data pr-10" title="match data" data-toggle="tooltip"><i class="zmdi zmdi-swap txt-info"></i></a>
            <a href="{{ route('file-data.create', ['code'=>$list->ledger_code, 'doc_date'=>$list->docs_date]) }}" data-value="{{ $list->docs_date }}" class="text-inverse generate-data pr-10" title="generate report" data-toggle="tooltip"><i class="zmdi zmdi-cloud-download txt-warning"></i></a>
            <a href="{{ route('file-data.show', ['code'=>$list->ledger_code, 'doc_date'=>$list->docs_date]) }}" data-value="{{ $list->docs_date }}" class="text-inverse refresh-data" title="clear matching" data-toggle="tooltip"><i class="zmdi zmdi-refresh txt-success"></i></a>
        </td>

    </tr>
@endforeach