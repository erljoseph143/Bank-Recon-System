
<!-- widget content -->
<div class="widget-body">
    <table id="check-monthly" class="table table-striped table-bordered" width="100%">

        <thead>

        <tr>
            <th></th>
            <th>Bank Date</th>
            <th>Bank Name</th>
            <th>Account No</th>
        </tr>
        </thead>

        <tbody>
        @foreach($bsData as $key => $b)
            <tr>
                <td><input type="checkbox" name="month" class="month" value="{{$b->datein}}"></td>
                <td>{{date('F, Y',strtotime($b->datein))}}</td>
                <td>{{ $banklist[0][0] }}</td>
                <td>{{ $banklist[0][1] }}</td>
            </tr>

        @endforeach
        </tbody>

    </table>
    <button class="btn btn-success pull-right check-bal" data-source="{{$data}}">
        <i class="glyphicon glyphicon-search"></i>
        Check Balance
    </button>
    <div class="clearfix"></div>
</div>
<!-- end widget content -->

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

        var otable = $('#check-monthly').dataTable();
        var allPages = otable.fnGetNodes();


        $('.check-bal').click(function(){
            var date  = [];
            var urldata     = $(this).data('source');
          //  console.log(data);
            $('.month',allPages).each(function(){
                if($(this).is(':checked')==true)
                {
                    date.push($(this).val());
                }
            });
            BootstrapDialog.show({
                title:'Checking. . .',
                message:$("<div><label class='hidden' id='error_num'></label> " +
                        "<table class='table table-striped table-bordered' width='100%'> " +
                            "<thead> " +
                                "<tr> " +
                                    "<th>Month</th> " +
                                    "<th>Date</th> " +
                                    "<th>Description</th> " +
                                    "<th>Check No</th> " +
                                    "<th>Debit Amount</th> " +
                                    "<th>Credit Amount</th> " +
                                    "<th>Balance</th> " +
                                    "<th></th> " +
                                "</tr> " +
                            "</thead> " +
                            "<tbody id='tbody-result'> " +
                            "</tbody> " +
                        "</table> " +
                        "<div id='data-result'></div> " +
                    "</div>"),
                size:BootstrapDialog.SIZE_WIDE,
            });
            $('#tbody-result').html('');
            setTimeout(function(){
            $.ajax({
                type:'post',
                data:{date:date,urldata:urldata,_token:'{{csrf_token()}}'},
                url:'checkdataBS',
                cache:false,
                beforeSend: function (jqXHR, settings)
                {
                    console.log(urldata);
                    var self = this;
                    var xhr = settings.xhr;
                    settings.xhr = function () {
                        var output = xhr();
                        output.previous_text = '';
                        output.onreadystatechange = function () {

                            try{
                                // var new_response = output.responseText.substring(output.previous_text.length);
                                var result       = JSON.parse( output.responseText.match(/{[^}]+}$/gi) );
                                //var result = output.responseText;
                                var error_num = "";
                                if (output.readyState == 3)
                                {
                                    $("#data-result").html('<span style="color:red"><img src="loading.gif" height="20px" width="20px"> Please wait. . .</span>');
                                   // console.log(result.status);
                                    if(result.status=="not equal")
                                    {
                                        var deb  = "";
                                        var cred = "";
                                        if(result.type=="AR")
                                        {
                                            deb = result.amount;
                                            console.log(deb);
                                        }
                                        else
                                        {
                                            cred = result.amount;
                                            console.log(cred);
                                        }
                                        $("#tbody-result").append('<tr style="color:red">' +
                                            '<td>'+result.month+'</td>' +
                                            '<td>'+result.bank_date+'</td>' +
                                            '<td>'+result.des+'</td>' +
                                            '<td>'+result.check_no+'</td>' +
                                            '<td>'+deb+'</td>' +
                                            '<td>'+cred+'</td>' +
                                            '<td>'+result.balance+'</td>' +
                                            '<td><a class="btn btn-success btn-xs show-data" href="#" data-url="showErroBS/'+result.url+'">data</a></td>' +
                                            '</tr>');
                                        $("#error_num").text('naay_error');
                                    }

                                }
                                else if(output.readyState == 4)
                                {
                                    $("#data-result").fadeOut('slow');
                                    if( $("#error_num").text()=="")
                                    {
                                        $("#tbody-result").append('<tr><td colspan="8" style="text-align:center">No error found'+error_num+'</td></tr>')
                                    }
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
            },500);
        });


        $(document).on('click',".show-data",function(){
            var url = $(this).data('url');
            BootstrapDialog.show({
                title:'BS DATA',
                message:$('<div style="height: 30em;overflow-y: auto"></div>').load(url),
                type:BootstrapDialog.TYPE_DANGER,
                size:BootstrapDialog.SIZE_WIDE
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

        /*
         -----------------MY OWN FUNCTIONS------------------------------
         */
        /* BASIC ;*/



        /* END BASIC */

        /* COLUMN FILTER  */


        // custom toolbar

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