<div class="tabbable tabbable-custom tabbable-noborder tabbable-reversed color">
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#all" data-toggle="tab">
                All Checks
            </a>
        </li>
        <li>
            <a href="#PDC" data-toggle="tab" id="pdc-nav" data-check-class="{{$checkClass}}">
                PDC
            </a>
        </li>
        <li>
            <a href="#due" data-toggle="tab" id="due-nav"  data-check-class="{{$checkClass}}">
                Due Checks
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="all">
            <table class="table table-condensed table-hover" id="check-details">
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
                    <tr>
                        <td>
                            {{$check->check_class}}
                        </td>
                        <td>{{date("m/d/Y",strtotime($check->check_date))}}</td>
                        <td>{{$check->checksreceivingtransaction_id}}</td>
                        <td>{{$check->check_no}}</td>
                        <td style="text-align:right">{{number_format($check->check_amount,2)}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="tab-pane" id="PDC">

        </div>

        <div class="tab-pane" id="due">

        </div>

    </div>
</div>

<style>
    .tabbable-custom.tabbable-noborder > .nav-tabs > li > a {
        border: 0;
        background: #faebcc;
        color: green;
        margin-left: -3%;
        border-bottom:2px solid green;
    }

    .tabbable-custom.tabbable-noborder > .nav-tabs > li.active > a {
        border: 0;
        background: white;
        color: green;
        margin-left: -3%;
    }

    .color{
        background: #faebcc;
    }
</style>




<script>
    $("#check-details").DataTable();

   // setTimeout(function(){
        element = document.createElement("div");
        element.className = "row total-check-class";
        input   = document.createElement('div');
        input.className = "form-control col-md-4 pull-right check-total-class";
        input.setAttribute("style", "text-align: right;");
        newelement = document.getElementsByClassName("total-check-class");
        ele  = element.appendChild(input);
        insertAsThird(ele,document.getElementById("check-details_wrapper"))
   // },1000);
        $(".check-total-class").text('Total :  {{number_format(session()->get('check_data_receive')->where('check_class',$checkClass)->sum('check_amount'),2)}}');

    function insertAsThird( element, parent )
    {
        parent.insertBefore(element, parent.children[2]);
    }

    $("#pdc-nav").click(function(){
        var checkClass = $(this).data('check-class');
        $.ajax({
            type:'get',
            url:'{{url('treasury/pdc')}}/'+checkClass+'/{{$date}}',
            success:function(data)
            {
                $("#PDC").html(data);
            }
        });
    });

    $("#due-nav").click(function(){
        var checkClass = $(this).data('check-class');
        $.ajax({
            type:'get',
            url:'{{url('treasury/due')}}/'+checkClass+'/{{$date}}',
            success:function(data)
            {
                $("#due").html(data);
            }
        });
    });
</script>