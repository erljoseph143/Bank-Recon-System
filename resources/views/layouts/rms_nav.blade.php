<!-- NAVIGATION : This navigation is also responsive

To make this navigation dynamic please make sure to link the node
(the reference to the nav > ul) after page load. Or the navigation
will not initialize.
-->
<nav>
    <!--
    NOTE: Notice the gaps after each icon usage <i></i>..
    Please note that these links work a bit different than
    traditional href="" links. See documentation for details.
    -->

    <ul>
        <li class="">
            <a href="#" title="Dashboard"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Dashboard</span></a>
            <ul>
                <li class="">
                    <a href="#" title="Dashboard" id="load-dashboard-rms"><span class="menu-item-parent">Dashboard</span></a>
                </li>
            </ul>
        </li>
        <li class="">
            <a href="#"><i class="fa fa-lg fa-fw fa-cube txt-color-blue"></i> <span class="menu-item-parent">Option</span></a>
            <ul>
                <li class="">
                    <a href="#" id="bs" onclick="BSperCom()" title="Uploaded Bank Statement"> <span class="menu-item-parent">Bank Statement Uploaded</span></a>
                </li>

            </ul>
        </li>




    </ul>
</nav>

<script>
    function BSperCom()
    {
        $("#content").html("<div style='position:relative;min-height:38em;width:100%'>" +
            "<div style='position:absolute;min-height:37.7em;width:100%;background-image:url(\"img/ajax-loader.gif\");background-repeat:no-repeat;background-position: center'></div></div>");
        $.ajax({
            type:'get',
            url:'BSperCom',
            success:function (data)
            {
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
    }
</script>