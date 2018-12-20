@foreach($duplicatebs as $duplicateb)
    <tr data-id="{{ $duplicateb->bank_id }}" style="color: {{ (is_null($duplicateb->deleted_at))?"black":"red" }}">
        <td>{{ $duplicateb->bank_id }}</td>
        <td>{{ $duplicateb->bank_date->format('M d, Y') }}</td>
        <td>{{ $duplicateb->description }}</td>
        <td>{{ $duplicateb->bank_account_no }}</td>
        <td>{{ $duplicateb->bank_check_no }}</td>
        <td>{{ number_format($duplicateb->bank_amount, 2) }}</td>
        <td>{{ number_format($duplicateb->bank_balance, 2) }}</td>
        <td>{{ $duplicateb->status }}</td>
        <td>{{ $duplicateb->type }}</td>
        <td style="background-color: {{ ($duplicateb->bu_unit=="")?'rgba(255,63,63,0.4)':'inherit' }}">
            {{ ($duplicateb->bu_unit=="")?'No Business unit':$duplicateb->businessunit->bname }}
        </td>
        <td style="background-color: {{ ($duplicateb->bu_unit=="")?'rgba(255,63,63,0.4)':'inherit' }}">
            {{ ($duplicateb->bu_unit=="")?'No Company':$duplicateb->businessunit->company->company }}
        </td>
        <td>{{ $duplicateb->date_added->format('M d, Y') }}</td>
        <td>
            <a href="#" class="m-r-10 text-dark prev-dup-bs-btn" data-url="{{ route('adminviewprevbs') }}">
                <i class="fa fa-arrow-circle-o-left"></i>
            </a>
            <a href="#" class="m-r-10 text-dark next-dup-bs-btn" data-url="{{ route('adminviewnextbs') }}">
                <i class="fa fa-arrow-circle-o-right"></i>
            </a>
            <a href="#" class="text-danger trash-dup-bs-btn" data-action="{{ (is_null($duplicateb->deleted_at))?"trash":"restore" }}" data-url="{{ route('admintrashduplicatebs') }}">
                <i class="fa {{ (is_null($duplicateb->deleted_at))?"fa-trash-o":"fa-undo" }}"></i>

            </a>
        </td>
    </tr>
@endforeach