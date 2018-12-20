<table class="table table-bordered table-striped table-hover flip-content " id="sample_editable_1">
    <thead>
    <tr>
        <th>Sale Date</th>
        <th>Deposit Date</th>
        <th>Section</th>
        <th>DS Number</th>
        <th>Amount</th>

    </tr>
    </thead>
    <tbody>

    <tr>
        <td>{{ $sm->sales_date->format('M d, Y') }}</td>
        <td>{{ $sm->deposit_date->format('M d, Y') }}</td>
        <td>{{$sm->cashLog->description}} </td>
        <td>{{$sm->ds_no}}</td>
        <td style="text-align: right">{{number_format($sm->amount_edited,2)}}</td>
    </tr>
    @foreach($adjustment as $adj)
        @if($adj[1]!='Due Checks')
            <tr>
                <td style="text-align: right;color:red" colspan="4">{{ $adj[1] }}</td>
                <td style="text-align: right;color:red">{{number_format($adj[2],2)}}</td>
            </tr>
        @endif
    @endforeach
    <tr>
        <td style="text-align: right" colspan="4">Total:</td>
        <td style="text-align: right">{{number_format($totalAmount,2)}}</td>
    </tr>
    </tbody>
</table>