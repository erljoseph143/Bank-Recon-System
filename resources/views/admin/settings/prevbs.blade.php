<tr class="table-success" data-id="{{ $prevbs->bank_id }}">
    <td>{{ $prevbs->bank_id }}</td>
    <td>{{ $prevbs->bank_date->format('M d, Y') }}</td>
    <td>{{ $prevbs->description }}</td>
    <td>{{ $prevbs->bank_account_no }}</td>
    <td>{{ $prevbs->bank_check_no }}</td>
    <td>{{ number_format($prevbs->bank_amount, 2) }}</td>
    <td>{{ number_format($prevbs->bank_balance, 2) }}</td>
    <td>{{ $prevbs->status }}</td>
    <td>{{ $prevbs->type }}</td>
    <td style="background-color: {{ ($prevbs->bu_unit=="")?'rgba(255,63,63,0.4)':'inherit' }}">
        {{ ($prevbs->bu_unit=="")?'No Business unit':$prevbs->businessunit->bname }}
    </td>
    <td style="background-color: {{ ($prevbs->bu_unit=="")?'rgba(255,63,63,0.4)':'inherit' }}">
        {{ ($prevbs->bu_unit=="")?'No Company':$prevbs->businessunit->company->company }}
    </td>
    <td>{{ $prevbs->date_added->format('M d, Y') }}</td>
    <td>
        {{--<a href="#" class="m-r-10 text-dark prev-dup-bs-btn" data-url="{{ route('adminviewprevbs') }}">--}}
            {{--<i class="fa fa-arrow-circle-o-left"></i>--}}
        {{--</a>--}}
        {{--<a href="#" class="m-r-10 text-dark next-dup-bs-btn">--}}
            {{--<i class="fa fa-arrow-circle-o-right"></i>--}}
        {{--</a>--}}
    </td>
</tr>