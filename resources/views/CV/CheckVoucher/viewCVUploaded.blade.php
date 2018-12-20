<style>
    .modal-lg{
        width:95%;
    }
</style>
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
                    {{--&nbsp; &nbsp; &nbsp;Bank - >  {!!$bankName  !!}--}}

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

                        <table id="viewExcel" class="table table-striped table-bordered" width="100%">
                            <thead>
                            <tr>
                                <th>CV No.</th>
                                <th>Line No.</th>
                                <th>CV Status</th>
								<th>CV Date</th>
                                <th>Check Number</th>
                                <th>Check Amount</th>
                                <th>Bank Account No.</th>
                                <th>Bank Name</th>
                                <th>Check Date</th>
                                <th>Clearing Date</th>
                                <th>Cleared Flag</th>
                                <th>Payee</th>

                            </tr>
                            </thead>

                            <tbody>
                                @foreach($excelData as $key => $data)
                                    <tr>
                                        <td>{{$data[0]}}</td>
                                        <td>{{$data[1]}}</td>
                                        <td>{{$data[2]}}</td>
                                        <td>{{$data[3]}}</td>
                                        <td>{{$data[4]}}</td>
                                        <td style="text-align:right">{{number_format($data[5],2)}}</td>
                                        <td>{{$data[6]}}</td>
                                        <td>{{$data[7]}}</td>
                                        <td>{{$data[8]}}</td>
                                        <td>{{$data[9]}}</td>
                                        <td>{{$data[10]}}</td>
                                        <td>{{$data[11]}}</td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>

{{--@php--}}
    {{--dd($excelData);--}}
{{--@endphp--}}
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
        var otable = $('#viewExcel').DataTable({
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
                    responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#viewExcel'), breakpointDefinition);
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
        $("#viewExcel thead th input[type=text]").on( 'keyup change', function () {

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