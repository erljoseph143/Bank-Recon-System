<table class="table table-bordered table-hover dt-responsive no-wrap checks-table-detail" width="100%">
    <thead>
    <tr>
        <th>Date Posted</th>
        <th>Check #</th>
        <th>Amount</th>
        <th>Balance</th>
        <th>Date Uploaded</th>
        <th>Uploaded By</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th>Date Posted</th>
        <th>Check #</th>
        <th>Amount</th>
        <th>Balance</th>
        <th></th>
        <th></th>
    </tr>
    </tfoot>
    <tbody>
    @foreach ($match_checks as $key => $check)
    <tr>
        <td>{{ $check->date_posted->format('M d, Y') }}</td>
        <td>{{ $check->check_no }}</td>
        <td>{{ $check->trans_amount }}</td>
        <td>{{ $check->balance }}</td>
        <td>{{ $check->date_uploaded->format('M d, Y') }}</td>
        <td>{{ $check->user1->firstname . ' ' . $check->user1->lastname }}</td>
    </tr>
    @endforeach
    </tbody>
</table>