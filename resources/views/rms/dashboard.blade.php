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
                        <div id="myTabContent" class="tab-content">
                            <div class="tab-pane fade active in padding-10 no-padding-bottom" id="s1">

                                <div id="up_bs" class="col-sm-4 col-sm-offset-4" style="margin-top:10%;">

                                    <div class="well well-sm bg-color-teal txt-color-white text-center">
                                        <h3>Upload Bank Statement</h3>

                                    </div>

                                </div>



                            </div>
                            <!-- end s1 tab pane -->


                            <!-- end s2 tab pane -->

                        </div>

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

<script type="text/javascript">
    $(document).ready(function () {


        var dataString = "";
        $("#up_bs").click(function () {


//            $.ajax({
//                type:"GET",
//                url:"bankstatement",
//                success:function(data)
//                {
//                 // console.log(data);
//                  dataString = data;
//                }
//            });


            BootstrapDialog.show({
                title: 'Bank Statement',
                message: $('<div></div>').load('bankstatement',function(e,st){

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
                type: BootstrapDialog.TYPE_SUCCESS,

                buttons: [{
                    label: 'Close',
                    icon: 'glyphicon glyphicon-remove',
                    cssClass: 'btn btn-flat btn-danger',
                    action: function (dialogRef) {
                        dialogRef.close();
                    }
                }]
            });
        })
    });

</script>
