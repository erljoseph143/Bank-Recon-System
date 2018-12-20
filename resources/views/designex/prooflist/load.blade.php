@foreach($prooflists as $prooflist)
    <tr>
        <td>{{ $prooflist->check_date->format('F d, Y') }}</td>
        <td>{{ $prooflist->check_no }}</td>
        <td>{{ $prooflist->check_bank }}</td>
        <td>{{ $prooflist->doc_no }}</td>
        <td>{{ $prooflist->doc_date->format('F d, Y') }}</td>
        <td>{{ $prooflist->payee }}</td>
        <td>{{ number_format($prooflist->amount,2) }}</td>
    </tr>
@endforeach