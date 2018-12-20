
    <table class="table table-condensed table-hover" id="monthlydep">
        <thead>
            <tr>
                <th>Month</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($month as $key => $mon)
                <tr>
                    <td>{{date("F ,Y",strtotime($mon->sales_date))}}</td>
                    <td>
                        <button class="view-daily btn btn-default btn-xs" data-date="{{date("Y-m",strtotime($mon->sales_date))}}">
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
    @if(!isset($login_user))
    <script>
            $(".title-page").text('');
            $(".title-page").text('{{$content_title}}');
            $("#monthlydep").dataTable({"aaSorting": []});


    </script>
    @endif
@push('scripts')
    <script>
        $(".title-page").text('{{$content_title}}');
        $("#monthlydep").dataTable({"aaSorting": []});

        $(document).on("click",".view-daily",function(){
            var date = $(this).data('date');
            dailyDep(date);
        });
    </script>
@endpush