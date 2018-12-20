@foreach($bank_accounts as $bank_account)
    <tr>
        <td>{{ $bank_account->bank }}</td>
        <td>{{ $bank_account->accountno }}</td>
        <td>{{ $bank_account->accountname }}</td>
        <td>{{ $bank_account->businessunit->bname }}</td>
        <td>{{ $bank_account->company->company }}</td>
    </tr>
    
@endforeach