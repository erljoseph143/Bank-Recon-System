
<div style="border:1px solid black;padding:1px;width:976px;font-size:18px;display:block">
            <p style="text-align:center;">
                {{\Illuminate\Support\Facades\Auth::user()->businessunit->company->company}}
            </p>
            <p style="text-align: center;margin: -18px">
                {{\Illuminate\Support\Facades\Auth::user()->businessunit->bname}} TREASURY
            </p>
            <p style="text-align: center">
                CASH PULL OUT FORM
            </p>

        <div style="width:25%;display:inline-table;text-align: right">Name:</div>
        <div style="width:25%;display:inline-table;border-bottom:1px solid black">{{$cpo->user->firstname ." " . $cpo->user->lastname}}</div>
        <div style="width:10%;display:inline-table">TCPOF #:</div>
        <div style="width:35%;display:inline-table;border-bottom:1px solid black">{{$cpo->tcpof_no}}</div>
    <br/>
    <br/>
        <div style="width:25%;display:inline-table;text-align: right">Department/Section:</div>
        <div style="width:25%;display:inline-table;border-bottom:1px solid black">{{$cpo->department->dep_name}}</div>
        <div style="width:10%;display:inline-table">Date:</div>
        <div style="width:35%;display:inline-table;border-bottom:1px solid black">{{date("m/d/Y",strtotime($cpo->pull_out_date))}}</div>
    <br/>
    <br/>

        <div style="width:25%;display:inline-table;text-align: right">Amount in words:</div>
        <div style="width:71%;display:inline-table;border-bottom:1px solid black">{{$cpo->amt_words}}</div>
    <br/>
    <br/>
        <div style="width:25%;display:inline-table;text-align: center"></div>
        <div style="width:71%;display:inline-table;text-align: center;border-bottom:1px solid black">( ₱ {{number_format($cpo->amount_edited,2)}})</div>
    <br/>
    <br/>
        <div style="width:25%;display:inline-table;text-align: right">Purpose:</div>
        <div style="width:71%;display:inline-table;text-align: left;border-bottom:1px solid black">{{ucfirst($cpo->purposes->description)}}</div>
    <br/>
    <br/>
    <br/>
        <div style="width:25%;display:inline-table;text-align: right">Requested By:</div>
        <div style="width:71%;display:inline-table;text-align: center;border-bottom:1px solid black">{{$cpo->user->firstname ." " . $cpo->user->lastname}}</div>
        <div style="width:25%;display:inline-table;text-align: center"></div>
        <div style="width:71%;display:inline-table;text-align: center">Printed Name and Signature</div>
    <br/>
    <br/>
        <div style="width:25%;display:inline-table;text-align: right">Approved By:</div>
        <div style="width:71%;display:inline-table;text-align: center;border-bottom:1px solid black">{{$cpo->approveby->firstname ." " . $cpo->approveby->lastname}}</div>
        <div style="width:25%;display:inline-table;text-align: center"></div>
        <div style="width:71%;display:inline-table;text-align: center">Printed Name and Signature</div>
    <br/>
    <br/>
        <div style="width:25%;display:inline-table;text-align: right">Released By:</div>
        <div style="width:71%;display:inline-table;text-align: center;border-bottom:1px solid black">{{$cpo->release_by!='' ? $cpo->releaseby->firstname ." " . $cpo->releaseby->lastname:''}}</div>
        <div style="width:25%;display:inline-table;text-align: center"></div>
        <div style="width:71%;display:inline-table;text-align: center">Printed Name and Signature</div>
    <br/>
    <br/>
        <div style="width:25%;display:inline-table;text-align: right">Note:</div>
        <div style="width:71%;display:inline-table;text-align: justify">
            The amount borrowed must be refunded with in five (5) days, failure to do so would mean insubordination and accountable for it.
        </div>
    <br/>
    <br/>
</div>


<div style="border:1px solid black;padding:1px;width:976px;font-size:18px;display:block;margin-top:80px">
            <p style="text-align:center;">
                {{\Illuminate\Support\Facades\Auth::user()->businessunit->company->company}}
            </p>
            <p style="text-align: center;margin: -18px">
                {{\Illuminate\Support\Facades\Auth::user()->businessunit->bname}} TREASURY
            </p>
            <p style="text-align: center">
                CASH PULL OUT FORM
            </p>

        <div style="width:25%;display:inline-table;text-align: right">Name:</div>
        <div style="width:25%;display:inline-table;border-bottom:1px solid black">{{$cpo->user->firstname ." " . $cpo->user->lastname}}</div>
        <div style="width:10%;display:inline-table">TCPOF #:</div>
        <div style="width:35%;display:inline-table;border-bottom:1px solid black">{{$cpo->tcpof_no}}</div>
    <br/>
    <br/>
        <div style="width:25%;display:inline-table;text-align: right">Department/Section:</div>
        <div style="width:25%;display:inline-table;border-bottom:1px solid black">{{$cpo->department->dep_name}}</div>
        <div style="width:10%;display:inline-table">Date:</div>
        <div style="width:35%;display:inline-table;border-bottom:1px solid black">{{date("m/d/Y",strtotime($cpo->pull_out_date))}}</div>
    <br/>
    <br/>

        <div style="width:25%;display:inline-table;text-align: right">Amount in words:</div>
        <div style="width:71%;display:inline-table;border-bottom:1px solid black">{{$cpo->amt_words}}</div>
    <br/>
    <br/>
        <div style="width:25%;display:inline-table;text-align: center"></div>
        <div style="width:71%;display:inline-table;text-align: center;border-bottom:1px solid black">( ₱ {{number_format($cpo->amount_edited,2)}})</div>
    <br/>
    <br/>
        <div style="width:25%;display:inline-table;text-align: right">Purpose:</div>
        <div style="width:71%;display:inline-table;text-align: left;border-bottom:1px solid black">{{ucfirst($cpo->purposes->description)}}</div>
    <br/>
    <br/>
    <br/>
        <div style="width:25%;display:inline-table;text-align: right">Requested By:</div>
        <div style="width:71%;display:inline-table;text-align: center;border-bottom:1px solid black">{{$cpo->user->firstname ." " . $cpo->user->lastname}}</div>
        <div style="width:25%;display:inline-table;text-align: center"></div>
        <div style="width:71%;display:inline-table;text-align: center">Printed Name and Signature</div>
    <br/>
    <br/>
        <div style="width:25%;display:inline-table;text-align: right">Approved By:</div>
        <div style="width:71%;display:inline-table;text-align: center;border-bottom:1px solid black">{{$cpo->approveby->firstname ." " . $cpo->approveby->lastname}}</div>
        <div style="width:25%;display:inline-table;text-align: center"></div>
        <div style="width:71%;display:inline-table;text-align: center">Printed Name and Signature</div>
    <br/>
    <br/>
        <div style="width:25%;display:inline-table;text-align: right">Released By:</div>
        <div style="width:71%;display:inline-table;text-align: center;border-bottom:1px solid black">{{$cpo->release_by!='' ? $cpo->releaseby->firstname ." " . $cpo->releaseby->lastname:''}}</div>
        <div style="width:25%;display:inline-table;text-align: center"></div>
        <div style="width:71%;display:inline-table;text-align: center">Printed Name and Signature</div>
    <br/>
    <br/>
        <div style="width:25%;display:inline-table;text-align: right">Note:</div>
        <div style="width:71%;display:inline-table;text-align: justify">
            The amount borrowed must be refunded with in five (5) days, failure to do so would mean insubordination and accountable for it.
        </div>
    <br/>
    <br/>
</div>
