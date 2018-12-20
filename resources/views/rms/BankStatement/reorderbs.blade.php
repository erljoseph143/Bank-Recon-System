<style>
    .modal-lg{
        width:80%;
    }
</style>

                    <!-- widget content -->
                    <div class="widget-body">

                        <table id="error_bs" class="table table-striped table-bordered" width="100%">

                            <thead>

                            <tr>
                                <th><input type="radio" name="tag-to" class="top-data" value="top-data"></th>
                                <th>Bank Date</th>
                                <th>Description</th>
                                <th>Bank No.</th>
                                <th>Details</th>
                                <th>Debit Amount</th>
                                <th>Credit Amount</th>
                                <th>Bank Balance</th>
                                <th>Status</th>

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
                                <tr style="background-color: {{$b->error_label!=''?'red':''}};" id="tr{{$key}}" class="tr">
                                    <td>
                                        <input type="radio" name="tag-to" id="{{$key}}" class="tag-to" value="{{$key}}">
                                    </td>
                                    <td>
                                        <span id='spandate{{$b->bank_id}}'>{{date("m/d/Y",strtotime($b->bank_date))}}</span>
                                    </td>
                                    <td>
                                        <span id='spandes{{$b->bank_id}}'>{{$b->description}}</span>
                                    </td>
                                    <td>

                                        <span id='spanbankno{{$b->bank_id}}'>{{$b->bank_account_no}}</span>
                                    </td>
                                    <td>

                                        <span id='spanbankcheck{{$b->bank_id}}'>{{$b->bank_check_no}}</span>
                                    </td>
                                    <td style='text-align:right'>

                                        <span id='spanbankamountAP{{$b->bank_id}}'>{{$ap_amount}}</span>
                                    </td>
                                    <td style='text-align:right'>

                                        <span id='spanbankamountAR{{$b->bank_id}}'>{{$ar_amount}}</span>
                                    </td>
                                    <td style='text-align:right'>

                                        <span id='spanbankbal{{$b->bank_id}}'>{{number_format($b->bank_balance,2)}}</span>
                                    </td>
                                    <td>
                                        @if($b->error_label !='')
                                            <span class="badge bg-color-red bounceIn animated col-sm-12">
                                                Not Balance
                                            </span>
                                            <button class="btn btn-success btn-xs  add-data" id="{{$key}}">
                                                <i class="glyhpicon glyphicon-plus" ></i>
                                                Insert Data
                                            </button>
                                        @else
                                            Balance
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>

                        </table>


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
                message:$('<div></div>').load('{{url('bsinsertingdata')}}/'+key),
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
var data = 0;
        var $checkboxes = $('.tag-to');
        var check="";
    $checkboxes.change(function(){
        $(".tr").removeAttr('style');

        if($(this).is(':checked')==true)
        {
            check = $(this).attr('id').trim();
            setTimeout(function(){

                $("#tr"+check).css({'background-color':'blue','color':'white'});
            },100);
        }

    });



//        $checkboxes.change(function(){
//            var id = $(this).attr('id');
//            var countCheckedCheckboxes = $checkboxes.filter(':checked').length;
//            $('#count-checked-checkboxes').text(countCheckedCheckboxes);
//            data = countCheckedCheckboxes;
//            if(data>0)
//            {
//
//
//            }
//
//        });

//        $(".reorder").click(function(){
//            $(this).each(function(){
//                if($(this).is(':checked')==true)
//                {
//                    data
//                }
//            })
//        })

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