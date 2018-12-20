<table id="dtr" class="table table-condensed table-hover">
    <thead>
    <tr>
        <th>Date</th>
        <th>Check Number</th>
        <th>SBA Reference No.</th>
        <th>Branch</th>
        <th>Transaction Code</th>
        <th>Transaction Description</th>
        <th>Debit</th>
        <th>Credit</th>
        <th>Running Balance</th>
    </tr>
    </thead>
    <tbody>
    @foreach($dtr as $key => $d)
        <tr>
            <td>{{date("m/d/Y",strtotime($d->bank_date))}}</td>
            <td>{{$d->check_no}}</td>
            <td>{{$d->sba_ref_no}}</td>
            <td>{{$d->branch}}</td>
            <td>{{$d->trans_code}}</td>
            <td>{{$d->description}}</td>
            <td style="text-align:right">{{$d->type_amount =='AP'?number_format($d->bank_amount,2):''}}</td>
            <td style="text-align:right">{{$d->type_amount =='AR'?number_format($d->bank_amount,2):''}}</td>
            <td style="text-align:right">{{number_format($d->bank_balance,2)}}</td>
        </tr>
    @endforeach
    </tbody>
</table>



<script>
    $("#dtr").DataTable();
</script>