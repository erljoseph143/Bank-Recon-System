<section id="widget-grid" class="">

    <!-- row -->
    <div class="row">

        <!-- NEW WIDGET START -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
                <!-- widget options:
                usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

                data-widget-colorbutton="false"
                data-widget-editbutton="false"
                data-widget-togglebutton="false"
                data-widget-deletebutton="false"
                data-widget-fullscreenbutton="false"
                data-widget-custombutton="false"
                data-widget-collapsed="true"
                data-widget-sortable="false"

                -->

                <header role="heading">
                    <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                    <h2></h2>

                </header>

                <!-- widget div-->
                <div>

                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->

                    </div>
                    <!-- end widget edit box -->

                    <!-- widget content -->
                    <div class="widget-body no-padding">
                       @if($type=="rmsMonthly")
                        <br>
                        <a href="#{{base64_encode($bu."/".$com."/".$bankID).csrf_token()."?".base64_encode("BRS")}}" id='backbutton'><button class="btn btn-flat btn-warning"  style="margin-left:10px"> <i class='glyphicon glyphicon-arrow-left'></i> Back</button></a>

                            <br>
                       @endif
                        <table id="Monthly-table" class="table table-striped table-bordered" width="100%">

                            <thead>
                            <tr>

                                <th class="hasinput" style="width:16%">
                                    <input type="text" class="form-control" placeholder="Filter Month" />
                                </th>
                                <th class="hasinput" style="width:16%">
                                    <input type="text" class="form-control" placeholder="Filter Bank Name" />
                                </th>
                                <th class="hasinput" style="width:17%">
                                    <input type="text" class="form-control" placeholder="Filter Bank Account No" />
                                </th>

                                <th class="hasinput" style="width:16%">
                                    <input type="text" class="form-control" placeholder="Filter Bank Account Name" />
                                </th>
                                <th class="hasinput" style="width:16%">

                                </th>
                            </tr>
                            <tr>
                                <th data-class="expand">Month</th>
                                <th data-class="expand">Bank Name</th>
                                <th data-class="expand">Bank Account No</th>
                                <th data-hide="phone">Bank Account Name</th>
                                <th data-hide="phone"></th>


                            </tr>
                            </thead>

                            <tbody>

                    @foreach($data as $bd)
                        @foreach($banklist as $bl)
                                    <tr>
									<td>{{date("F, Y", strtotime($bd->datein))}}</td>
									<td>{{$bl[0]}}</td>
									<td>{{$bl[1]}}</td>
									<td>{{$bl[2]}}</td>
									<td>


						<div class='btn-group col-md-12'>
                            @php
                                if($type=="rmsMonthly")
                                {
                                $url = base64_encode("$bd->datein/$bankno/$com/$bu/$bl[0]/$bl[1]/$bankID/$bl[2]").csrf_token()."?".base64_encode("BRS");
                                $ex = explode(csrf_token()."?",$url);
                               // $exp2 = explode("/",base64_decode($ex[0]));
                              //  echo $exp2[7];
                                }
                                else
                                {
                                $url = base64_encode("$bd->datein/$bankno/$com/$bu/$bl[0]/$bl[1]").csrf_token()."?".base64_encode("BRS");
                                $ex = explode(csrf_token()."?",$url);
                                }

                            @endphp
                            @if($type=="manualBS")
                                <a href='#{{$url}}' class="showdata">
                                    <button class='btn btn-flat btn-info col-sm-12'>
                                        <i class='glyphicon glyphicon-eye-open'></i>
                                        View
                                    </button>
                                </a>
                             @elseif($type=="monthlyDis")
                                <a href='#{{$url}}' class="showdata-dis">
                                    <button class='btn btn-flat btn-info col-sm-12'>
                                        <i class='glyphicon glyphicon-eye-open'></i>
                                        View
                                    </button>
                                </a>
                            @elseif($type=="monthlyCheck")
                                <a href='#{{$url}}' class="match-check">
                                    <button class='btn btn-flat btn-info col-sm-12'>
                                        <i class='glyphicon glyphicon-eye-open'></i>
                                        View
                                    </button>
                                </a>
                            @elseif($type=="monthlyUnmatch")
                                <a href='#{{$url}}' class="Unmatch-check">
                                    <button class='btn btn-flat btn-info col-sm-12'>
                                        <i class='glyphicon glyphicon-eye-open'></i>
                                        View
                                    </button>
                                </a>
                            @elseif($type=="monthlyOC")
                                <a href='#{{$url}}' class="oc-check">
                                    <button class='btn btn-flat btn-info col-sm-12'>
                                        <i class='glyphicon glyphicon-eye-open'></i>
                                        View
                                    </button>
                                </a>
                            @elseif($type=="monthlyDM")
                                <a href='#{{$url}}' class="dm-check">
                                    <button class='btn btn-flat btn-info col-sm-12'>
                                        <i class='glyphicon glyphicon-eye-open'></i>
                                        View
                                    </button>
                                </a>
                            @elseif($type=="monthlyPDC_DC")
                                <a href='#{{$url}}' class="pdcDC-check">
                                    <button class='btn btn-flat btn-info col-sm-12'>
                                        <i class='glyphicon glyphicon-eye-open'></i>
                                        View
                                    </button>
                                </a>
                            @elseif($type=="monthlyPosted")
                                <a href='#{{$url}}' class="posted-check">
                                    <button class='btn btn-flat btn-info col-sm-12'>
                                        <i class='glyphicon glyphicon-eye-open'></i>
                                        View
                                    </button>
                                </a>
                            @elseif($type=="monthlyCancellded")
                                <a href='#{{$url}}' class="cancel-check">
                                    <button class='btn btn-flat btn-info col-sm-12'>
                                        <i class='glyphicon glyphicon-eye-open'></i>
                                        View
                                    </button>
                                </a>
                            @elseif($type=="rmsMonthly")
                                @php
                                    $year  = date("Y",strtotime($bd->datein));
                                    $month = date("m",strtotime($bd->datein));
                                    $countError = \App\BankStatement::whereYear('bank_date',$year)
                                                ->whereMonth('bank_date',$month)
												->where('bank_account_no',$bankno)
                                                ->where('company',$com)
                                                ->where('bu_unit',$bu)
                                                ->where('error_label','not balance')
                                                ->count('bank_id');
		
                                @endphp
                                <a href='#{{$url}}' class="rms-bs">
                                    <button class='btn btn-flat btn-info {{$countError==0?'col-sm-12':'col-sm-6'}} '>
                                        <i class='glyphicon glyphicon-eye-open'></i>
                                        View
                                    </button>
                                </a>
                                @if($countError != 0)
                                <a href="#{{$url}}" class="error-bs">
                                    <span class="badge bg-color-red bounceIn animated col-sm-6">
                                        ({{$countError}}) Errors
                                    </span>
                                </a>
                                @endif
                            @elseif($type=="acctg-View-BS")
                                <a href='#{{$url}}' class="acctg-View-BS">
                                    <button class='btn btn-flat btn-info col-sm-12'>
                                        <i class='glyphicon glyphicon-eye-open'></i>
                                        View
                                    </button>
                                </a>
                            @elseif($type=="staleCheck")
                                <a href='#{{$url}}' class="staleCheck">
                                    <button class='btn btn-flat btn-info col-sm-12'>
                                        <i class='glyphicon glyphicon-eye-open'></i>
                                        View
                                    </button>
                                </a>
                            @elseif($type=="reportsBS")
                                <button class='col-md-12 btn btn-info btn-sm dropdown-toggle' data-toggle='dropdown'>
                                    <i class='glyphicon glyphicon-eye-open'></i>	View <span class='caret'></span>
                                </button>
                                <ul class='dropdown-menu'>

                                    <li>



                                        <a class="getcheck" href='getcheck/{{$bd->datein}}/{{$bankno}}/{{$com}}/{{$bu}}/{{$bl[0]}}/{{$bl[1]}}'>
                                            Export as Excel
                                        </a>

                                    </li>
                                    <li class='divider'></li>
                                    {{--<li>--}}
                                    {{--<a href='#'>--}}
                                    {{--Export as PDF--}}

                                    {{--</a>--}}
                                    {{--</li>--}}
                                </ul>
                            @elseif($type=="ReportsManualBS")
                                <button class='col-md-12 btn btn-info btn-sm dropdown-toggle' data-toggle='dropdown'>
                                    <i class='glyphicon glyphicon-eye-open'></i>	View <span class='caret'></span>
                                </button>
                                <ul class='dropdown-menu'>

                                    <li>



                                        <a href='getManualBS/{{$bd->datein}}/{{$bankno}}/{{$com}}/{{$bu}}/{{$bl[0]}}/{{$bl[1]}}'>
                                            Export as Excel
                                        </a>

                                    </li>
                                    <li class='divider'></li>
                                    {{--<li>--}}
                                    {{--<a href='#'>--}}
                                    {{--Export as PDF--}}

                                    {{--</a>--}}
                                    {{--</li>--}}
                                </ul>
                            @endif
						</div>


									</td>
							  </tr>
                        @endforeach()
                    @endforeach()

                            </tbody>

                        </table>

                    </div>
                    <!-- end widget content -->

                </div>
                <!-- end widget div -->

            </div>
            <!-- end widget -->
        </article>
    </div>
</section>
<script type="text/javascript">

    /* DO NOT REMOVE : GLOBAL FUNCTIONS!
     *
     * pageSetUp(); WILL CALL THE FOLLOWING FUNCTIONS
     *
     * // activate tooltips
     * $("[rel=tooltip]").tooltip();
     *
     * // activate popovers
     * $("[rel=popover]").popover();
     *
     * // activate popovers with hover states
     * $("[rel=popover-hover]").popover({ trigger: "hover" });
     *
     * // activate inline charts
     * runAllCharts();
     *
     * // setup widgets
     * setup_widgets_desktop();
     *
     * // run form elements
     * runAllForms();
     *
     ********************************
     *
     * pageSetUp() is needed whenever you load a page.
     * It initializes and checks for all basic elements of the page
     * and makes rendering easier.
     *
     */

    pageSetUp();

    /*
     * ALL PAGE RELATED SCRIPTS CAN GO BELOW HERE
     * eg alert("my home function");
     *
     * var pagefunction = function() {
     *   ...
     * }
     * loadScript("js/plugin/_PLUGIN_NAME_.js", pagefunction);
     *
     */

    // PAGE RELATED SCRIPTS

    // pagefunction
    var pagefunction = function() {

        $(".showdata").click(function(){
            var data1 = $(this).attr("href");
            urinew = data1.replace("#","").trim();
            $("#content").html("");
            $("#content").html("<div style='position:relative;min-height:38em;width:100%'>" +
                "<div style='position:absolute;min-height:37.7em;width:100%;background-image:url(\"img/ajax-loader.gif\");background-repeat:no-repeat;background-position: center'></div></div>");
            $.ajax({
                type:'get',
                url:'showData/'+urinew,
                success:function(data)
                {
                    //console.log(data);
                    $("#content").html(data);
                },
                error:function(e) {
                    if (e.status == 401) {
                        BootstrapDialog.show({
                            type:BootstrapDialog.TYPE_INFO,
                            title:'Unauthorized',
                            message:'Sorry your session is expired, please login back',
                            buttons:[
                                {
                                    label:'close',
                                    icon:'glyphicon glyphicon-remove',
                                    cssClass:'btn btn-danger',
                                    action:function(dialogRef)
                                    {
                                        window.location.reload();
                                        dialogRef.close();
                                    }
                                }
                            ]
                        });
                    }
                }
            });
        });

        $(".showdata-dis").click(function(){
            var data1 = $(this).attr("href");
            urinew = data1.replace("#","").trim();
            $("#content").html("");
            $("#content").html("<div style='position:relative;min-height:38em;width:100%'>" +
                "<div style='position:absolute;min-height:37.7em;width:100%;background-image:url(\"img/ajax-loader.gif\");background-repeat:no-repeat;background-position: center'></div></div>");
            $.ajax({
                type:'get',
                url:'showDataDis/'+urinew,
                success:function(data)
                {
                    //console.log(data);
                    $("#content").html(data);
                },
                error:function(e) {
                    if (e.status == 401) {
                        BootstrapDialog.show({
                            type:BootstrapDialog.TYPE_INFO,
                            title:'Unauthorized',
                            message:'Sorry your session is expired, please login back',
                            buttons:[
                                {
                                    label:'close',
                                    icon:'glyphicon glyphicon-remove',
                                    cssClass:'btn btn-danger',
                                    action:function(dialogRef)
                                    {
                                        window.location.reload();
                                        dialogRef.close();
                                    }
                                }
                            ]
                        });
                    }
                }
            });
        });

        $(".match-check").click(function(){
            var data1 = $(this).attr("href");
            urinew = data1.replace("#","").trim();
            //alert(urinew);
            $("#content").html("");
            $("#content").html("<div style='position:relative;min-height:38em;width:100%'>" +
                "<div style='position:absolute;min-height:37.7em;width:100%;background-image:url(\"img/ajax-loader.gif\");background-repeat:no-repeat;background-position: center'></div></div>");
            $.ajax({
                type:'get',
                url:'showMacthCheck/'+urinew,
                success:function(data)
                {
                    //console.log(data);
                    $("#content").html(data);
                },
                error:function(e) {
                    if (e.status == 401) {
                        BootstrapDialog.show({
                            type:BootstrapDialog.TYPE_INFO,
                            title:'Unauthorized',
                            message:'Sorry your session is expired, please login back',
                            buttons:[
                                {
                                    label:'close',
                                    icon:'glyphicon glyphicon-remove',
                                    cssClass:'btn btn-danger',
                                    action:function(dialogRef)
                                    {
                                        window.location.reload();
                                        dialogRef.close();
                                    }
                                }
                            ]
                        });
                    }
                }
            });
        });

        $(".Unmatch-check").click(function(){
            var data1 = $(this).attr("href");
            urinew = data1.replace("#","").trim();
            //alert(urinew);
            $("#content").html("");
            $("#content").html("<div style='position:relative;min-height:38em;width:100%'>" +
                "<div style='position:absolute;min-height:37.7em;width:100%;background-image:url(\"img/ajax-loader.gif\");background-repeat:no-repeat;background-position: center'></div></div>");
            $.ajax({
                type:'get',
                url:'showMonthlyUnmatched/'+urinew,
                success:function(data)
                {
                    //console.log(data);
                    $("#content").html(data);
                },
                error:function(e) {
                    if (e.status == 401) {
                        BootstrapDialog.show({
                            type:BootstrapDialog.TYPE_INFO,
                            title:'Unauthorized',
                            message:'Sorry your session is expired, please login back',
                            buttons:[
                                {
                                    label:'close',
                                    icon:'glyphicon glyphicon-remove',
                                    cssClass:'btn btn-danger',
                                    action:function(dialogRef)
                                    {
                                        window.location.reload();
                                        dialogRef.close();
                                    }
                                }
                            ]
                        });
                    }
                }
            });
        });
        $(".oc-check").click(function(){
            var data1 = $(this).attr("href");
            urinew = data1.replace("#","").trim();
            //alert(urinew);
            $("#content").html("");
            $("#content").html("<div style='position:relative;min-height:38em;width:100%'>" +
                "<div style='position:absolute;min-height:37.7em;width:100%;background-image:url(\"img/ajax-loader.gif\");background-repeat:no-repeat;background-position: center'></div></div>");
            $.ajax({
                type:'get',
                url:'showMonthlyOC/'+urinew,
                success:function(data)
                {
                    //console.log(data);
                    $("#content").html(data);
                },
                error:function(e) {
                    if (e.status == 401) {
                        BootstrapDialog.show({
                            type:BootstrapDialog.TYPE_INFO,
                            title:'Unauthorized',
                            message:'Sorry your session is expired, please login back',
                            buttons:[
                                {
                                    label:'close',
                                    icon:'glyphicon glyphicon-remove',
                                    cssClass:'btn btn-danger',
                                    action:function(dialogRef)
                                    {
                                        window.location.reload();
                                        dialogRef.close();
                                    }
                                }
                            ]
                        });
                    }
                }
            });
        });

        $(".dm-check").click(function(){
            var data1 = $(this).attr("href");
            urinew = data1.replace("#","").trim();
            //alert(urinew);
            $("#content").html("");
            $("#content").html("<div style='position:relative;min-height:38em;width:100%'>" +
                "<div style='position:absolute;min-height:37.7em;width:100%;background-image:url(\"img/ajax-loader.gif\");background-repeat:no-repeat;background-position: center'></div></div>");
            $.ajax({
                type:'get',
                url:'showMonthlyDM/'+urinew,
                success:function(data)
                {
                    //console.log(data);
                    $("#content").html(data);
                },
                error:function(e) {
                    if (e.status == 401) {
                        BootstrapDialog.show({
                            type:BootstrapDialog.TYPE_INFO,
                            title:'Unauthorized',
                            message:'Sorry your session is expired, please login back',
                            buttons:[
                                {
                                    label:'close',
                                    icon:'glyphicon glyphicon-remove',
                                    cssClass:'btn btn-danger',
                                    action:function(dialogRef)
                                    {
                                        window.location.reload();
                                        dialogRef.close();
                                    }
                                }
                            ]
                        });
                    }
                }
            });
        });
        $(".pdcDC-check").click(function(){
            var data1 = $(this).attr("href");
            urinew = data1.replace("#","").trim();
            //alert(urinew);
            $("#content").html("");
            $("#content").html("<div style='position:relative;min-height:38em;width:100%'>" +
                "<div style='position:absolute;min-height:37.7em;width:100%;background-image:url(\"img/ajax-loader.gif\");background-repeat:no-repeat;background-position: center'></div></div>");
            $.ajax({
                type:'get',
                url:'showMonthlyPDC_DC/'+urinew,
                success:function(data)
                {
                    //console.log(data);
                    $("#content").html(data);
                },
                error:function(e) {
                    if (e.status == 401) {
                        BootstrapDialog.show({
                            type:BootstrapDialog.TYPE_INFO,
                            title:'Unauthorized',
                            message:'Sorry your session is expired, please login back',
                            buttons:[
                                {
                                    label:'close',
                                    icon:'glyphicon glyphicon-remove',
                                    cssClass:'btn btn-danger',
                                    action:function(dialogRef)
                                    {
                                        window.location.reload();
                                        dialogRef.close();
                                    }
                                }
                            ]
                        });
                    }
                }
            });
        });

        $(".cancel-check").click(function(){
            var data1 = $(this).attr("href");
            urinew = data1.replace("#","").trim();
            //alert(urinew);
            $("#content").html("");
            $("#content").html("<div style='position:relative;min-height:38em;width:100%'>" +
                "<div style='position:absolute;min-height:37.7em;width:100%;background-image:url(\"img/ajax-loader.gif\");background-repeat:no-repeat;background-position: center'></div></div>");
            $.ajax({
                type:'get',
                url:'showMonthlyCancelled/'+urinew,
                success:function(data)
                {
                    //console.log(data);
                    $("#content").html(data);
                },
                error:function(e) {
                    if (e.status == 401) {
                        BootstrapDialog.show({
                            type:BootstrapDialog.TYPE_INFO,
                            title:'Unauthorized',
                            message:'Sorry your session is expired, please login back',
                            buttons:[
                                {
                                    label:'close',
                                    icon:'glyphicon glyphicon-remove',
                                    cssClass:'btn btn-danger',
                                    action:function(dialogRef)
                                    {
                                        window.location.reload();
                                        dialogRef.close();
                                    }
                                }
                            ]
                        });
                    }
                }
            });
        });

        $(".posted-check").click(function(){
            var data1 = $(this).attr("href");
            urinew = data1.replace("#","").trim();
            //alert(urinew);
            $("#content").html("");
            $("#content").html("<div style='position:relative;min-height:38em;width:100%'>" +
                "<div style='position:absolute;min-height:37.7em;width:100%;background-image:url(\"img/ajax-loader.gif\");background-repeat:no-repeat;background-position: center'></div></div>");
            $.ajax({
                type:'get',
                url:'showMonthlyPosted/'+urinew,
                success:function(data)
                {
                    //console.log(data);
                    $("#content").html(data);
                },
                error:function(e) {
                    if (e.status == 401) {
                        BootstrapDialog.show({
                            type:BootstrapDialog.TYPE_INFO,
                            title:'Unauthorized',
                            message:'Sorry your session is expired, please login back',
                            buttons:[
                                {
                                    label:'close',
                                    icon:'glyphicon glyphicon-remove',
                                    cssClass:'btn btn-danger',
                                    action:function(dialogRef)
                                    {
                                        window.location.reload();
                                        dialogRef.close();
                                    }
                                }
                            ]
                        });
                    }
                }
            });
        });

        $(".rms-bs").click(function(){
            var data1 = $(this).attr("href");
            urinew = data1.replace("#","").trim();
            //alert(urinew);
            $("#content").html("");
            $("#content").html("<div style='position:relative;min-height:38em;width:100%'>" +
                "<div style='position:absolute;min-height:37.7em;width:100%;background-image:url(\"img/ajax-loader.gif\");background-repeat:no-repeat;background-position: center'></div></div>");
            $.ajax({
                type:'get',
                url:'showMonthlyBS/'+urinew,
                success:function(data)
                {
                    //console.log(data);
                    $("#content").html(data);
                },
                error:function(e) {
                    if (e.status == 401) {
                        BootstrapDialog.show({
                            type:BootstrapDialog.TYPE_INFO,
                            title:'Unauthorized',
                            message:'Sorry your session is expired, please login back',
                            buttons:[
                                {
                                    label:'close',
                                    icon:'glyphicon glyphicon-remove',
                                    cssClass:'btn btn-danger',
                                    action:function(dialogRef)
                                    {
                                        window.location.reload();
                                        dialogRef.close();
                                    }
                                }
                            ]
                        });
                    }
                }
            });
        });

        $(".staleCheck").click(function(){
            var data1 = $(this).attr("href");
            urinew = data1.replace("#","").trim();
            //alert(urinew);
            $("#content").html("");
            $("#content").html("<div style='position:relative;min-height:38em;width:100%'>" +
                "<div style='position:absolute;min-height:37.7em;width:100%;background-image:url(\"img/ajax-loader.gif\");background-repeat:no-repeat;background-position: center'></div></div>");
            $.ajax({
                type:'get',
                url:'showMonthlyStale/'+urinew,
                success:function(data)
                {
                    //console.log(data);
                    $("#content").html(data);
                },
                error:function(e) {
                    if (e.status == 401) {
                        BootstrapDialog.show({
                            type:BootstrapDialog.TYPE_INFO,
                            title:'Unauthorized',
                            message:'Sorry your session is expired, please login back',
                            buttons:[
                                {
                                    label:'close',
                                    icon:'glyphicon glyphicon-remove',
                                    cssClass:'btn btn-danger',
                                    action:function(dialogRef)
                                    {
                                        window.location.reload();
                                        dialogRef.close();
                                    }
                                }
                            ]
                        });
                    }
                }
            });
        });

        $(".acctg-View-BS").click(function(){
            var data1 = $(this).attr("href");
            urldata = data1.replace("#","").trim();

            // BootstrapDialog.show({
                // title:'Bank Statement Per Month',
                // message:$('<div></div>').load("showMonthlyBS_ACCTG/"+urldata,function(e,st){

                    // if(st == 'error')
                    // {
                        // BootstrapDialog.show({
                            // title:'Unauthorized',
                            // message:'Sorry your session is expired, please login back',
                            // buttons:[{
                                // label:'close',
                                // icon:'glyphicon glyphicon-remove',
                                // cssClass:'btn btn-danger',
                                // action:function(dialogRef)
                                // {
                                    // window.location.reload();
                                    // dialogRef.close();
                                // }
                            // }]
                        // })
                    // }
                // }),
                // type:BootstrapDialog.TYPE_INFO,
                // size:BootstrapDialog.SIZE_WIDE,
                // buttons:[{
                    // label:'close',
                    // icon:'glyphicon glyphicon-remove',
                    // cssClass:'btn btn-danger',
                    // action:function(dialogRef){
                        // dialogRef.close();
                    // }
                // }]
            // });
			
			
			BootstrapDialog.show({
                title:'Bank Statement Per Month',
                message:function(dialog) {
                var $message = $("<div><img src='ajax.gif'><small class='text-danger'>please wait...</small></div>");
                var pageToLoad = dialog.getData('pageToLoad');
                setTimeout(function(){
                    $message.load(pageToLoad,function(e,st){

                        if(st == 'error')
                        {
                            BootstrapDialog.show({
                                title:'Unauthorized',
                                message:'Sorry your session is expired, please login back',
                                buttons:[{
                                    label:'close',
                                    icon:'glyphicon glyphicon-remove',
                                    cssClass:'btn btn-danger',
                                    action:function(dialogRef)
                                    {
                                        window.location.reload();
                                        dialogRef.close();
                                    }
                                }]
                            })
                        }
                    });
                },1000);
                return $message;
            },
            data: {
                'pageToLoad': "showMonthlyBS_ACCTG/"+urldata,
            },
            onhidden: function(dialogRef){

            },
                type:BootstrapDialog.TYPE_INFO,
                size:BootstrapDialog.SIZE_WIDE,
                buttons:[{
                    label:'close',
                    icon:'glyphicon glyphicon-remove',
                    cssClass:'btn btn-danger',
                    action:function(dialogRef){
                        dialogRef.close();
                    }
                }]
            });
			
			
        });

		
		    $("#backbutton").click(function(){
            var data1 = $(this).attr("href");
            urinew = data1.replace("#","").trim();
            $("#content").html("");
            $("#content").html("<div style='position:relative;min-height:38em;width:100%'>" +
                "<div style='position:absolute;min-height:37.7em;width:100%;background-image:url(\"img/ajax-loader.gif\");background-repeat:no-repeat;background-position: center'></div></div>");
            $.ajax({
                type:'get',
                url:'BankList/'+urinew,
                success:function(data)
                {
                    //console.log(data);
                    $("#content").html(data);
                },
                error:function(e) {
                    if (e.status == 401) {
                        BootstrapDialog.show({
                            type:BootstrapDialog.TYPE_INFO,
                            title:'Unauthorized',
                            message:'Sorry your session is expired, please login back',
                            buttons:[
                                {
                                    label:'close',
                                    icon:'glyphicon glyphicon-remove',
                                    cssClass:'btn btn-danger',
                                    action:function(dialogRef)
                                    {
                                        window.location.reload();
                                        dialogRef.close();
                                    }
                                }
                            ]
                        });
                    }
                }
            });
        });
		
		$(".getcheck").click(function(e){
			e.preventDefault();
			
			var urldata =$(this).attr('href');
			            BootstrapDialog.show({
                            type:BootstrapDialog.TYPE_SUCCESS,
                            title:'Exporting To Excel',
                            message:function(dialog) {
								// var $message = $('<div style="text-align:center"><img src="loader.gif" with="100px" height="100px"><small class="text-danger"><div id="text-message">please wait...</div></small></div>');
								var $message = $('<div class="col-md-12">' +
									'<div style="text-align:center">' +
									'<img src="loader.gif" with="100px" height="100px">' +
									'<small class="text-danger">' +
									'<div id="text-message">please wait...</div>' +
									'</small>' +
									'</div>\n' +
									'    <div  style="background-color: #b1b7ba;width:100%">\n' +
									'        <div class="progress progress-lg active">\n' +
									'\n' +
									'            <div class="progress-bar prog1 bg-color-green" role="progressbar" style="width: 0%;"> ' +
									'<div id="percent" style="margin-top:5px;font-size:medium;vertical-align: middle"></div></div>\n' + '' +

								// '            <div class="progress-bar prog2 bg-color-green" role="progressbar" style="width: 0%;margin-left:14.28%"> ' +
								// '<div id="percent" style="margin-top:5px;font-size:medium;vertical-align: middle"></div></div>\n' +
								// '\n' +
								// '            <div class="progress-bar prog3 bg-color-green" role="progressbar" style="width: 0%;margin-left:28.56%"> ' +
								// '<div id="percent" style="margin-top:5px;font-size:medium;vertical-align: middle"></div></div>\n' +
								// '\n' +
								// '            <div class="progress-bar prog4 bg-color-green" role="progressbar" style="width: 0%;margin-left:42.84%"> ' +
								// '<div id="percent" style="margin-top:5px;font-size:medium;vertical-align: middle"></div></div>\n' +
								// '\n' +
								// '            <div class="progress-bar prog5 bg-color-green" role="progressbar" style="width: 0%;margin-left:57.12%"> ' +
								// '<div id="percent" style="margin-top:5px;font-size:medium;vertical-align: middle"></div></div>\n' +
								// '\n' +
								// '            <div class="progress-bar prog6 bg-color-green" role="progressbar" style="width: 0%;margin-left:71.40%"> ' +
								// '<div id="percent" style="margin-top:5px;font-size:medium;vertical-align: middle"></div></div>\n' +
								// '\n' +
								// '            <div class="progress-bar prog7 bg-color-green" role="progressbar" style="width: 0%;margin-left:85.68%"> ' +
								// '<div id="percent" style="margin-top:5px;font-size:medium;vertical-align: middle"></div></div>\n' +
								// '\n' +
								
								'        </div>\n' +
								'        {{--<div class="progress-lg  bg-color-greenLight" style="width: 0%;"></div>--}}\n' +
								'    </div>\n' +

								'</div>\n' +
								'<div class="clearfix"></div>');
								//var pageToLoad = dialog.getData('pageToLoad');
								// setTimeout(function(){
									// $message.load(pageToLoad);
								// },1000);
								return $message;
							},
							closable:false
                            // buttons:[
                                // {
                                    // label:'close',
									// id:'closeCheck',
                                    // icon:'glyphicon glyphicon-remove',
                                    // cssClass:'btn btn-danger',
                                    // action:function(dialogRef)
                                    // {
                                       // // window.location.reload();
                                        // dialogRef.close();
                                    // }
                                // }
                            //]
                        });
						
						$.ajax({
									type:'get',
									url:urldata,
							        success:function(data)
									{

									},
									beforeSend: function (jqXHR, settings)
									{
										var self = this;
										var xhr = settings.xhr;
										settings.xhr = function () {
											var output = xhr();
											output.previous_text = '';
											output.onreadystatechange = function () {
													var allprog   = 0;
													var progdata1 = 0;
													var progdata2 = 0;
													var progdata3 = 0;
													var progdata4 = 0;
													var progdata5 = 0;
													var progdata6 = 0;
													var progdata7 = 0;
												try{
													// var new_response = output.responseText.substring(output.previous_text.length);
													 var result       = JSON.parse( output.responseText.match(/{[^}]+}$/gi) );
													//var result = output.responseText;


													if (output.readyState == 3)
													{
													//	console.log(result.message);
														$("#text-message").text(result.message);
														$("#text-message").html(result.time_elapse);
														if(Math.round(result.pecent)==99)
														{
															prog = 100;
														}
														else
														{
															prog = result.percent.toFixed(2);
														}
														//console.log(prog);
														$('.prog1').css('width',prog+"%");
														$('#percent').text(prog+"%");
														// $('.prog1').css('width',result.progress1+"%");
														// $('.prog2').css('width',result.progress2+"%");
														// $('.prog3').css('width',result.progress3+"%");
														// $('.prog4').css('width',result.progress4+"%");
														// $('.prog5').css('width',result.progress5+"%");
														// $('.prog6').css('width',result.progress6+"%");
														// $('.prog7').css('width',result.progress7+"%");
														
														
														//console.log(result.percent.toFixed(2));
														
													}
													else if(output.readyState == 4)
													{
														$('.prog1').css('width',"100%");
														$('#percent').text('100%');
														// alert($("#text-message").html());
														window.location = result.url;
														
														// for(i=1;i<=5;i++)
														// {
															
															// if(i==5)
															// {	
																// $(".close").click();
															// }
															
															// setTimeout(function(){},1000);
														// }
														
														var i = 1;
														setTimeout(function(){
															$("#text-message").html($("#text-message").html() + " </br>Closing in " + i);
															i++;
															$(".close").click();
														},5000);
														

														
														// BootstrapDialog.show({
															// title:'Result',
															// message:$('<div></div>').load('result/'+result.file+'/'+result.date),
															// size:BootstrapDialog.SIZE_WIDE
														// });
													}
												}catch(e)
												{
													console.log("[XHR STATECHANGE] Exception: " + e);
												}
											};
											return output;
										}
									}
						        })
		});

		$(".error-bs").click(function(){
		    var url = $(this).attr('href').replace("#","").trim();
                    BootstrapDialog.show({
                        title:'Bank Statement Error',
                        message:$('<div class="clearfix" style="height: 30em;overflow-y: auto"></div>').load('bsError/'+url),
                        size:BootstrapDialog.SIZE_WIDE,
                        closable:false,
                        buttons:[
                            {
                                label:'Close',
                                cssClass:'btn btn-danger',
                                icon:'glyphicon glyphicon-remove',
                                action:function(dialog)
                                {
                                    dialog.close();
                                }
                            },
                            {
                                label:'Change Data Ordered',
                                icon:'glyphicon glyphicon-sort-by-order',
                                cssClass:'btn btn-success',
                                action:function(dialog)
                                {
//                                    if($(".reorder").is( ":checked" )==true)
//                                    {
                                       // console.log($(".reorder").is( ":checked" ));
                                    //}
                                    var id = "";
                                    $(".reorder").each(function(){
                                        if($(this).is(':checked')==true)
                                        {
                                          id+="|"+$(this).val();
                                        }
                                    });

                                    BootstrapDialog.show({
                                        title:'Re Order Data',
                                        message:$('<div style="height: 30em;overflow: auto"></div>').load('{{url('reorder')}}/'+id),
                                        size:BootstrapDialog.SIZE_WIDE,
                                        closable:false,
                                        buttons:[
                                            {
                                              label:'Proceed',
                                              icon:'glyphicon glyphicon-sort-by-order',
                                              cssClass:'btn btn-success',
                                              action:function(dialog)
                                              {
                                                 // dialog.close();
                                               var data =   $('input[name=tag-to]:checked').val();
                                                    BootstrapDialog.show({
                                                        title:'Result',
                                                        message:$('<div></div>').load('{{url('ordering')}}/'+data),

                                                    });
                                              }
                                            },
                                            {
                                                label:'Close',
                                                icon:'glyphicon glyhpicon-remove',
                                                cssClass:'btn btn-danger',
                                                action:function(dialog)
                                                {
                                                    dialog.close();
                                                }
                                            }
                                        ]

                                    });
                                }
                            }
                        ]
                    });

        });


        //console.log("cleared");

        /* // DOM Position key index //

         l - Length changing (dropdown)
         f - Filtering input (search)
         t - The Table! (datatable)
         i - Information (records)
         p - Pagination (paging)
         r - pRocessing
         < and > - div elements
         <"#id" and > - div with an id
         <"class" and > - div with a class
         <"#id.class" and > - div with an id and class

         Also see: http://legacy.datatables.net/usage/features
         */

        /* BASIC ;*/
        var responsiveHelper_dt_basic = undefined;
        var responsiveHelper_datatable_fixed_column = undefined;
        var responsiveHelper_datatable_col_reorder = undefined;
        var responsiveHelper_datatable_tabletools = undefined;

        var breakpointDefinition = {
            tablet : 1024,
            phone : 480
        };

        $('#dt_basic').dataTable({
            "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
            "oLanguage": {
                "sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
            },
            "autoWidth" : true,
            "preDrawCallback" : function() {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper_dt_basic) {
                    responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic'), breakpointDefinition);
                }
            },
            "rowCallback" : function(nRow) {
                responsiveHelper_dt_basic.createExpandIcon(nRow);
            },
            "drawCallback" : function(oSettings) {
                responsiveHelper_dt_basic.respond();
            }
        });

        /* END BASIC */

        /* COLUMN FILTER  */
        var otable = $('#Monthly-table').DataTable({
            //"bFilter": false,
            //"bInfo": false,
            //"bLengthChange": false
            //"bAutoWidth": false,
            //"bPaginate": false,
            //"bStateSave": true // saves sort state using localStorage
            "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6 hidden-xs'f><'col-sm-6 col-xs-12 hidden-xs'<'toolbar'>>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
            "oLanguage": {
                "sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
            },
            "autoWidth" : true,
            "preDrawCallback" : function() {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper_datatable_fixed_column) {
                    responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#Monthly-table'), breakpointDefinition);
                }
            },
            "rowCallback" : function(nRow) {
                responsiveHelper_datatable_fixed_column.createExpandIcon(nRow);
            },
            "drawCallback" : function(oSettings) {
                responsiveHelper_datatable_fixed_column.respond();
            }

        });

        // custom toolbar


        // Apply the filter
        $("#Monthly-table thead th input[type=text]").on( 'keyup change', function () {

            otable
                .column( $(this).parent().index()+':visible' )
                .search( this.value )
                .draw();

        } );
        /* END COLUMN FILTER */

        /* COLUMN SHOW - HIDE */
        $('#datatable_col_reorder').dataTable({
            "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'C>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
            "oLanguage": {
                "sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
            },
            "autoWidth" : true,
            "preDrawCallback" : function() {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper_datatable_col_reorder) {
                    responsiveHelper_datatable_col_reorder = new ResponsiveDatatablesHelper($('#datatable_col_reorder'), breakpointDefinition);
                }
            },
            "rowCallback" : function(nRow) {
                responsiveHelper_datatable_col_reorder.createExpandIcon(nRow);
            },
            "drawCallback" : function(oSettings) {
                responsiveHelper_datatable_col_reorder.respond();
            }
        });

        /* END COLUMN SHOW - HIDE */

        /* TABLETOOLS */
        $('#datatable_tabletools').dataTable({

            // Tabletools options:
            //   https://datatables.net/extensions/tabletools/button_options
            "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'T>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
            "oLanguage": {
                "sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
            },
            "oTableTools": {
                "aButtons": [
                    "copy",
                    "csv",
                    "xls",
                    {
                        "sExtends": "pdf",
                        "sTitle": "SmartAdmin_PDF",
                        "sPdfMessage": "SmartAdmin PDF Export",
                        "sPdfSize": "letter"
                    },
                    {
                        "sExtends": "print",
                        "sMessage": "Generated by SmartAdmin <i>(press Esc to close)</i>"
                    }
                ],
                "sSwfPath": "js/plugin/datatables/swf/copy_csv_xls_pdf.swf"
            },
            "autoWidth" : true,
            "preDrawCallback" : function() {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper_datatable_tabletools) {
                    responsiveHelper_datatable_tabletools = new ResponsiveDatatablesHelper($('#datatable_tabletools'), breakpointDefinition);
                }
            },
            "rowCallback" : function(nRow) {
                responsiveHelper_datatable_tabletools.createExpandIcon(nRow);
            },
            "drawCallback" : function(oSettings) {
                responsiveHelper_datatable_tabletools.respond();
            }
        });

        /* END TABLETOOLS */

    };

    // load related plugins

    loadScript("js/plugin/datatables/jquery.dataTables.min.js", function(){
        loadScript("js/plugin/datatables/dataTables.colVis.min.js", function(){
            loadScript("js/plugin/datatables/dataTables.tableTools.min.js", function(){
                loadScript("js/plugin/datatables/dataTables.bootstrap.min.js", function(){
                    loadScript("js/plugin/datatable-responsive/datatables.responsive.min.js", pagefunction)
                });
            });
        });
    });


</script>
<style>
	.close{
		display:none;
	}
</style>