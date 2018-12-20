@foreach($accounts as $account)
    <tr id="account-{{ $account->id }}">
        <td>{{ $account->account_code }}</td>
        <td>{{ $account->account_name }}</td>
        <td>{{ $account->normal_balance }}</td>
    </tr>
@endforeach