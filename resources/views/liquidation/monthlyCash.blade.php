
<table class="table table-condensed table-hover" id="monthly-cash">
    <thead>
    <tr>
        <th>Month</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($month as $key => $mon)
        <tr>
            <td>{{date("F, Y",strtotime($mon->datein))}}</td>
            <td>
                <button class="view-daily btn btn-default btn-xs" data-date="{{date("Y-m",strtotime($mon->datein))}}">
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
        $("#monthly-cash").dataTable({"aaSorting": []});

        $(document).on("click",".view-daily",function(){
            var date = $(this).data('date');
            dailyCash(date);
        });
    </script>
