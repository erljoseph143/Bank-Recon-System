<ul>
    @foreach($arDay as $key => $day)
        @foreach($day as $key2 => $d)
            @php
                $d<10?$dayof="0$d":$dayof=$d;
                $dateNew = "$dateYear-$dateMonth-$dayof";
            @endphp
            <li class="{{strtotime($curDate)==strtotime($dateNew)?'grey':"$dateNew"}} date_cell" style="{{$arStyle[$key][$key2]}}">
                <span>
                    {{$d}}
                </span>
                @if(trim($allDay[$key][$key2])!='void' and trim($allDay[$key][$key2])!=0)
                <div class="record" data-date="{{$dateNew}}" style="margin-top:30px;color:blue">
                    {{$allDay[$key][$key2]}}
                    <br>
                    Records
                </div>
                @endif
            </li>
        @endforeach
    @endforeach
</ul>

<script>
    $(".record").click(function(){
        var date    = $(this).data('date');
        var datefor = new Date(date);
        datefor     = (datefor.getMonth() + 1) + '/' + datefor.getDate() + '/' +  datefor.getFullYear();
        BootstrapDialog.show({
            title:'Records for '+ datefor.trim(),
            message:$('<div></div>').load('{{url("dtr/daily/$bankID/$buid")}}/'+date),
            size:BootstrapDialog.SIZE_WIDE,

        });
    });
</script>
