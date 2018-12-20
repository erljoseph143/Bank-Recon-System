@foreach($ledgers as $ledger)
    <tr id="trans-type-{{ $ledger->id }}">
        <td class="code-{{ $ledger->id }}">{{ $ledger->ledger_code }}</td>
        <td class="name-{{ $ledger->id }}">{{ $ledger->ledger_name }}</td>
        <td>
            <button data-id="{{ $ledger->id }}" data-toggle="modal" data-target="#myModal" class="btn btn-xs btn-primary btn-icon-anim btn-circle edit-ledger">
                 <i class="zmdi zmdi-edit"></i>
            </button>
        </td>
    </tr>
@endforeach