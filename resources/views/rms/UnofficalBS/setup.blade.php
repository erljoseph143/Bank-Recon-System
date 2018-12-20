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
                <header>
                    <span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
                    <h2> </h2>
                </header>
                <!-- widget div-->
                <div class="no-padding" style="height:auto;">
                    <!-- widget edit box -->

                    <!-- end widget edit box -->

                        <div class="widget-body padding-10">
                            <div class="row">
                                <div class="col-md-5 bordered" style="height: 10em;margin-right: 10px;margin-left: 7%;padding-top:30px">

                                    <div class="input-group col-md-12">
                                        <label class=""> Select Bank</label>
                                        <select name="bank" id="bank" class="form-control">
                                            <option value="BPI">BPI</option>
                                            <option value="PNB">PNB</option>
                                            <option value="BDO">BDO</option>
                                            <option value="UCPB">UCPB</option>
                                            <option value="Metro Bank">Metro Bank</option>
                                        </select>
                                    </div>
                                    <div class="input-group">

                                    </div>


                                </div>
                                <div class="col-md-5 bordered" style="height: 25em">

                                </div>
                            </div>
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
