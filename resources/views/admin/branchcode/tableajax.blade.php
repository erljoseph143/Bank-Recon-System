@foreach($branchcodes as $branchcode)
<tr>
<td>{{ $branchcode->bank_code }}</td>
<td>{{ $branchcode->branch_name }}</td>
<td>{{ $branchcode->bank->bankname }}</td>
{{--<td>{{ $branchcode->created_at->format('F d Y') }}</td>--}}
<td>{{ $branchcode->creator->firstname }}</td>
</tr>
@endforeach