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
                    <h2>Bank Recon </h2>

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


                        <table id="datatable_fixed_column" class="table table-striped table-bordered" width="100%">

                            <thead>

                            <tr>
                                <th data-class="expand">Bank Date</th>
                                <th data-class="expand">Bank Account No</th>
                                <th>Description</th>
                                <th data-hide="phone">Bank Check No</th>
                                <th data-hide="phone">Credit</th>
                                <th data-hide="phone">Debit</th>
                                <th data-hide="phone">Balance</th>
                                <th data-hide="phone">Action</th>


                            </tr>
                            </thead>

                            <tbody>
                            @foreach($mBS as $bs)
                                @if($bs->type =="AR")
                                    @php
                                    $credit = number_format($bs->bank_amount,2);
                                    $debit  = "";
                                    @endphp
                                @else
                                    @php
                                        $credit = "";
                                        $debit  = number_format($bs->bank_amount,2);
                                    @endphp
                                @endif
                                <tr>
                                    <td>
                                        <span id="bdate{{$bs->bank_id}}"> {{date("m/d/Y",strtotime($bs->bank_date))}}</span>
                                        <input type="text" style="width:100px;" class="form-control hidden" id="date{{$bs->bank_id}}" value="{{date("m/d/Y",strtotime($bs->bank_date))}}">
                                    </td>
                                    <td>
                                        <span id="bno{{$bs->bank_id}}">{{$bs->bank_account_no}}</span>
                                        <input type="text" style="width:100px;" class="form-control hidden" id="bankno{{$bs->bank_id}}" value="{{$bs->bank_account_no}}">
                                    </td>
                                    <td>
                                        <span id="bdes{{$bs->bank_id}}">{{$bs->description}}</span>
                                        <input type="text" style="width:100px;" class="form-control hidden" id="des{{$bs->bank_id}}" value="{{$bs->description}}">
                                    </td>
                                    <td>
                                        <span id="bcheck{{$bs->bank_id}}">{{$bs->bank_check_no}}</span>
                                        <input type="text" style="width:100px;" class="form-control hidden" id="checkno{{$bs->bank_id}}" value="{{$bs->bank_check_no}}">
                                    </td>
                                    <td>
                                        <span id="bcred{{$bs->bank_id}}">{{$credit}}</span>
                                        <input type="text" style="width:100px;" class="form-control hidden" id="cred{{$bs->bank_id}}" value="{{$credit}}">
                                    </td>
                                    <td>
                                        <span id="bdeb{{$bs->bank_id}}">{{$debit}}</span>
                                        <input type="text" style="width:100px;" class="form-control hidden" id="deb{{$bs->bank_id}}" value="{{$debit}}">
                                    </td>
                                    <td>
                                        <span id="bbal{{$bs->bank_id}}">{{number_format($bs->bank_balance,2)}}</span>
                                        <input type="text" style="width:100px;" class="form-control hidden" id="bal{{$bs->bank_id}}" value="{{number_format($bs->bank_balance,2)}}">
                                    </td>
                                    <td style="width: 150px">
                                        <button class="btn btn-info modify btn-xs" me="{{$bs->bank_id}}" id="{{$bs->bank_id}}">
                                            <i class="glyphicon glyphicon-edit"></i>
                                            Modify
                                        </button>
                                        <button class="btn btn-success btn-xs hidden save" id="save{{$bs->bank_id}}" me="{{$bs->bank_id}}">
                                            <i class="glyphicon glyphicon-save"></i>
                                            Save
                                        </button>
                                        <button class="btn btn-danger btn-xs hidden cancel" id="cancel{{$bs->bank_id}}" me="{{$bs->bank_id}}">
                                            <i class="glyphicon glyphicon-remove"></i>
                                            Cancel
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
        var id = 0;
        $(".modify").click(function () {
             id = $(this).attr("id");
            $(this).hide();

            $("#date"+id).removeClass("hidden");
            $("#bankno"+id).removeClass("hidden");
            $("#checkno"+id).removeClass("hidden");
            $("#deb"+id).removeClass("hidden");
            $("#cred"+id).removeClass("hidden");
            $("#bal"+id).removeClass("hidden");
            $("#des"+id).removeClass("hidden");

            $("#bdate"+id).addClass("hidden");
            $("#bno"+id).addClass("hidden");
            $("#bcheck"+id).addClass("hidden");
            $("#bcred"+id).addClass("hidden");
            $("#bdeb"+id).addClass("hidden");
            $("#bbal"+id).addClass("hidden");
            $("#bdes"+id).addClass("hidden");

            $("#save"+id).removeClass("hidden");
            $("#cancel"+id).removeClass("hidden");

        });

        $(".cancel").click(function(){
            var id = $(this).attr("me");
            $("#date"+id).addClass("hidden");
            $("#bankno"+id).addClass("hidden");
            $("#checkno"+id).addClass("hidden");
            $("#deb"+id).addClass("hidden");
            $("#cred"+id).addClass("hidden");
            $("#bal"+id).addClass("hidden");
            $("#des"+id).addClass("hidden");

            $("#bdate"+id).removeClass("hidden");
            $("#bno"+id).removeClass("hidden");
            $("#bcheck"+id).removeClass("hidden");
            $("#bcred"+id).removeClass("hidden");
            $("#bdeb"+id).removeClass("hidden");
            $("#bbal"+id).removeClass("hidden");
            $("#bdes"+id).removeClass("hidden");

            $("#save"+id).addClass("hidden");
            $("#cancel"+id).addClass("hidden");
            $("[me="+id+"]").show();
        });

        $(".save").click(function(){
            var id = $(this).attr("me");
            bankdate = $("#date"+id).val();
            bankno   = $("#bankno"+id).val();
            checkno  = $("#checkno"+id).val();
            deb      = $("#deb"+id).val();
            cred     = $("#cred"+id).val();
            bal      = $("#bal"+id).val();
            des      = $("#des"+id).val();
            BootstrapDialog.show({
                title:'BRS',
                message:'Are you sure you want to save Changes?',
                buttons:[
                    {
                        label:'Yes',
                        icon:'glyphicon glyphicon-thumbs-up',
                        cssClass:'btn btn-success',
                        action:function(dialogRef)
                        {
                           // alert(deb);
                            $.ajax({

                                type:'POST',
                                url:'bsmanualupdate',
                                data:{bankdate:bankdate,bankno:bankno,checkno:checkno,deb:deb,cred:cred,bal:bal,des:des,id:id},
                                success:function (data)
                                {
                               //   console.log(data);

                                    $("#date"+id).addClass("hidden");
                                    $("#bankno"+id).addClass("hidden");
                                    $("#checkno"+id).addClass("hidden");
                                    $("#deb"+id).addClass("hidden");
                                    $("#cred"+id).addClass("hidden");
                                    $("#bal"+id).addClass("hidden");
                                    $("#des"+id).addClass("hidden");

                                    $("#bdate"+id).removeClass("hidden");
                                    $("#bno"+id).removeClass("hidden");
                                    $("#bcheck"+id).removeClass("hidden");
                                    $("#bcred"+id).removeClass("hidden");
                                    $("#bdeb"+id).removeClass("hidden");
                                    $("#bbal"+id).removeClass("hidden");
                                    $("#bdes"+id).removeClass("hidden");

                                    $("#save"+id).addClass("hidden");
                                    $("#cancel"+id).addClass("hidden");
                                    $("[me="+id+"]").show();

                                    $("#bdate"+id).text(bankdate);
                                    $("#bno"+id).text(bankno);
                                    $("#bcheck"+id).text(checkno);
                                    $("#bcred"+id).text(cred);
                                    $("#bdeb"+id).text(deb);
                                    $("#bbal"+id).text(bal);
                                    $("#bdes"+id).text(des);
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
                            $("#date"+id).addClass("hidden");
                            $("#bankno"+id).addClass("hidden");
                            $("#checkno"+id).addClass("hidden");
                            $("#deb"+id).addClass("hidden");
                            $("#cred"+id).addClass("hidden");
                            $("#bal"+id).addClass("hidden");

                            $("#bdate"+id).removeClass("hidden");
                            $("#bno"+id).removeClass("hidden");
                            $("#bcheck"+id).removeClass("hidden");
                            $("#bcred"+id).removeClass("hidden");
                            $("#bdeb"+id).removeClass("hidden");
                            $("#bbal"+id).removeClass("hidden");

                            $("#save"+id).addClass("hidden");
                            $("#cancel"+id).addClass("hidden");
                            $("[me="+id+"]").show();
                            dialogRef.close();
                        }
                    }
                ]
            });
        });



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