<button class="btn btn-danger pull-right print-data">
    <i class="glyphicon glyphicon-print"></i>
    Print Data
</button>
<br/>
<br/>
<table class="table table-bordered table-striped table-hover flip-content " id="sample_editable_1">
    <thead>
    <tr>
        <th>Sale Date</th>
        <th>Deposit Date</th>
        <th>Section</th>
        <th>DS Number</th>
        <th>Amount</th>
        <th>Controls</th>
    </tr>
    </thead>
    <tbody>
    @foreach($cashdep as $d)
        <tr>
            <td>{{ $d->sales_date->format('M d, Y') }}</td>
            <td>{{ $d->deposit_date->format('M d, Y') }}</td>
            <td>{{$d->cashLog->description}} </td>
            <td>{{$d->ds_no}}</td>
            @php
                if(preg_match('/supermarket/',strtolower($d->cashLog->description))>0):
                 $amount = $d->amount_edited - ($adjsum + $cposum);
                 $total +=$amount;
                else:
                  $amount = $d->amount_edited;
                  $total  += $amount;
                endif;
            @endphp
            <td style="text-align: right">{{number_format($amount,2)}}</td>
            <td>
                @if(preg_match('/supermarket/',strtolower($d->cashLog->description))>0)
                    <button class="btn btn-sm view-details" id="{{$d->id}}" data-date="{{date("Y-m-d",strtotime($d->sales_date))}}">
                        <i class="fa fa-folder-open"></i>
                        View Details
                    </button>
                @endif
            </td>
        </tr>
    @endforeach
    <tr>
        <td colspan="4" style="text-align:right"> Total: </td>
        <td style="text-align:right">{{number_format($total,2)}}</td>
        <td></td>
    </tr>

    </tbody>
</table>
{{$d->sales_date}}

<script>
   // document.addEventListener('DOMContentLoaded',function(){
        $(".view-details").click(function(){
            var id = $(this).attr('id');
            var date = $(this).data('date');

            BootstrapDialog.show({
                title:'Supermarket Details',
                message:$('<div></div>').load('treasury/viewSMDetails/'+id+'/'+date),
                size:BootstrapDialog.SIZE_WIDE

            });
        });
   // });
   $(".monthly-deposited").click(function(){
       cashDeposited();
   });

   $(".daily-dep-list").click(function(){
       var date = $(this).data('date');
       dailyDeposited(date);
   });

   $(".print-data").click(function(){
       BootstrapDialog.show({
           title:'Data Printing',
           message:$('<div></div>').load('treasury/printAll/{{date("Y-m-d",strtotime($d->sales_date))}}'),
           size:BootstrapDialog.SIZE_WIDE
       });
   });
</script>


