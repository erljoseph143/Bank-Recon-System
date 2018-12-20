<div class="col-md-12">
    <div class="col-md-4">
        <label style="color:green;">Cash Pull Out Date:  </label> <label class="date-text"></label>
    </div>
    <div class="col-md-4">
        <label style="color:green">TCPOF #:  </label> <label class="tcpof-text"></label>
    </div>

    <div class="col-md-4" style="margin-left: -5%;">
        <label style="color:green;">Amount Pull Out:  </label> <label class="amount-text"></label>
    </div>

</div>
<table id="ledger-data" class="table table-condensed table-hover" >
    <thead>
        <tr>
            <th style="text-align: center;">Check No</th>
            <th style="text-align: center;">Check Amount</th>
            <th>Balance</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="2" style="text-align:center"></td>
            <td  style="text-align:center">{{number_format($cpo_ledger[0]->cpo_amount,2)}}</td>
        </tr>
    @php
        $balance = $cpo_ledger[0]->cpo_amount;
    @endphp
    @foreach($cpo_ledger as $key => $data)
        @php
            $balance -= $data->check_amount;
        @endphp
        <tr>
            <td>{{$data->check_no}}</td>
            <td style="text-align: center;color:red">{{number_format($data->check_amount,2)}}</td>
            <td style="text-align: center">{{number_format($balance,2)}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<script>
   // $("#ledger-data").DataTable({"aaSorting": []});
    $(".date-text").text('{{date("m/d/Y",strtotime($data->cpo_date))}}');
    $(".tcpof-text").text('{{$data->tcpof->tcpof_no}}');
    $(".amount-text").text('{{number_format($data->cpo_amount,2)}}');
</script>