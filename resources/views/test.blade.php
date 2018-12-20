<table border="1" style="border-collapse: collapse">
    <tr>
        @php
            $onek  = $denno->sum('1k_q');
            $d5h   = $denno->sum('5h_q');
            $d2h   = $denno->sum('2h_q');
            $d1h   = $denno->sum('1h_q');
            $d50p  = $denno->sum('50p_q');
            $d20p  = $denno->sum('20p_q');
            $d10p  = $denno->sum('10p_q');
        @endphp
       <td>1000</td> <td>{{1000*$onek}}</td>
    </tr>
    <tr>
        <td>500</td>
        <td>{{500*$d5h}}</td>
    </tr>
    <tr>
        <td>200</td>
        <td>{{200*$d2h}}</td>
    </tr>
    <tr>
        <td>100</td>
        <td>{{100*$d1h}}</td>
    </tr>
    <tr>
        <td>50</td>
        <td>{{50*$d50p}}</td>
    </tr>
    <tr>
        <td>20</td>
        <td>{{20*$d20p}}</td>
    </tr>
    <tr>
        <td>10</td>
        <td>{{10*$d10p}}</td>
    </tr>
    <tr>
        <td>Coins</td>
        <td>{{$denno->sum('coins')}}</td>
    </tr>
</table>