
<table class="table table-condensed table-hover" id="monthlydeposited">
    <thead>
    <tr>
        <th>Month</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($cashdep as $key => $mon)
        <tr>
            <td>{{date("F ,Y",strtotime($mon->sales_date))}}</td>
            <td>
                <button class="view-daily-deposited btn btn-default btn-xs" data-date="{{date("Y-m",strtotime($mon->sales_date))}}">
                    <i class="icon icon-magnifier"></i>
                    View
                </button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>


{{--<div class="pull-right">--}}
{{--{{$month->appends(request()->except('page'))->links()}}--}}
{{--</div>--}}
{{--<div class="clearfix"></div>--}}


    <script>
        $(".title-page").text('');
        $(".title-page").text('{{$content_title}}');
        $("#monthlydeposited").dataTable({"aaSorting": []});

        $(".view-daily-deposited").click(function(){
            var date = $(this).data('date');
            dailyDeposited(date);
        });
    </script>
