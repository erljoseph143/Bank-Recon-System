<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <title>DATA</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    {{--<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>--}}
    <link href="{{ url('logbook/metronic/assets/global/css/fonts.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ url('logbook/metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ url('logbook/metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ url('logbook/metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ url('logbook/metronic/assets/global/plugins/uniform/css/uniform.default.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ url('logbook/metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css"/>

    <link href="{{url('logbook/metronic/assets/global/plugins/icheck/skins/all.css')}}" rel="stylesheet"/>
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
    {{--<link href="{{ url('logbook/metronic/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet" type="text/css"/>--}}
    {{--<link href="{{ url('logbook/metronic/global/plugins/fullcalendar/fullcalendar.min.css') }}" rel="stylesheet" type="text/css"/>--}}
    {{--<link href="{{ url('logbook/metronic/global/plugins/jqvmap/jqvmap/jqvmap.css') }}" rel="stylesheet" type="text/css"/>--}}
<!-- END PAGE LEVEL PLUGIN STYLES -->
    @stack('styles')
<!-- Bootstrap Dialog -->
    {{--<link rel="stylesheet" type="text/css" media="screen" href="{{asset('assets/bootstrap-dialog/css/bootstrap-dialog.css')}}">--}}
    <link rel="stylesheet" type="text/css" media="screen" href="{{asset('assets/bootstrap-dialog/css/bootstrap-dialog.min.css')}}">

    <!-- BEGIN PAGE STYLES -->
    <link href="{{ url('logbook/metronic/assets/admin/pages/css/tasks.css') }}" rel="stylesheet" type="text/css"/>
    <!-- END PAGE STYLES -->

    <!-- BEGIN PLUGINS USED BY X-EDITABLE -->
    <link rel="stylesheet" type="text/css" href="{{ url('logbook/metronic/assets/global/plugins/select2/select2.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('logbook/metronic/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('logbook/metronic/assets/global/plugins/bootstrap-datepicker/css/datepicker.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('logbook/metronic/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('logbook/metronic/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('logbook/metronic/assets/global/plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('logbook/metronic/assets/global/plugins/bootstrap-editable/inputs-ext/address/address.css') }}"/>
    <!-- END PLUGINS USED BY X-EDITABLE -->

    <!-- BEGIN THEME STYLES -->
    <link href="{{ url('logbook/metronic/assets/global/css/components.css') }}" id="style_components" rel="stylesheet" type="text/css"/>
    <link href="{{ url('logbook/metronic/assets/global/css/plugins.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ url('logbook/metronic/assets/admin/layout/css/layout.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ url('logbook/metronic/assets/admin/layout/css/themes/darkblue.css') }}" rel="stylesheet" type="text/css" id="style_color"/>
    <link href="{{ url('logbook/metronic/assets/admin/layout/css/custom.css') }}" rel="stylesheet" type="text/css"/>
    <!-- END THEME STYLES -->
    {{--<link rel="shortcut icon" href="favicon.ico"/>--}}
    <style>
        /* width */
        ::-webkit-scrollbar {
            width: 5px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            box-shadow: inset 0 0 5px grey;
            border-radius: 5px;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #26a69a;
            border-radius: 5px;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #26a69a;
        }
    </style>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-fixed-mobile" and "page-footer-fixed-mobile" class to body element to force fixed header or footer in mobile devices -->
<!-- DOC: Apply "page-sidebar-closed" class to the body and "page-sidebar-menu-closed" class to the sidebar menu element to hide the sidebar by default -->
<!-- DOC: Apply "page-sidebar-hide" class to the body to make the sidebar completely hidden on toggle -->
<!-- DOC: Apply "page-sidebar-closed-hide-logo" class to the body element to make the logo hidden on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-hide" class to body element to completely hide the sidebar on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-fixed" class to have fixed sidebar -->
<!-- DOC: Apply "page-footer-fixed" class to the body element to have fixed footer -->
<!-- DOC: Apply "page-sidebar-reversed" class to put the sidebar on the right side -->
<!-- DOC: Apply "page-full-width" class to the body element to have full width page without the sidebar menu -->
<body class="page-header-fixed page-quick-sidebar-over-content page-sidebar-closed-hide-logo page-container-bg-solid">
<!-- BEGIN HEADER -->
<div class="page-header -i navbar navbar-fixed-top">

</div>
<!-- END HEADER -->
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">

    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <div class="page-content">


            <!-- BEGIN PAGE HEADER-->
            <div class="page-bar">
                <ul id="page-breadcrumb" class="page-breadcrumb">
                    @yield('crumb')
                </ul>
            </div>
            <h3 id="page-title" class="page-title">
                Data
            </h3>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
            <div class="row">
                <div id="content">
                   <div class="col-md-12">
                       <div class="portlet box blue-hoki">
                           <div class="portlet-title">
                               <div class="caption">
                                   <i class="icon-settings"></i> Default Form Layout
                               </div>
                           </div>
                           <div class="portlet-body form">
                               <div class="skin skin-square">

                                       <div class="form-body">

                                           <div class="form-group">

                                               <div class="col-md-2">
                                                   <label>Table List</label>
                                                   <div class="">
                                                       <div class="icheck-list">
                                                           <label>
                                                               <input type="radio" name="radio1" class="icheck" data-radio="iradio_square-green" value="bank_statement">
                                                               Bank Statement
                                                           </label>
                                                           <label>
                                                               <input type="radio" name="radio1" class="icheck" data-radio="iradio_square-green" value="pdc_line">
                                                               Book Disbursement
                                                           </label>
                                                       </div>
                                                   </div>

                                               </div>

                                               <div class="col-md-9">
                                                   <div class="col-md-12">

                                                           <div class="row">
                                                               <div class="form-group col-sm-4">
                                                                   <div class="input-group">
                                                                       {{--{!! Form::select('company',[''=>'------------------select company-----------------'] + $com,null,['class'=>'form-control','id'=>'com']) !!}--}}
                                                                       {!! Form::select('company',[''=>'------- Select Company -------']+$com,null,['class'=>'form-control','id'=>'com']) !!}
                                                                   </div>
                                                               </div>
                                                               <div class="form-group col-sm-4">
                                                                   <div class="input-group bu-div">
                                                                       <input type="text" disabled="disabled" class="form-control" placeholder="Business Unit">
                                                                   </div>
                                                               </div>
                                                               <div class="form-group col-sm-4">
                                                                   <div class="input-group bank-div">
                                                                       <input type="text" disabled="disabled" class="form-control" placeholder="Bank Account">
                                                                   </div>
                                                               </div>

                                                           </div>

                                                        <div class="clearfix"></div>
                                                   </div>
                                               </div>
                                           </div>

                                       </div>

                                        <div class="col-md-12" style="min-height: 200px; border:1px solid lightgrey">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Inline</label>
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <div class="icheck-inline">
                                                                <label>
                                                                    <input type="checkbox" class="icheck" data-radio="icheck_square-green" value="checkno">
                                                                    Check No
                                                                </label>
                                                                <label>
                                                                    <input type="checkbox"  class="icheck" data-radio="icheck_square-green" value="checkamount">
                                                                    Check Amount
                                                                </label>

                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div style="display: inline-table;">
                                                    <input type="text" class="form-control hidden" name="check_no" id="check_no" style="display: inline-table;" placeholder="Enter check no">
                                                </div>
                                                <div style="display: inline-table;">
                                                    <input type="text" class="form-control hidden" name="check_amt" id="check_amt" style="display: inline-table;" placeholder="Enter check amount">
                                                </div>
                                                <br/>
                                                <br/>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <textarea disabled="disabled" name="" id="query" cols="30" rows="3" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12" id="result">

                                            </div>
                                        </div>
                                   <div class="clearfix"></div>
                                       <div class="form-actions">
                                           <button type="submit" class="btn blue submit">Submit</button>
                                           <button type="button" class="btn default">Cancel</button>
                                       </div>

                               </div>
                           </div>
                       </div>
                   </div>
                </div>
            </div>
            <!-- END PAGE CONTENT-->
            <div class="clearfix">
            </div>
        </div>
    </div>
    <!-- END CONTENT -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="page-footer">
    <div class="page-footer-inner">
        2014 &copy; Metronic by kokoythemes.
    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
</div>
<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="{{ url('logbook/metronic/assets/global/plugins/respond.min.js') }}"></script>
<script src="{{ url('logbook/metronic/assets/global/plugins/excanvas.min.js') }}"></script>
<![endif]-->
<script src="{{ url('logbook/metronic/assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ url('logbook/metronic/assets/global/plugins/jquery-migrate.min.js') }}" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="{{ url('logbook/metronic/assets/global/plugins/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>
<script src="{{ url('logbook/metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ url('logbook/metronic/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') }}" type="text/javascript"></script>
<script src="{{ url('logbook/metronic/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
<script src="{{ url('logbook/metronic/assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
<script src="{{ url('logbook/metronic/assets/global/plugins/jquery.cokie.min.js') }}" type="text/javascript"></script>
<script src="{{ url('logbook/metronic/assets/global/plugins/uniform/jquery.uniform.min.js') }}" type="text/javascript"></script>
<script src="{{ url('logbook/metronic/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
{{--<script src="{{ url('logbook/metronic/global/plugins/jquery.pulsate.min.js') }}" type="text/javascript"></script>--}}
{{--<script src="{{ url('logbook/metronic/global/plugins/bootstrap-daterangepicker/moment.min.js') }}" type="text/javascript"></script>--}}
{{--<script src="{{ url('logbook/metronic/global/plugins/bootstrap-daterangepicker/daterangepicker.js') }}" type="text/javascript"></script>--}}
{{--<!-- IMPORTANT! fullcalendar depends on jquery-ui.min.js for drag & drop support -->--}}
{{--<script src="{{ url('logbook/metronic/global/plugins/fullcalendar/fullcalendar.min.js') }}" type="text/javascript"></script>--}}
{{--<script src="{{ url('logbook/metronic/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js') }}" type="text/javascript"></script>--}}
{{--<script src="{{ url('logbook/metronic/global/plugins/jquery.sparkline.min.js') }}" type="text/javascript"></script>--}}
<!-- END PAGE LEVEL PLUGINS -->
@stack('scripts')

<script src="{{asset('assets/bootstrap-dialog/js/bootstrap-dialog.min.js')}}"></script>

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ url('logbook/metronic/assets/global/scripts/metronic.js') }}" type="text/javascript"></script>
<script src="{{ url('logbook/metronic/assets/admin/layout/scripts/layout.js') }}" type="text/javascript"></script>
<script src="{{ url('logbook/metronic/assets/admin/layout/scripts/quick-sidebar.js') }}" type="text/javascript"></script>
<script src="{{ url('logbook/metronic/assets/admin/layout/scripts/demo.js') }}" type="text/javascript"></script>
<script src="{{ url('logbook/metronic/assets/admin/pages/scripts/index.js') }}" type="text/javascript"></script>
<script src="{{ url('logbook/metronic/assets/admin/pages/scripts/tasks.js') }}" type="text/javascript"></script>


<script src="{{ url('logbook/maskmoney/jquery.maskMoney.js') }}" type="text/javascript"></script>

<script src="{{url('logbook/metronic/assets/global/plugins/icheck/icheck.min.js')}}"></script>
<script src="{{url('logbook/metronic/assets/admin/pages/scripts/form-icheck.js')}}"></script>

<!-- END PAGE LEVEL SCRIPTS -->
<script>
var table    = "";
var com      = "";
var bu       = "";
var checkno  = "";
var checkamt = "";
var bankno   = "";
var buHtml   = $(".bu-div").html();
var bankHtml = $(".bank-div").html();

    $("#com").change(function(){
        var comId = $(this).val();
        com = comId;
        if(comId!='')
        {
            $.ajax({
                type:'get',
                url:'loadBu/'+comId,
                success:function(data)
                {
                    $(".bu-div").html(data);
                }
            })
        }
        else
        {
            $(".bu-div").html(buHtml);
            $(".bank-div").html(bankHtml);
        }

    });

    $(document).on('change','#bu',function(){
        bu = $(this).val();
        if(bu!='')
        {
            $.ajax({
                type: 'get',
                url: '{{url('bankact')}}/' + com + "/" + bu,
                success: function (data) {
                    $(".bank-div").html(data);
                }
            });
        }
        else
        {
            $(".bank-div").html(bankHtml);
        }
    });

    $(document).on('change','#bankact',function(){
        id = $(this).val();
        //console.log(id);
        if(id!='')
        {
            $.ajax({
                type:'get',
                url:'{{url('bankno')}}/'+id,
                success:function(data)
                {
                    bankno = data.trim();
                    console.log(bankno);
                }
            })
        }
        else
        {
            bankno="";
        }


    });

    $('input[type=radio]').on('ifChecked', function(event){
        table=$(this).val().trim();
    });

    $('input[type=checkbox]').on('ifChecked', function(event){
        if($(this).val().trim()=='checkno')
        {
            $("#check_no").removeClass("hidden")
        }
        else
        {
            $("#check_amt").removeClass("hidden")
        }
    });

    $('input[type=checkbox]').on('ifUnchecked', function(event){
        if($(this).val().trim()=='checkno')
        {
            $("#check_no").addClass("hidden")
        }
        else
        {
            $("#check_amt").addClass("hidden")
        }
    });

    $("#check_no").keyup(function(){
        checkno = $(this).val();
    });

    $("#check_amt").keyup(function(){
        checkamt = $(this).val();
    });

    function Thing(table,com,bu,checkno,checkamt)
    {
        this.name = name;
    }
    Thing.prototype.doSomething = function(callback)
    {
        // Call our callback, but using our own instance as the context
        //callback.call(this);
        callback.apply(this, ['Hi', 3, 2, 1]);
    }

    function foo()
    {
        var fieldcheck    = "";
        var fieldcheckamt = "";
        var bankaccount   = "";
        var query         = "";
        if(table=='bank_statement')
        {
            fieldcheck    = "bank_check_no";
            fieldcheckamt = "bank_amount";
            bankaccount   = "bank_account_no";
        }
        else if(table=='pdc_line')
        {
            fieldcheck    = "check_no";
            fieldcheckamt = "check_amount";
            bankaccount   = "baccount_no";
        }
        else
        {
            return alert("Please select table!");
        }

        if(com=='')
        {
            return alert("Please select company!");
        }

        if(bu=='')
        {
            return alert("Please select business unit!");
        }

        if(bankno == '')
        {
            return alert("Please select Bank Account");
        }

        if($("#check_no").hasClass("hidden") && $("#check_amt").hasClass("hidden")==false)
        {
            query = "select * from "+ table + " where "+fieldcheckamt+"="+checkamt.replace(/\,/g,'').trim()+" and "+bankaccount+"='"+bankno+"' and company="+com+" and bu_unit='"+bu+"'";
        }
        else if($("#check_no").hasClass("hidden")==false && $("#check_amt").hasClass("hidden"))
        {
            query = "select * from "+ table + " where "+fieldcheck+"='"+ checkno +"' and "+bankaccount+"='"+bankno+"' and company="+com+" and bu_unit='"+bu+"'";
        }
        else if($("#check_no").hasClass("hidden")==false && $("#check_amt").hasClass("hidden")==false)
        {
            query = "select * from "+ table + " where "+fieldcheck+"='"+ checkno +"' and "+fieldcheckamt+"="+checkamt.replace(/\,/g,'').trim()+" and "+bankaccount+"='"+bankno+"' and company="+com+" and bu_unit='"+bu+"'";
        }
        else
        {
            query = "select * from "+ table + " where "+bankaccount+"='"+bankno+"' and company="+com+" and bu_unit='"+bu+"'"
        }

        $("#query").text(query);
        var data = [table,com,bu,bankno,checkno,checkamt];
        $.ajax({
            type:'post',
            data:{data:data,_token:'{{csrf_token()}}'},
            url:'getresult',
            success:function(result)
            {
                $("#result").html(result);
                //console.log(result);
            }
        });
    }

    $(".submit").click(function(){
        // alert($(".checked input[name=radio1]").val());
        checkamt = checkamt.replace(/\,/g,'');
        var t = new Thing(table,com,bu,checkno,checkamt,bankno);
        t.doSomething(foo);
    });

</script>
<script>
    jQuery(document).ready(function() {
        Metronic.init(); // init metronic core componets
        Layout.init(); // init layout
        QuickSidebar.init(); // init quick sidebar
        Demo.init(); // init demo features
        Index.init();
        Index.initDashboardDaterange();
        Index.initJQVMAP(); // init index page's custom scripts
        Index.initCalendar(); // init index page's custom scripts
        Index.initCharts(); // init index page's custom scripts
        Index.initChat();
        Index.initMiniCharts();
        Tasks.initDashboardWidget();

    });


</script>
<script src="{{ url('logbook/AjaxAndScripts/AjaxAndScripts.js') }}" type="text/javascript"></script>
<!-- END JAVASCRIPTS -->
<!-- START LARRY SCRIPTS -->
<script type="text/javascript" src="{{ url('logbook/larry-scripts/nav.js') }}"></script>
<!-- END LARRY SCRIPTS -->
</body>
<!-- END BODY -->
</html>