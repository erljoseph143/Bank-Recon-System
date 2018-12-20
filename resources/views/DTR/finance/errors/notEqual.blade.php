<table class="table table-bordered table-striped">
    {{--{{dd(session()->get('notbalance'))}}--}}
    @php
        $total      = 0;
        $data       = base64_decode($data);
        $exp        = explode("/",$data);
        $debit      = $exp[0];
        $credit     = $exp[1];
        $balance    = $exp[2];
        $currentbal = $exp[3];
    @endphp
    <tr style="color:red">
        <td colspan="3">Error in Uploading not balance Data</td>
    </tr>
    <tr>
        <td>Current Balance Uploaded</td>
        <td></td>
        <td style="text-align:right">{{number_format($currentbal,2)}}</td>
    </tr>
    <tr>
        <td>Debit</td>
        <td style="text-align:center"> - </td>
        <td style="text-align:right">{{$debit!=''?number_format($debit,2):''}}</td>
    </tr>
    <tr>
        <td>Credit</td>
        <td style="text-align:center"> + </td>
        <td style="text-align:right">{{$credit!=''?number_format($credit,2):''}}</td>
    </tr>
    <tr>
        <td>Total</td>
        <td></td>
        <td style="text-align:right">{{$debit!='' ? number_format($total = $currentbal-$debit,2) : number_format($total = $currentbal+$credit,2) }}</td>
    </tr>
    <tr>
        <td>Your file running Balance</td>
        <td style="text-align:center"> - </td>
        <td style="text-align:right">{{number_format($balance,2)}}</td>
    </tr>
    <tr>
        <td>Balance Not Equal</td>
        <td></td>
        <td style="text-align:right">{{number_format($total - $balance,2)}}</td>
    </tr>
</table>