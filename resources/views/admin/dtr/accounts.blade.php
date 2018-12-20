<option value="">( Select Bank Account )</option>
@foreach($accounts as $account)

    <option value="{{ $account->bank_account_no . '|' . $request->id }}">{{ $account->bank. ' ' . $account->accountno . ' ' . $account->accountname }}</option>

@endforeach