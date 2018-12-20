<tr class="table-info" data-id="{{ $nextbs->bank_id }}">
    <td>{{ $nextbs->bank_id }}</td>
    <td>{{ $nextbs->bank_date->format('M d, Y') }}</td>
    <td>{{ $nextbs->description }}</td>
    <td>{{ $nextbs->bank_account_no }}</td>
    <td>{{ $nextbs->bank_check_no }}</td>
    <td>{{ number_format($nextbs->bank_amount, 2) }}</td>
    <td>{{ number_format($nextbs->bank_balance, 2) }}</td>
    <td>{{ $nextbs->status }}</td>
    <td>{{ $nextbs->type }}</td>
    <td style="background-color: {{ ($nextbs->bu_unit=="")?'rgba(255,63,63,0.4)':'inherit' }}">
        {{ ($nextbs->bu_unit=="")?'No Business unit':$nextbs->businessunit->bname }}
    </td>
    <td style="background-color: {{ ($nextbs->bu_unit=="")?'rgba(255,63,63,0.4)':'inherit' }}">
        {{ ($nextbs->bu_unit=="")?'No Company':$nextbs->businessunit->company->company }}
    </td>
    <td>{{ $nextbs->date_added->format('M d, Y') }}</td>
    <td>
        {{--<a href="#" class="m-r-10 text-dark prev-dup-bs-btn" data-url="{{ route('adminviewprevbs') }}">--}}
            {{--<i class="fa fa-arrow-circle-o-left"></i>--}}
        {{--</a>--}}
        {{--<a href="#" class="m-r-10 text-dark next-dup-bs-btn">--}}
            {{--<i class="fa fa-arrow-circle-o-right"></i>--}}
        {{--</a>--}}
    </td>
</tr>