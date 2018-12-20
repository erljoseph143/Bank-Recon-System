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
                    <br/>
                    &nbsp; &nbsp; &nbsp;Bank - >  {!!$bankName  !!}

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
                            <a href="#{{base64_encode($bu ."/".$com."/".$bankID.csrf_token())."?".base64_encode("BRS")}}" id='backbutton'><button class="btn btn-flat btn-warning"  style="margin-left:10px"> <i class='glyphicon glyphicon-arrow-left'></i> Back</button></a>
                        @endif
                        {{--<a href="#ajax/by_month_list.php?buid={{$bu}}&comid={{$com}}&bankid={{$bankID}}"><button class="btn btn-flat btn-warning" id='backbutton' style="margin-left:10px"> <i class='glyphicon glyphicon-arrow-left'></i> Back</button></a>--}}
                        {{--<a href="#ajax/view_bank_uploaded.php?comid={{$com}}&buid={{$bu}}&bankid={{$bankID}}&bankdate={{$bankdate}}&bankno={{$bankno}}&bankname={{$bankName}}">--}}
                        <a href="downloadBS/{{$bankdate}}/{{$bankno}}/{{$com}}/{{$bu}}/{{$bankName}}/{{$acctno}}">
                            <button class="btn btn-flat btn-success" id='backbutton' style="margin-left:10px">
                                <i class='glyphicon glyphicon-download'></i> Export to Excel
                            </button>
                        </a>

                        </br>
                        <table id="datatable_fixed_column" class="table table-striped table-bordered" width="100%">

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
                            @foreach($bank as $b)
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
                                 <tr>
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
										<input type="hidden" id="checknoOld{{$b->bank_id}}" value="{{$b->bank_check_no}}"/>
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

		   $("#backbutton").click(function(){
            var dataUrl = $(this).attr("href").replace("#","").trim();
            $("#content").html("");
            $("#content").html("<div style='position:relative;min-height:38em;width:100%'>" +
                "<div style='position:absolute;min-height:37.7em;width:100%;background-image:url(\"img/ajax-loader.gif\");background-repeat:no-repeat;background-position: center'></div></div>");
            $.ajax({
                type:'get',
                url:'BMonthly/'+dataUrl,
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
				var checknoOld  = $("#checknoOld"+idx).val();


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
                                    data:{bu:bu,comid:comid,bankno:bankno,bankdate:bankdate,id:id,des:des,bankcheckno:bankcheckno,bankamountAP:bankAP,bankamountAR:bankAR,bankbal:bankbal,checknoOld:checknoOld},
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
        var otable = $('#datatable_fixed_column').DataTable({
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
                    responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#datatable_fixed_column'), breakpointDefinition);
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
        $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {

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