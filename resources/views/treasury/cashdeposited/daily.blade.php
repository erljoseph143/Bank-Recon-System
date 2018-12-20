
<table class="table table-condensed table-hover" id="dailydep">
    <thead>
    <tr>
        <th>Sales Date</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($dailydep as $key => $day)
        <tr>
            <td>{{date("F j,Y",strtotime($day->sales_date))}}</td>
            <td>
                <button class="view-dep btn btn-default btn-xs" data-date="{{date("Y-m-d",strtotime($day->sales_date))}}">
                    <i class="icon icon-magnifier"></i>
                    View
                </button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>


<script>
    $("#dailydep").dataTable({"aaSorting": []});

    $(".view-dep").click(function(){
        var date = $(this).data('date');
        depositedCash(date);
    });

    $(".title-page").text('Cash Receive for the month of {{date("F, Y",strtotime($date))}}');

    $(".monthly-deposited").click(function(){
        cashDeposited();
    });

</script>
