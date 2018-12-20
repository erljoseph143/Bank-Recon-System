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

                        <table id="showErrorBS" class="table table-striped table-bordered" width="100%">
                            <thead>
                            <tr>

                                <th class="hasinput" >
                                    <input type="text" class="form-control" placeholder="Filter Bank Date" />
                                </th>
                                <th class="hasinput" >
                                    <input type="text" class="form-control" placeholder="Filter Description" />
                                </th>
                                <th class="hasinput" >
                                    <input type="text" class="form-control" placeholder="Filter Bank No" />
                                </th>
                                <th class="hasinput" >
                                    <input type="text" class="form-control" placeholder="Filter Details" />
                                </th>
                                <th class="hasinput" >
                                    <input type="text" class="form-control" placeholder="Filter Debit Amount" />
                                </th>
                                <th class="hasinput" >
                                    <input type="text" class="form-control" placeholder="Filter Credit Amount" />
                                </th>
                                <th class="hasinput" >
                                    <input type="text" class="form-control" placeholder="Filter Bank Balance" />
                                </th>

                                <th>

                                </th>
                            </tr>
                            <tr>
                                <th>Bank Date</th>
                                <th>Description</th>
                                <th>Bank No.</th>
                                <th>Details</th>
                                <th>Debit Amount</th>
                                <th>Credit Amount</th>
                                <th>Bank Balance</th>
                                <th>Action</th>

                            </tr>
                            </thead>

                            <tbody>
                            @foreach($bank as $key => $b)
                                @php
                                    if($b->type=="AP")
                                    {
                                        $ap_amount = number_format($b->bank_amount,2);
                                        $ar_amount = "";
                                    }
                                     else
                                     {
                                        $ar_amount = number_format($b->bank_amount,2);
                                        $ap_amount ="";
                                     }
                                @endphp
                                <tr id="idtr{{$b->bank_id}}" style="background-color: {{$b->error_label!=''?'red':'none'}};color:{{$b->error_label!=''?'white':'none'}}">
                                    <td>
                                        <input type='hidden' id='bankid{{$b->bank_id}}' value='{{$b->bank_id}}'>
                                        <input type='hidden' id='comid' value='{{$com}}'>
                                        <input type='hidden' id='buid' value='{{$bu}}'>


                                        <input style='width:100px' class='hidden form-control' type='text' id='bankdate{{$b->bank_id}}' value='{{date("m/d/Y",strtotime($b->bank_date))}}'>
                                        <span id='spandate{{$b->bank_id}}'>{{date("m/d/Y",strtotime($b->bank_date))}}</span>
                                    </td>
                                    <td>
                                        <input style='width:100px' type='text' class='hidden form-control' id='des{{$b->bank_id}}' value='{{$b->description}}'>
                                        <span id='spandes{{$b->bank_id}}'>{{$b->description}}</span>
                                    </td>
                                    <td>
                                        <input style='width:100px' type='text' class='hidden form-control' id='bankno{{$b->bank_id}}' value='{{$b->bank_account_no}}'>
                                        <span id='spanbankno{{$b->bank_id}}'>{{$b->bank_account_no}}</span>
                                    </td>
                                    <td>
                                        <input style='width:100px' type='text' class='hidden form-control' id='bankcheckno{{$b->bank_id}}' value='{{$b->bank_check_no}}'>
                                        <span id='spanbankcheck{{$b->bank_id}}'>{{$b->bank_check_no}}</span>
                                    </td>
                                    <td style='text-align:right'>
                                        <input style='width:100px' type='text' class='hidden form-control' id='bankamountAP{{$b->bank_id}}' value='{{$ap_amount}}'>
                                        <span id='spanbankamountAP{{$b->bank_id}}'>{{$ap_amount}}</span>
                                    </td>
                                    <td style='text-align:right'>
                                        <input style='width:100px' type='text' class='hidden form-control' id='bankamountAR{{$b->bank_id}}' value='{{$ar_amount}}'>
                                        <span id='spanbankamountAR{{$b->bank_id}}'>{{$ar_amount}}</span>
                                    </td>
                                    <td style='text-align:right'>
                                        <input style='width:100px' type='text' class='hidden form-control' id='bankbal{{$b->bank_id}}' value='{{number_format($b->bank_balance,2)}}'>
                                        <span id='spanbankbal{{$b->bank_id}}'>{{number_format($b->bank_balance,2)}}</span>
                                    </td>
                                    <td>
                                        <button id='{{$b->bank_id}}' class='modify btn btn-info btn-xs'>
                                            <i class='glyphicon glyphicon-edit'></i> Modify
                                        </button>

                                        <button id='save{{$b->bank_id}}' me='{{$b->bank_id}}' class='hidden save btn btn-success btn-xs'>
                                            <i class='glyphicon glyphicon-save'></i> save
                                        </button>

                                        @if($b->error_label!="")
                                            <button class="btn btn-success btn-xs  add-data" id="{{$key}}">
                                                <i class="glyhpicon glyphicon-plus" ></i>
                                                Insert Data
                                            </button>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
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
    elmnt = document.getElementById("idtr{{$id}}");
    elmnt.scrollIntoView();
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

        /*
         -----------------MY OWN FUNCTIONS------------------------------
         */

        $(".add-data").click(function(){
            var key = $(this).attr('id');
            BootstrapDialog.show({
                title:'Adding Data',
                message:$('<div style="height:20em;overflow-y: auto"></div>').load('{{url('bsinsertingdata')}}/'+key),
                size:BootstrapDialog.SIZE_WIDE,
                closable:false,
                buttons:[
                    {
                        label:'Close',
                        icon:'glyphicon glyphicon-remove',
                        cssClass:'btn btn-danger',
                        action:function(dialog)
                        {
                            dialog.close();
                        }
                    }
                ]
            });
        });


        $(".modify").each(function(){
            $(this).click(function()
            {
                var idx = $(this).attr('id');
                $("#save"+idx).removeClass("hidden");
                $("#save"+idx).show();
                $("#spandate"+idx).hide();
                $("#spandes"+idx).hide();
                //$("#spanbankno"+idx).hide();
                $("#spanbankcheck"+idx).hide();
                $("#spanbankamountAP"+idx).hide();
                $("#spanbankamountAR"+idx).hide();
                $("#spanbankbal"+idx).hide();

                $("#bankdate"+idx).removeClass("hidden");
                $("#des"+idx).removeClass("hidden");
                //$("#bankno"+idx).removeClass("hidden");
                $("#bankcheckno"+idx).removeClass("hidden");
                $("#bankamountAP"+idx).removeClass("hidden");
                $("#bankamountAR"+idx).removeClass("hidden");
                $("#bankbal"+idx).removeClass("hidden");
                $(this).hide();
            })
        });

        $(".save").each(function(){
            $(this).click(function()
            {
                var idx = $(this).attr('me');
                $("#"+idx).show();
                var id = $("#bankid"+idx).val();


                var bankdate = $("#bankdate"+idx).val();


                var des = $("#des"+idx).val();


                // $("#bankno"+idx).addClass("hidden");
                var bankno = $("#bankno"+idx).val();
                // $("#spanbankno"+idx).show();


                var bankcheckno = $("#bankcheckno"+idx).val();



                var bankAP = $("#bankamountAP"+idx).val();



                var bankAR = $("#bankamountAR"+idx).val();




                var bankbal = $("#bankbal"+idx).val();
                var comid   = $("#comid").val();
                var bu		= $("#buid").val();

                $(this).hide();


                BootstrapDialog.show({
                    title:'BRS Modifiying',
                    message:'Are you sure you want to update this records?',
                    type:BootstrapDialog.TYPE_INFO,
                    size:BootstrapDialog.SIZE_SMALL,
                    closable:false,
                    buttons:[
                        {
                            label:'Yes',
                            icon:'glyphicon glyphicon-thumbs-up',
                            cssClass:'btn btn-success',
                            action:function(dialogRef){

                                $(this).hide();
                                $.ajax({
                                    type:'POST',
                                    url:'updatebsdata',
                                    data:{bu:bu,comid:comid,bankno:bankno,bankdate:bankdate,id:id,des:des,bankcheckno:bankcheckno,bankamountAP:bankAP,bankamountAR:bankAR,bankbal:bankbal},
                                    success:function(res)
                                    {
                                        $("#bankdate"+idx).addClass("hidden");
                                        $("#des"+idx).addClass("hidden");
                                        $("#bankcheckno"+idx).addClass("hidden");
                                        $("#bankamountAP"+idx).addClass("hidden");
                                        $("#bankamountAR"+idx).addClass("hidden");
                                        $("#bankbal"+idx).addClass("hidden");


                                        $("#spandate"+idx).html(bankdate);
                                        $("#spandes"+idx).html(des);
                                        $("#spanbankcheck"+idx).html(bankcheckno);
                                        $("#spanbankamountAP"+idx).html(bankAP);
                                        $("#spanbankamountAR"+idx).html(bankAR);
                                        $("#spanbankbal"+idx).html(bankbal);

                                        $("#spandate"+idx).show();
                                        $("#spandes"+idx).show();
                                        $("#spanbankcheck"+idx).show();
                                        $("#spanbankamountAP"+idx).show();
                                        $("#spanbankamountAR"+idx).show();
                                        $("#spanbankbal"+idx).show();
                                        console.log(res);
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
                                dialogRef.close();
                            }
                        },
                        {
                            label:'No',
                            icon:'glyphicon glyphicon-thumbs-down',
                            cssClass:'btn btn-danger',
                            action:function(dialogRef)
                            {
                                $("#bankdate"+idx).addClass("hidden");
                                $("#des"+idx).addClass("hidden");
                                $("#bankcheckno"+idx).addClass("hidden");
                                $("#bankamountAP"+idx).addClass("hidden");
                                $("#bankamountAR"+idx).addClass("hidden");
                                $("#bankbal"+idx).addClass("hidden");

                                $("#spandate"+idx).html(bankdate);
                                $("#spandes"+idx).html(des);
                                $("#spanbankcheck"+idx).html(bankcheckno);
                                $("#spanbankamountAP"+idx).html(bankAP);
                                $("#spanbankamountAR"+idx).html(bankAR);
                                $("#spanbankbal"+idx).html(bankbal);

                                $("#spandate"+idx).show();
                                $("#spandes"+idx).show();
                                $("#spanbankcheck"+idx).show();
                                $("#spanbankamountAP"+idx).show();
                                $("#spanbankamountAR"+idx).show();
                                $("#spanbankbal"+idx).show();
                                dialogRef.close();
                            }
                        }
                    ]
                });
            });
        });


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