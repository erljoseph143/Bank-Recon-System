
<style>
    .modal-header .close {
        margin-top: -2px;
        height: 30px;
        width: 40px;
        border: 1px solid black;
    }
    body.smart-style-6 .form-control, body.smart-style-6 .smart-form .icon-append, body.smart-style-6 .smart-form .icon-prepend, body.smart-style-6 .smart-form .input input, body.smart-style-6 .smart-form .select select, body.smart-style-6 .smart-form .textarea textarea {
        border-top-width: 1;
        border-left-width: 1;
        border-right-width: 1;
    }
    input[type=text].ui-autocomplete-loading
    {


        background-position: 92% 50%;
        padding-right: 27px;
    }
    .alert-success {
        border-color: #8ac38b;
        color: #356635;
        background-color: #cde0c4;
    }
    body.smart-style-6 .alert-success, body.smart-style-6 .btn-success {
        border-color: #388E3C;
        color: #FFF;
        background-color: #D6EDCC;
        color:#3c763d;
        font-size:large;
    }

    body.smart-style-6 .form-control,

    body.smart-style-6
    .smart-form
    .icon-prepend,
    body.smart-style-6
    .smart-form .select
    select,
    body.smart-style-6
    .smart-form .textarea textarea {
        border-top-width: 1;
         border-left-width: 0;
         border-right-width: 0;
    }

    .form-drop
    {
        position:absolute;
        z-index:1000;
        border:1px solid black;
        background-color: black;
        opacity:.5;
        left:0;
    }
</style>
<div class="jarviswidget jarviswidget-sortable" id="wid-id-3" data-widget-editbutton="false" data-widget-custombutton="false" role="widget">

    <header role="heading">
        <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
        <h2>Manual Data Entry of Daily Bank Statement </h2>





        <span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span></header>

    <!-- widget div-->
    <div role="content">

        <!-- widget edit box -->
        <div class="jarviswidget-editbox">
            <!-- This area used as dropdown edit box -->

        </div>
        <!-- end widget edit box -->

        <!-- widget content -->
        <div class="widget-body no-padding">

            <form id="form-save" method="post" class="smart-form" novalidate="novalidate" action="saveBS">

                {{csrf_field()}}
                <fieldset>
                    <div class="row">
                        <section class="col-md-12">
                            <span  class="help-block btn-info padding-5">
                                * Please Select First the Bank Accounts
                            </span>
                            <label for="bsAct" class="input" id="bankActlabel">

                                <i class="icon0append glyphicon glyphicon-bank"> </i>
                                    {{Form::select('bankact',[''=>'---------------------------select bank account---------------------------'] + $bankAct,null,['class'=>'form-control','id'=>'bsAct'])}}
                                <span class="help-block hidden" id="bankActError">
                                    <i class="fa fa-warning "></i>
                                    * Please Select Bank Account!
                                </span>
                            </label>

                        </section>
                    </div>
                </fieldset>

                <fieldset id="parent-field">
                    <div id="drop" class="form-drop">

                    </div>
                    <div class="row">
                        <section class="col col-6">
                            <span class="help-block btn-info padding-5">
                                    * Bank Date
                            </span>
                            <label class="input" id="bankdatelabel"> <i class="icon-append glyphicon glyphicon-calendar"></i>
                                {{--<input type="text" name="bankDate" placeholder="Bank Date. . .">--}}
                                <input type="text" name="bankdate" class="form-control" placeholder="Select a date"  id="datepicker">
                                <span class="help-block hidden" id="bankdateError">
                                    <i class="fa fa-warning "></i>
                                    * Please input Bank Date!
                                </span>
                            </label>
                        </section>
                        <section class="col col-6">
                            <span class="help-block btn-info padding-5">
                                * Credit Amount
                            </span>
                            <label class="input" id="creditlabel"> <i class="icon-append">&#8369;</i>
                                <input type="text" name="credit"  id="credit"  placeholder="Credit" autocomplete="off" data-inputmask="'alias': 'numeric','groupSeparator': ',','autoGroup': true,'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" required>
                                <span class="help-block hidden" id="creditError">
                                    <i class="fa fa-warning "></i>
                                    * Please input Credit Amount!
                                </span>
                            </label>
                        </section>

                    </div>

                    <div class="row">
                        <section class="col col-6">
                            <span class="help-block btn-info padding-5">
                                * Description
                            </span>
                            <label class="input" id="deslabel"> <i class="icon-append glyphicon glyphicon-subtitles"></i>
                                <input type="text" id="des" name="des" placeholder="Description . . .">
                            </label>
                        </section>
                        <section class="col col-6">
                            <span class="help-block btn-info padding-5">
                                * Debit Amount
                            </span>
                            <label class="input" id="debitlabel"> <i class="icon-append ">&#8369;</i>
                                <input type="text" name="debit" id="debit" placeholder="Debit" autocomplete="off" data-inputmask="'alias': 'numeric','groupSeparator': ',','autoGroup': true,'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" required>
                                <span class="help-block hidden" id="debitError">
                                    <i class="fa fa-warning "></i>
                                    * Please input Debit Amount!
                                </span>
                            </label>
                        </section>
                    </div>

                    <div class="row">
                        <section class="col col-6">
                            <span class="help-block btn-info padding-5">
                                    * Check No
                            </span>
                            <label class="input " id="checklabel"> <i class="icon-append glyphicon glyphicon-saved"></i>
                                {{--<input type="text" name="checkno" id="checkno" placeholder="Check No">--}}
                                {{--<input type="text" name="checkno" id="checkno" placeholder="Check No" autocomplete="off" data-inputmask="'alias': 'numeric','groupSeparator': ',','autoGroup': true,'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" required>--}}
                                <input type="text" name="checkno" class="form-control " id="checkno" placeholder="Check No. . ." autocomplete="off" data-inputmask="'alias': 'integer', 'allowMinus':false" required>
                                <span class="help-block hidden" id="checkError">
                                    <i class="fa fa-warning "></i>
                                    Check No. not exist in book record
                                </span>
                            </label>
                        </section>


                        <section class="col col-6">
                            <span class="help-block btn-info padding-5">
                                * Bank Balance
                            </span>
                            <label class="input" id="bankballabel"> <i class="icon-append">&#8369;</i>
                                <input type="text" name="bankbalance"  id="bankbalance"  placeholder="Bank Balance" autocomplete="off" data-inputmask="'alias': 'numeric','groupSeparator': ',','autoGroup': true,'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" required>
                                <span class="help-block hidden" id="bankbalError">
                                    <i class="fa fa-warning "></i>
                                    * Please input Bank Balance!
                                </span>
                            </label>
                        </section>
                    </div>

                </fieldset>


                <footer>
                    <button class="btn btn-primary" type="submit" id="saveBS">
                        Submit
                    </button>
                </footer>
            </form>

            <div class="response">

            </div>

        </div>
        <!-- end widget content -->

    </div>
    <!-- end widget div -->

</div>

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

function showErrorMessage(message)
{
    var dialog = new BootstrapDialog({
        message: function(dialogRef){
            var $message = $('<div>fdsf</div>');
            return $message;
        },
        closable: false
    });
    dialog.realize();
    dialog.getModalHeader().hide();
    dialog.getModalFooter().hide();
    dialog.getModalBody().css('background-color', '#0088cc');
    dialog.getModalBody().css('color', '#fff');
    dialog.open();
    setTimeout(function(){
        dialog.close();
    }, 1500);
//    setTimeout(function(){
//        window.location.href = 'gcreleased-institutions-pdf.php?id='+data['id'];
//    }, 1700);

}

    // pagefunction
    var pagefunction = function() {
//        $.ajaxSetup({
//            headers:{
//                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//            }
//        });

        var heightParent = $('#drop').closest('#parent-field').height();
        var widthParent  = $('#drop').closest('#parent-field').width();

        emheight = heightParent * 0.0625;
        emwidth  = widthParent * 0.0625;

       // console.log(heightParent+ ' => ' + xdat);

        $("#drop").css("height",heightParent);
        $("#drop").css("width","100%");
        $(":input, a").attr("tabindex", "-1");

        $("#bsAct").on("change",function(){
            if($(this).val()!="")
            {
                $("#drop").fadeOut("slow");
                $(":input").removeAttr("tabindex");

            }
            else
            {
                $("#drop").fadeIn("slow");
                $(":input, a").attr("tabindex", "-1");
                $(":input").val("");
                $("#checklabel").removeClass("has-error");
                $("#checkError").addClass("hidden");

            }
        });




        $('#checkno').inputmask();
        $('#debit').inputmask();
        $('#credit').inputmask();
        $('#bankamount').inputmask();
        $('#bankbalance').inputmask();
        $('#checkno').css("text-align","left");




    };
/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------
* FOCUS OUT EVENT
*------------------------------------------------------------------------------------------------------------------------------------------------------------------------
* */

    $("#checkno").focusout(function(){
        var checkno = $(this).val();
        if(checkno.trim()!="")
        {
            $(this).addClass("ui-autocomplete-loading");
            $.ajax({
                type:'get',
                url:'checkTheCheck/'+checkno,
                success:function(data)
                {

                    if(data.trim() == 0)
                    {
                        $("#checklabel").addClass("has-error");
                        $("#checkError").removeClass("hidden");
                    }
                    else
                    {

                        $("#checklabel").removeClass("has-error");
                        $("#checkError").addClass("hidden");
                    }
                }
            });
            $(this).removeClass("ui-autocomplete-loading");
        }
        else
        {

            $("#checklabel").removeClass("has-error");
            $("#checkError").addClass("hidden");
        }
    });

    $("#checkno").val($("#searchCheck").val());

    $("#debit").focusout(function() {
        if($(this).val() !=0.00)
        {
            $("#credit").val("0.00");
            $("#credit").prop("disabled",true);
        }
        else{
            $("#credit").prop("disabled",false);
        }

    });

    $("#credit").focusout(function() {
        if($(this).val() !=0.00)
        {
            $("#debit").val("0.00");
            $("#debit").prop("disabled",true);
        }
        else{
            $("#debit").prop("disabled",false);
        }

    });
/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
 * ON BLUR EVENT
 *------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
 * */

    $("#datepicker").blur(function(){
        $("#bankdatelabel").removeClass("has-error");
        $("#bankdateError").addClass("hidden");
    });
    $("#credit").blur(function(){
        $("#creditlabel").removeClass("has-error");
        $("#creditError").addClass("hidden");
    });
    $("#debit").blur(function(){
        $("#debitlabel").removeClass("has-error");
        $("#debitError").addClass("hidden");
    });
    $("#bankbalance").blur(function(){
        $("#bankballabel").removeClass("has-error");
        $("#bankbalError").addClass("hidden");
    });

    $("#bsAct").blur(function(){
        $("#bankActlabel").removeClass("has-error");
        $("#bankActError").addClass("hidden");
    });



/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
* FORM SUMBIT
*------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
* */



    $("#form-save").on('submit',function(e){
        e.preventDefault();
        var bankdate    = $("#datepicker").val();
        var des         = $("#des").val();
        var checkno     = $("#checkno").val();
        var debit       = $("#debit").val();
        var credit      = $("#credit").val();
        var bankbalance = $("#bankbalance").val();
        var bankAct     = $("#bsAct").val();

            if(bankdate =="" || bankdate ==null)
            {
                $("#bankdatelabel").addClass("has-error");
                $("#bankdateError").removeClass("hidden");
                var heightParent = $('#drop').closest('#parent-field').height();
                var widthParent  = $('#drop').closest('#parent-field').width();

                $("#drop").css("height",heightParent);
                $("#drop").css("width",widthParent);
                $(":input, a").attr("tabindex", "-1");
            }
            else
            {

                $("#bankdatelabel").removeClass("has-error");
                $("#bankdateError").addClass("hidden");
            }

            if(debit =="0.00" && credit=="0.00")
            {
                $("#debitlabel").addClass("has-error");
                $("#debitError").removeClass("hidden");
                $("#creditlabel").addClass("has-error");
                $("#creditError").removeClass("hidden");
                var heightParent = $('#drop').closest('#parent-field').height();
                var widthParent  = $('#drop').closest('#parent-field').width();

                $("#drop").css("height",heightParent);
                $("#drop").css("width",widthParent);
                $(":input, a").attr("tabindex", "-1");
            }
            else
            {

                $("#debitlabel").removeClass("has-error");
                $("#debitError").addClass("hidden");
                $("#creditlabel").removeClass("has-error");
                $("#creditError").addClass("hidden");
            }

            if(bankbalance =="0.00")
            {
                $("#bankballabel").addClass("has-error");
                $("#bankbalError").removeClass("hidden");
                var heightParent = $('#drop').closest('#parent-field').height();
                var widthParent  = $('#drop').closest('#parent-field').width();

                $("#drop").css("height",heightParent);
                $("#drop").css("width",widthParent);
                $(":input, a").attr("tabindex", "-1");
            }
            else
            {

                $("#bankballabel").removeClass("has-error");
                $("#bankbalError").addClass("hidden");
            }

            if(bankAct =="")
            {
                $("#bankActlabel").addClass("has-error");
                $("#bankActError").removeClass("hidden");
                var heightParent = $('#drop').closest('#parent-field').height();
                var widthParent  = $('#drop').closest('#parent-field').width();


            }
            else
            {

                $("#bankActlabel").removeClass("has-error");
                $("#bankActError").addClass("hidden");
            }
//console.log(credit);
            if(bankdate!="" && (credit!="0.00" || debit!="0.00") && bankbalance!="0.00")
            {
                $.ajax({
                        type:'POST',
                        data:{bankdate:bankdate,des:des,checkno:checkno,debit:debit,credit:credit,bankbalance:bankbalance,bankaccount:bankAct},
                        url:'saveBS',
                        success:function(data)
                        {
                            // alert("successfull");
                        //    console.log(data);
                            $(".response").html('<div class="alert alert-success" id="show-message"> ' +
                                '<i class="glyphicon glyphicon-check" ></i>Successfully Save </div>');
                            setTimeout(function(){
                                $(".response").fadeOut("slow");
                            },1000);
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
                    })
            }
    });
    // load related plugins


    loadScript("js/jquery.inputmask.bundle.min.js",pagefunction);
    //loadScript("jquery-ui-1.12.1.custom/jquery-ui.js",newfunc);
  //  loadScript("js/plugin/bootstrap-timepicker/bootstrap-timepicker.min.js");
    $("#datepicker").datepicker();

</script>


