<table class="table table-condensed table-hover" id="check-pdc">
    <thead>
    <tr>
        <th>Description</th>
        <th>Check Date</th>
        <th>Trans ID</th>
        <th>Check No</th>
        <th>Amount</th>

    </tr>
    </thead>
    <tbody>
    {{--{{dd(session()->get('check_data_receive'))}}--}}
    @foreach(session()->get('check_data_receive')->where('check_class',$checkClass) as $check)
        @php

            $d1          = strtotime($date);
            $d2          = strtotime($check->check_date);
        @endphp
        @if($d2 > $d1)
            <tr>
                <td>
                    {{$check->check_class}}
                </td>
                <td>{{date("m/d/Y",strtotime($check->check_date))}}</td>
                <td>{{$check->checksreceivingtransaction_id}}</td>
                <td>{{$check->check_no}}</td>
                <td style="text-align:right">{{number_format($check->check_amount,2)}}</td>
            </tr>
        @endif
    @endforeach
    </tbody>
</table>
<script>
    $("#check-pdc").DataTable();
</script>
