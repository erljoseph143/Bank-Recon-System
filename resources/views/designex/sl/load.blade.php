@foreach($sls as $sl)
    <tr>
        <td>{{ $sl->doc_date->format('F d, Y') }}</td>
        <td>{{ $sl->doc_type }}</td>
        <td>{{ $sl->doc_no }}</td>
        <td>{{ $sl->account_code }}</td>
        <td>{{ $sl->ledger_code }}</td>
        <td>{{ number_format($sl->debit, 2) }}</td>
        <td>{{ number_format($sl->credit, 2) }}</td>
        <td>{{ number_format($sl->balance, 2) }}</td>
        {{--<td>action</td>--}}
    </tr>
@endforeach