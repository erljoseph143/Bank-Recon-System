<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption bank-data">
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse">
            </a>
            <a href="#portlet-config" data-toggle="modal" class="config">
            </a>
        </div>
    </div>
    <div class="portlet-body">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab_1_1" data-toggle="tab">
                    Calendar View </a>
            </li>
            <li>
                <a href="#tab_1_2" data-toggle="tab">
                    Tabular View </a>
            </li>

        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active in" id="tab_1_1">

                <div class="col-md-12 col-sm-12">
                    {{--<div id="calendar" class="has-toolbar">--}}
                    {{--</div>--}}
                    <div class="form-group col-md-3">
                        {!! Form::select('months',$months,$dateMonth,['class'=>'form-control','id'=>'months']) !!}
                    </div>
                    <div class="form-group col-md-3">
                        {!! Form::select('years',$years,$dateYear,['class'=>'form-control','id'=>'years']) !!}
                    </div>
                    <div class="form-group col-md-3">
                        <button class="btn btn-default prev">
                            <i class="glyphicon glyphicon-arrow-left"></i>
                            Prev
                        </button>
                        <button class="btn btn-default next">
                            <i class="glyphicon glyphicon-arrow-right"></i>
                            Next
                        </button>
                    </div>
                    <div id="calender_section">
                        <div id="calender_section_top">
                            <ul>
                                <li>Sun</li>
                                <li>Mon</li>
                                <li>Tue</li>
                                <li>Wed</li>
                                <li>Thu</li>
                                <li>Fri</li>
                                <li>Sat</li>
                            </ul>
                        </div>
                        <div id="calender_section_bot">
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
                        </div>

                    </div>
                </div>
            </div>
            <div class="tab-pane fade tabular" id="tab_1_2">
                @include('DTR.finance.bsTable')
            </div>

        </div>
        <div class="clearfix margin-bottom-20">
        </div>

    </div>
</div>
<link rel="stylesheet" href="{{url('calendar/style.css')}}">
<link href="{{ url('logbook/metronic/assets/global/plugins/fullcalendar/fullcalendar.min.css') }}" rel="stylesheet"/>
{{--@push('scripts')--}}

    <script src="{{ url('logbook/metronic/assets/global/plugins/moment.min.js') }}"></script>
    <script src="{{ url('logbook/metronic/assets/global/plugins/fullcalendar/fullcalendar.min.js') }}"></script>
    {{--<script src="{{ url('logbook/metronic/assets/admin/pages/scripts/calendar.js') }}"></script>--}}
    <script>

        $("#years").change(function(){
            var year = $(this).val();
            var month = $('#months').val();
           $.ajax({
               type:'get',
               url:'{{url("dtr/calendar/$bankID/$buid")}}/'+year+'/'+month,
               success:function(data)
               {
                   $("#calender_section_bot").html(data);
               }
           });

           $.ajax({
               type:'get',
               url:'{{url("dtr/tabular/$bankID/$buid")}}/'+year+'/'+month,
               success:function(data)
               {
                   $(".tabular").html(data);
               }
           });
        });

        $("#months").change(function(){
            var month = $(this).val();
            var year = $('#years').val();
           $.ajax({
               type:'get',
               url:'{{url("dtr/calendar/$bankID/$buid")}}/'+year+'/'+month,
               success:function(data)
               {
                   $("#calender_section_bot").html(data);
               }
           });

           $.ajax({
               type:'get',
               url:'{{url("dtr/tabular/$bankID/$buid")}}/'+year+'/'+month,
               success:function(data)
               {
                   $(".tabular").html(data);
               }
           });
        });

        $(".prev").click(function(){
            var month = $('#months').val();
            var year = $('#years').val();

            if(month==12 && year == (new Date()).getFullYear())
            {
                $(".next").prop('disabled',true);
            }
            else
            {
                $(".next").prop('disabled',false);
            }

            lastValue = $('#years option:last-child').val();

            if(month==02 && year == lastValue)
            {
                $(this).prop('disabled',true);
            }
            else
            {
                $(this).prop('disabled',false);
            }


            nowMonths = month.replace(/^0+/, '').trim();
            nowMonths = parseInt(nowMonths)-1;
            if(nowMonths!=0)
            {
                if(nowMonths <10)
                {
                    nowMonths = "0"+nowMonths;
                }
                $("#months").val(nowMonths).trigger("change");
            }
            else
            {
                nowMonths = 12;
                if(nowMonths <10)
                {
                    nowMonths = "0"+nowMonths;
                }
                year = parseInt(year)-1;
                $("#months").val(nowMonths).trigger("change");
                $("#years").val(year).trigger("change");
            }

        });

        $(".next").click(function(){
            var month = $('#months').val();
            var year = $('#years').val();

            if(month==11 && year == (new Date()).getFullYear())
            {
                $(this).prop('disabled',true);
            }
            else
            {
                $(this).prop('disabled',false);
            }

            lastValue = $('#years option:last-child').val();

            if(month==02 && year == lastValue)
            {
                $(".prev").prop('disabled',true);
            }
            else
            {
                $(".prev").prop('disabled',false);
            }

            nowMonths = month.replace(/^0+/, '').trim();
            nowMonths = parseInt(nowMonths)+1;
            if(nowMonths<=12)
            {


                if(nowMonths <10)
                {
                    nowMonths = "0"+nowMonths;
                }
                $("#months").val(nowMonths).trigger("change");
            }
            else
            {
                //console.log(new Date('Y'));

                nowMonths = 01;
                if(nowMonths <10)
                {
                    nowMonths = "0"+nowMonths;
                }
                year = parseInt(year)+1;
                $("#months").val(nowMonths).trigger("change");
                $("#years").val(year).trigger("change");
            }

        });

        {{--$(document).on("click",".record",function(){--}}
            {{--var date    = $(this).data('date');--}}
            {{--var datefor = new Date(date);--}}
            {{--datefor     = (datefor.getMonth() + 1) + '/' + datefor.getDate() + '/' +  datefor.getFullYear();--}}
            {{--BootstrapDialog.show({--}}
                {{--title:'Records for '+ datefor.trim(),--}}
                {{--message:$('<div></div>').load('{{url("dtr/daily/$bankID/$buid")}}/'+date),--}}
                {{--size:BootstrapDialog.SIZE_WIDE,--}}

            {{--});--}}
        {{--});--}}
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
<style>
    .modal{
        z-index: 20050;
    }
</style>
{{--@endpush--}}