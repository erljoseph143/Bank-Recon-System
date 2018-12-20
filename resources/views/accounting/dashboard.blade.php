<style>
    .col-md-offset-1 {
        margin-left: 1.333333%;
    }
    @media only screen and (max-width:990px)
    {
        .col-md-offset-1 {
            margin-left: 0%;
        }
    }
    .dimension
    {
        height: 120px;text-align: center; padding: 50px;border-color: #03A9F4;
        color: #01579B;
        background-color: #B3E5FC;
    }
</style>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> Bank Reconcillation Module <span></span></h1>
    </div>
    <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">

    </div>
</div>
<!-- widget grid -->
<section id="widget-grid" class="">

    <!-- row -->
    <div class="row">
        <article class="col-sm-12">
            <!-- new widget -->
            <div class="jarviswidget" id="wid-id-0" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
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
                <header>
                    <span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
                    <h2> </h2>



                </header>

                <!-- widget div-->
                <div class="no-padding" style="height:350px;">
                    <!-- widget edit box -->

                    <!-- end widget edit box -->

                    <div class="widget-body">
                        <!-- content -->
                        <div class="col-md-12">
                            <button class="btn btn-info" id="view_upload_bs" style="margin-left:1%">
                                <i class="fa fa-file-text"></i>
                                View Uploaded Bank Statement
                            </button>
                        </div>

                        <div id="version" class="col-md-12" style="padding:77px">
                            <div id="nav" class="col-md-4 col-md-offset-2 bordered dimension" style="" >
                                Navision
                            </div>

                            <div id="DsgX" class="col-md-4 col-md-offset-1 bordered dimension" style="">
                                Designex
                            </div>
                        </div>

                        <div id="Navision" class="col-md-4 col-md-offset-4 bordered dimension hidden" style="top:75px" >
                            Upload Check Voucher from Navision
                        </div>
                        <div id="Designex" class="col-md-4 col-md-offset-4 bordered dimension hidden" style="top:75px" >
                            Upload Check Voucher from Designex
                        </div>
                        {{--<div id="Navision" class="tab-content hidden">--}}
                            {{--<div class="tab-pane fade active in padding-10 no-padding-bottom" id="s1">--}}

                                {{--<div id="up_cv" class="col-sm-4 col-sm-offset-4" style="margin-top:10%;">--}}

                                    {{--<div class="well well-sm bg-color-teal txt-color-white text-center">--}}
                                        {{--<h3>Upload Check Voucher from Navision</h3>--}}

                                    {{--</div>--}}

                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div id="Designex" class="tab-content hidden">--}}
                            {{--<div class="tab-pane fade active in padding-10 no-padding-bottom" >--}}

                                {{--<div id="up_cv" class="col-sm-4 col-sm-offset-4" style="margin-top:10%;">--}}

                                    {{--<div class="well well-sm bg-color-teal txt-color-white text-center">--}}
                                        {{--<h3>Upload Check Voucher from Designex</h3>--}}

                                    {{--</div>--}}

                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        <!-- end content -->
                    </div>

                </div>
                <!-- end widget div -->
            </div>
            <!-- end widget -->

        </article>
    </div>

    <!-- end row -->

    <!-- row -->



    <!-- end row -->

</section>
<!-- end widget grid -->
<script>

    $("#nav").click(function(){
        $("#version").fadeOut("slow");
        $("#Navision").fadeIn("slow",function(){
            $("#Navision").removeClass("hidden");
        });

    });

    $("#DsgX").click(function(){
        $("#version").fadeOut("slow");
        $("#Designex").fadeIn("slow",function(){
            $("#Designex").removeClass("hidden");
        });
    });

    $("#Navision").click(function () {


        BootstrapDialog.show({
            title: '',
            message: $('<div></div>').load('check_voucher',function(e,st){

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
                }),
            type: BootstrapDialog.TYPE_INFO,
            closable:false,
            buttons: [{
                label: 'Close',
                icon: 'glyphicon glyphicon-remove',
                cssClass: 'btn btn-flat btn-danger',
                action: function (dialogRef) {
                    dialogRef.close();
                }
            }]
        });

    });

    $("#Designex").click(function () {


        BootstrapDialog.show({
            title: '',
            message: $('<div></div>').load('dXcheck_voucher',function(e,st){

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
                }),
            type: BootstrapDialog.TYPE_INFO,
            closable:false,
            buttons: [{
                label: 'Close',
                icon: 'glyphicon glyphicon-remove',
                cssClass: 'btn btn-flat btn-danger',
                action: function (dialogRef) {
                    dialogRef.close();
                }
            }]
        });

    });

    $("#view_upload_bs").click(function () {


        BootstrapDialog.show({
            title: '',
            message: $('<div></div>').load('viewUpBS',function(e,st){

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
                }),
            type: BootstrapDialog.TYPE_INFO,
            size: BootstrapDialog.SIZE_WIDE,
            closable:false,
            buttons: [{
                label: 'Close',
                icon: 'glyphicon glyphicon-remove',
                cssClass: 'btn btn-flat btn-danger',
                action: function (dialogRef) {
                    dialogRef.close();
                }
            }]
        });

    });
</script>


