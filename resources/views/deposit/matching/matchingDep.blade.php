<link href="{{url('deposit/assets/css/bootstrap.min.css')}}" rel="stylesheet" />
<link href="{{url('deposit/assets/css/material-bootstrap-wizard.css')}}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" media="screen" href="{{asset('assets/bootstrap-dialog/css/bootstrap-dialog.min.css')}}">


<style>
    .wizard-container {
        padding-top: 10px;
        z-index: 3;
    }
    .wizard-card .wizard-header {
        text-align: center;
         padding: 0 0 0 0;
    }

    .data-height
    {
        height: 350px;
        overflow-y: auto;
        padding:10px;
        border:1px solid black;
    }

    .my-table
    {
        font-size:12px;
        text-transform: uppercase;
    }

    .my-table-th
    {
        width: 180px;
    }

    .tab-href{
        pointer-events: none;
        cursor: default;
    }
</style>
<div class="col-sm-12 ">
<!--      Wizard container        -->
    <a href="{{url('deposit/deplist')}}" class="btn btn-default">
        <i class="glyphicon glyhpicon-home"></i>
        Back to Home
    </a>
<div class="wizard-container">
    <div class="card wizard-card" data-color="red" id="wizard">
        <form action="" method="">
            <!--        You can switch " data-color="blue" "  with one of the next bright colors: "green", "orange", "red", "purple"             -->

            <div class="wizard-header">
                <h3 class="wizard-title">
                    Deposit Matching
                </h3>

            </div>
            <div class="wizard-navigation">
                <ul>
                    <li><a class="tab-href" href="#samedateAmt" data-toggle="tab">Same Date And Amount</a></li>
                    <li><a class="tab-href" href="#duplicateEntry" data-toggle="tab">Duplicate Entry Same Date and Amount</a></li>
                    <li><a class="tab-href" href="#plus5days" data-toggle="tab">Plus 5 Days</a></li>
                    <li><a class="tab-href" href="#minus5day" data-toggle="tab">Minus 5 Days</a></li>

                    <li><a class="tab-href" href="#branchCode" data-toggle="tab">Match By Branch Code</a></li>
                    <li><a class="tab-href" href="#batchDS" data-toggle="tab">Match By DS# Batch</a></li>


                    <li><a class="tab-href" href="#unmatchBS" data-toggle="tab">Unmatch in Bank</a></li>
                    <li><a class="tab-href" href="#unmatchBK" data-toggle="tab">Unmatch in Book</a></li>
                </ul>
            </div>

            <div class="tab-content">
                <div class="tab-pane" id="samedateAmt">
                    <div class="data-height match-data-same">
                        <table class="my-table" border="1" style="border-collapse: collapse;width:100%">
                            <thead>
                            <tr>
                                <th class="my-table-th">Bank Date</th>
                                <th class="my-table-th">Bank Description</th>
                                <th class="my-table-th">Bank Amount</th>
                                <th class="my-table-th"></th>
                                <th class="my-table-th">Entry No</th>
                                <th class="my-table-th">Book Date</th>
                                <th class="my-table-th">Doc No</th>
                                <th class="my-table-th">Ext Doc No</th>
                                <th class="my-table-th">Book Amount</th>
                                <th class="my-table-th">User ID</th>
                                <th class="my-table-th">Description</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($sameDate as $key => $sameD)
                                <tr>
                                    @foreach($sameD as $key2 => $day)
                                        <td>{{$day}}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="duplicateEntry">
                    <div class="data-height match-data-dup">

                    </div>
                </div>
                <div class="tab-pane" id="plus5days">
                    <div class="data-height match-data-plus">

                    </div>
                </div>
                <div class="tab-pane" id="minus5day">
                    <div class="data-height match-data-minus">

                    </div>
                </div>


                <div class="tab-pane" id="branchCode">
                    <div class="data-height match-data-code">

                    </div>
                </div>
                <div class="tab-pane" id="batchDS">
                    <div class="data-height match-data-batchDS">

                    </div>
                </div>



                <div class="tab-pane" id="unmatchBS">
                    <div class="data-height match-data-unmatchBS">

                    </div>
                </div>
                <div class="tab-pane" id="unmatchBK">
                    <div class="data-height match-data-unmatchBK">

                    </div>
                </div>
            </div>
            <div class="wizard-footer">
                <div class="pull-right">
                    <input type='button' class='btn btn-next btn-fill btn-danger btn-wd next' data-match="same-date" name='next' value='Next' />
                    <input type='button' class='btn btn-finish btn-fill btn-danger btn-wd' name='finish' value='Finish' />
                    <a href="#" data-url="{{url('deposit/depExcel')}}" class="btn btn-success hidden btn-excel">
                        <i class="fa fa-download"></i>
                        Export to Excel
                    </a>
                </div>
                <div class="pull-left">
                    <input type='button' class='btn btn-previous btn-fill btn-default btn-wd prev' name='previous' value='Previous' />


                </div>
                <div class="clearfix"></div>
            </div>
        </form>
    </div>
</div> <!-- wizard container -->
</div>

<div class="hidden prog-data">
    <div class="col-md-12">
        <div style="text-align:center">
            <img src="{{url('loader.gif')}}" with="100px" height="100px">
        </div>
        <div style="background-color: #b1b7ba;width:100%">
            <div class="progress progress-lg active">
                <div class="progress-bar prog1 bg-color-green" role="progressbar" >
                    <div class="percent" style="margin-top:1px;font-size:small;vertical-align: middle">0%</div>
                </div>
            </div>

        </div>
    </div>
    <div class="clearfix"></div>
</div>

<script src="{{url('deposit/assets/js/jquery-2.2.4.min.js')}}" type="text/javascript"></script>
<script src="{{url('deposit/assets/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{url('deposit/assets/js/jquery.bootstrap.js')}}" type="text/javascript"></script>

<!--  Plugin for the Wizard -->
<script src="{{url('deposit/assets/js/material-bootstrap-wizard.js')}}"></script>

<!--  More information about jquery.validate here: http://jqueryvalidation.org/	 -->
<script src="{{url('deposit/assets/js/jquery.validate.min.js')}}"></script>

<script src="{{asset('assets/bootstrap-dialog/js/bootstrap-dialog.min.js')}}"></script>

<script>
    var sm     = 0;
    var dup    = 0;
    var plus   = 0;
    var minus  = 0;
    var brCode = 0;
    var dsNum  = 0;
    var unBs   = 0;
    var unBk   = 0;
    $(document).ready(function(){
        setTimeout(function(){
            $(".next").data("match","duplicate");
            $(".next").click();
            $(".next").prop('disabled',true);
            sm = 1;
        },2000);

    });




    $(".prev").click(function(){
        if(sm==1 || dup==1 || plus ==1 || minus ==1 || dsNum ==1 || unBs ==1 || unBk ==1)
        {
            $(".next").prop('disabled',false);
        }

    });
    $(".next").click(function(){
        var type = $(this).data("match");
        if(type=="duplicate")
        {
            $(".btn-excel").addClass("hidden");
            if(dup==0)
            {
                $(".next").prop('disabled',true);
                //$('#to-plus').prop('disabled',true);
                $(".match-data-dup").html("<div style='position:relative;min-height:20em;width:100%'>" +
                    "<div style='position:absolute;min-height:19em;width:100%;background-image:url(\"{{url('img/ajax-loader.gif')}}\");background-repeat:no-repeat;background-position: center'></div></div>");
                $.ajax({
                    type:'get',
                    url:'{{url('deposit/dupEntry')}}',
                    success:function(data)
                    {

                        dup = 1;
                        //$('#to-plus').prop('disabled',false);
                        $(".match-data-dup").html('');
                        $(".match-data-dup").html(data);
                        setTimeout(function(){
                            //$('#to-plus').click();
                            $(".next").data("match","plus 5 days");
                            $(".next").prop('disabled',false);
                            $(".next").click();
                            $(".next").prop('disabled',true);
                        },2000);


                    }
                });
            }
        }
        else if(type=='plus 5 days')
        {
            $(".btn-excel").addClass("hidden");
            if(plus==0)
            {
                $(".next").prop('disabled',true);
                //$('#to-plus').prop('disabled',true);
                $(".match-data-plus").html("<div style='position:relative;min-height:20em;width:100%'>" +
                    "<div style='position:absolute;min-height:19em;width:100%;background-image:url(\"{{url('img/ajax-loader.gif')}}\");background-repeat:no-repeat;background-position: center'></div></div>");
                $.ajax({
                    type:'get',
                    url:'{{url('deposit/plus5')}}',
                    success:function(data)
                    {

                        plus = 1;
                        //$('#to-plus').prop('disabled',false);
                        $(".match-data-plus").html('');
                        $(".match-data-plus").html(data);
                        setTimeout(function(){
                            //$('#to-plus').click();
                            $(".next").data("match","minus 5 days");
                            $(".next").prop('disabled',false);
                            $(".next").click();
                            $(".next").prop('disabled',true);
                        },2000);


                    }
                });
            }
        }
        else if(type=='minus 5 days')
        {
            $(".btn-excel").addClass("hidden");
            if(minus==0)
            {
                $(".next").prop('disabled',true);
                //$('#to-plus').prop('disabled',true);
                $(".match-data-minus").html("<div style='position:relative;min-height:20em;width:100%'>" +
                    "<div style='position:absolute;min-height:19em;width:100%;background-image:url(\"{{url('img/ajax-loader.gif')}}\");background-repeat:no-repeat;background-position: center'></div></div>");
                $.ajax({
                    type:'get',
                    url:'{{url('deposit/minus5')}}',
                    success:function(data)
                    {

                        minus = 1;
                        //$('#to-plus').prop('disabled',false);
                        $(".match-data-minus").html('');
                        $(".match-data-minus").html(data);
                        setTimeout(function(){
                            //$('#to-plus').click();
                            $(".next").data("match","branchcode");
                            $(".next").prop('disabled',false);
                            $(".next").click();
                            $(".next").prop('disabled',true);
                        },2000);


                    }
                });
            }
        }

        else if(type=='branchcode')
        {
            $(".btn-excel").addClass("hidden");
            if(brCode==0)
            {
                $(".next").prop('disabled',true);
                //$('#to-plus').prop('disabled',true);
                $(".match-data-code").html("<div style='position:relative;min-height:20em;width:100%'>" +
                    "<div style='position:absolute;min-height:19em;width:100%;background-image:url(\"{{url('img/ajax-loader.gif')}}\");background-repeat:no-repeat;background-position: center'></div></div>");
                $.ajax({
                    type:'get',
                    url:'{{url('deposit/branchCode')}}',
                    success:function(data)
                    {

                        brCode = 1;
                        //$('#to-plus').prop('disabled',false);
                        $(".match-data-code").html('');
                        $(".match-data-code").html(data);
                        setTimeout(function(){
                            //$('#to-plus').click();
                            $(".next").data("match","ds number");
                            $(".next").prop('disabled',false);
                            $(".next").click();
                            $(".next").prop('disabled',true);
                        },2000);


                    }
                });
            }
        }

        else if(type=='ds number')
        {
            $(".btn-excel").addClass("hidden");
            if(dsNum==0)
            {
                $(".next").prop('disabled',true);
                //$('#to-plus').prop('disabled',true);
                $(".match-data-batchDS").html("<div style='position:relative;min-height:20em;width:100%'>" +
                    "<div style='position:absolute;min-height:19em;width:100%;background-image:url(\"{{url('img/ajax-loader.gif')}}\");background-repeat:no-repeat;background-position: center'></div></div>");
                $.ajax({
                    type:'get',
                    url:'{{url('deposit/dsnumber')}}',
                    success:function(data)
                    {

                        dsNum = 1;
                        //$('#to-plus').prop('disabled',false);
                        $(".match-data-batchDS").html('');
                        $(".match-data-batchDS").html(data);
                        setTimeout(function(){
                            //$('#to-plus').click();
                            $(".next").data("match","unmatch BS");
                            $(".next").prop('disabled',false);
                            $(".next").click();
                            $(".next").prop('disabled',true);
                        },2000);


                    }
                });
            }
        }

        else if(type=='unmatch BS')
        {
            $(".btn-excel").addClass("hidden");
            if(unBs==0)
            {
                $(".next").prop('disabled',true);
                //$('#to-plus').prop('disabled',true);
                $(".match-data-unmatchBS").html("<div style='position:relative;min-height:20em;width:100%'>" +
                    "<div style='position:absolute;min-height:19em;width:100%;background-image:url(\"{{url('img/ajax-loader.gif')}}\");background-repeat:no-repeat;background-position: center'></div></div>");
                $.ajax({
                    type:'get',
                    url:'{{url('deposit/unmatchBS')}}',
                    success:function(data)
                    {

                        unBs = 1;
                        //$('#to-plus').prop('disabled',false);
                        $(".match-data-unmatchBS").html('');
                        $(".match-data-unmatchBS").html(data);
                        setTimeout(function(){
                            //$('#to-plus').click();
                            $(".next").data("match","unmatch BK");
                            $(".next").prop('disabled',false);
                            $(".next").click();
                            $(".next").prop('disabled',true);
                        },2000);


                    }
                });
            }
        }
        else if(type=='unmatch BK')
        {
            $(".btn-excel").removeClass("hidden");
            if(unBk==0)
            {
                //$('#to-plus').prop('disabled',true);
                $(".match-data-unmatchBK").html("<div style='position:relative;min-height:20em;width:100%'>" +
                    "<div style='position:absolute;min-height:19em;width:100%;background-image:url(\"{{url('img/ajax-loader.gif')}}\");background-repeat:no-repeat;background-position: center'></div></div>");
                $.ajax({
                    type:'get',
                    url:'{{url('deposit/unmatchBK')}}',
                    success:function(data)
                    {

                        unBk = 1;
                        //$('#to-plus').prop('disabled',false);
                        $(".match-data-unmatchBK").html('');
                        $(".match-data-unmatchBK").html(data);
                        setTimeout(function(){
                            //$('#to-plus').click();
                            $(".next").data("match","unmatch BK");
                            $(".btn-finish").hide();
                            $(".btn-excel").removeClass("hidden");
                            $(".next").prop('disabled',false);

                        },2000);


                    }
                });
            }
        }

    });
	
	   $(".btn-excel").click(function(){
        var dataUrl = $(this).data("url");
        var prog = $('.prog-data').html();
        var dialog = BootstrapDialog.show({
            title:'Deposit Excel Report',
            message:$('<div></div>').html(prog),
            closable:false
        });

        var xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function(vm)
        {
            var result = JSON.parse(this.responseText.match(/{[^}]+}$/gi));
            if(this.readyState == 3)
            {
                $(".prog1").css('width',result.percent+"%");
                $('.percent').text(result.percent+"%");
                console.log(result.percent+"%");
            }
            else if(this.readyState == 4)
            {
                $(".prog1").css('width',"100%");
                $('.percent').text("100%");
                window.location = result.url;
				dialog.close();
            }
        }.bind(xhr, this);
        xhr.open('GET', dataUrl);
        xhr.send();
    });

    {{--$(".btn-excel").click(function(){--}}
        {{--var prev = "<div style='position:relative;min-height:20em;width:100%'>" +--}}
            {{--"<div style='position:absolute;min-height:19em;width:100%;background-image:url(\"{{url('img/ajax-loader.gif')}}\");background-repeat:no-repeat;background-position: center'></div></div>";--}}
            {{--$.ajax({--}}
                {{--type:'get',--}}
                {{--url:'{{url('deposit/depExcel')}}',--}}
                {{--success:function()--}}
                {{--{--}}

                {{--}--}}

            {{--})--}}
    {{--});--}}

</script>