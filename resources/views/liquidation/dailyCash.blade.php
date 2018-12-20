<table id="daily-sales" class="table table-condensed table-hover">
    <thead>
    <tr>
        <th>Daily</th>
        <th>Action</th>

    </tr>
    </thead>
    <tbody>
    @foreach($daily as $key => $day)

        <tr>
            <td>{{date("F d, Y",strtotime($day->datein))}}</td>
            <td>
                <button class="btn btn-default btn-xs view-cash" data-date="{{$day->datein}}">
                    <i class="glyphicon glyphicon-zoom-in"></i>
                    View
                </button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<script>
    $("#daily-sales").dataTable({aaSorting:[]});
    $(".title-page").text('{{$content_title}}');

    $(".view-cash").click(function(){
        var date = $(this).data('date');
        viewCash(date);
    });

    $(".monthly-list-cash").click(function(){
        monthlyCash();
    });
</script>