<!-- NAVIGATION : This navigation is also responsive-->
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
				{{--<a href="#" id="load-dashboard-acct"  title="Dashboard"><span class="menu-item-parent">View Uploaded Bank Statement</span></a>--}}
                    <a href="#" id="view_upload_bs"  title="Dashboard"><span class="menu-item-parent">View Uploaded Bank Statement</span></a>
                </li>
				{{--<li class="">
                    <a href="#" onclick="dataCV()" id="load-dashboard-acct1"  title="Dashboard"><span class="menu-item-parent">CV middleware</span></a>
                </li>--}}
				<li class="">
                    <a href="#" onclick="excelCV()" id="excelCV"  title="Dashboard"><span class="menu-item-parent">CV Excel</span></a>
                </li>
            </ul>
        </li>
		
		@if(strtolower($bu)==strtolower("COLONNADE- MANDAUE"))
			<li class="">
				<a href="#" title="Checking Accounts"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Checking Accounts</span></a>
				<ul>
					<li class="">
						<a href="colacct/checking_accounts" id="load-CheckingAccounts-acct"  title="Checking Accounts"><span class="menu-item-parent">Checking Accounts</span></a>
					</li>
				</ul>
			</li>	
		@else
        <li class="">
            <a href="#"><i class="fa fa-lg fa-fw fa-cube"></i> <span class="menu-item-parent">Option</span></a>
            <ul>
                <li class="">
                    <a href="#" onclick="viewDis()" title="Deposit Viewing"> <span class="menu-item-parent">View Disbursement</span></a>
                </li>
                <li>
                    <a href="#" onclick="searchCheck()">Find BS Check in Book </a>
                </li>
                <li>
                    <a href="#" onclick="inputBsData()">View BS Data Inputted </a>
                </li>
                <li>
                    <a href="#" onclick="manual_dis_summary()">Manual BS Summary Reports </a>
                </li>
            </ul>
        </li>

        {{--<li>--}}
            {{--<a href="#"><i class="fa fa-lg fa-fw fa-inbox"></i> <span class="menu-item-parent">Daily Bank Statement</span> </a>--}}
            {{--<ul>--}}

            {{--</ul>--}}
        {{--</li>--}}
        <li>
            <a href="#"><i class="fa fa-lg fa-fw fa-inbox"></i> <span class="menu-item-parent">Disbursement</span> </a>
            <ul>
                <li>
                    <a href="#" onclick="matchCheck()">Matched Check </a>
                </li>
                <li>
                    <a href="#" onclick="unMatchCheck()">No Matched Check </a>
                </li>
                <li>
                    <a href="#" onclick="ocCheck()">Outstanding Check </a>
                </li>
                <li>
                    <a href="#" onclick="dmCheck()">Debit Memos </a>
                </li>
                <li>
                    <a href="#" onclick="PDC_DMCheck()">DC/PDC </a>
                </li>
                <li>
                    <a href="#" onclick="cancelCheck()">Cancelled Check </a>
                </li>
                <li>
                    <a href="#" onclick="postedCheck()">Posted Check </a>
                </li>
                <li>
                    <a href="#" onclick="staleCheck()">Stale Check </a>
                </li>
            </ul>
        </li>
		<li>
            <a href="#"><i class="fa fa-lg fa-fw fa-pencil-square-o"></i> <span class="menu-item-parent">Reports</span></a>
            <ul>
			@if(strtolower($bu)!=strtolower("COLONNADE- MANDAUE"))
                <li>
                    <a href="#" onclick="loadDis()">Disbursement Summary Reports</a>
                </li>
			@endif
                <li>
                    <a href="#" onclick="reconItems()">Reconciling Items</a>
                </li>
            </ul>
        </li>
		<li>
            <a href="dtr/accountingDTR"><i class="fa fa-lg fa-fw fa-pencil-square-o"></i> <span class="menu-item-parent">DTR</span></a>
        </li>
        <li>
            <a href="deposit/deplist"><i class="fa fa-lg fa-fw fa-pencil-square-o"></i> <span class="menu-item-parent">Deposit Matching</span></a>
        </li>			
		@endif


    </ul>
</nav>
<script>
    /*
    * Option function
    * */
    function loadDis()
    {
        $("#content").html("<div style='position:relative;min-height:38em;width:100%'>" +
            "<div style='position:absolute;min-height:37.7em;width:100%;background-image:url(\"img/ajax-loader.gif\");background-repeat:no-repeat;background-position: center'></div></div>");
        $.ajax({
            type:'get',
            url:'report_dis_summary',
            success:function(data)
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

    function manual_dis_summary()
    {
        $("#content").html("<div style='position:relative;min-height:38em;width:100%'>" +
            "<div style='position:absolute;min-height:37.7em;width:100%;background-image:url(\"img/ajax-loader.gif\");background-repeat:no-repeat;background-position: center'></div></div>");
        $.ajax({
            type:'get',
            url:'manual_dis_summary',
            success:function(data)
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

    function searchCheck()
    {
        $("#content").html("<div style='position:relative;min-height:38em;width:100%'>" +
            "<div style='position:absolute;min-height:37.7em;width:100%;background-image:url(\"img/ajax-loader.gif\");background-repeat:no-repeat;background-position: center'></div></div>");

        $.ajax({
            type:'get',
            url:'findCheck',
            success:function(data)
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

    function inputBsData()
    {
        $("#content").html("<div style='position:relative;min-height:38em;width:100%'>" +
            "<div style='position:absolute;min-height:37.7em;width:100%;background-image:url(\"img/ajax-loader.gif\");background-repeat:no-repeat;background-position: center'></div></div>");
        $.ajax({
            type:'get',
            url:'inputBS',
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
    function viewDis()
    {
        $("#content").html("<div style='position:relative;min-height:38em;width:100%'>" +
            "<div style='position:absolute;min-height:37.7em;width:100%;background-image:url(\"img/ajax-loader.gif\");background-repeat:no-repeat;background-position: center'></div></div>");
        $.ajax({
            type:'get',
            url:'viewDis',
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
    /*
    * Disbursement Functions
    * */
    function matchCheck()
    {
        $("#content").html("<div style='position:relative;min-height:38em;width:100%'>" +
            "<div style='position:absolute;min-height:37.7em;width:100%;background-image:url(\"img/ajax-loader.gif\");background-repeat:no-repeat;background-position: center'></div></div>");
        $.ajax({
            type:'get',
            url:'matchCheck',
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

    function unMatchCheck()
    {
        $("#content").html("<div style='position:relative;min-height:38em;width:100%'>" +
            "<div style='position:absolute;min-height:37.7em;width:100%;background-image:url(\"img/ajax-loader.gif\");background-repeat:no-repeat;background-position: center'></div></div>");
        $.ajax({
            type:'get',
            url:'unMatchCheck',
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
    function ocCheck()
    {
        $("#content").html("<div style='position:relative;min-height:38em;width:100%'>" +
            "<div style='position:absolute;min-height:37.7em;width:100%;background-image:url(\"img/ajax-loader.gif\");background-repeat:no-repeat;background-position: center'></div></div>");
        $.ajax({
            type:'get',
            url:'ocCheck',
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
    function dmCheck()
    {
        $("#content").html("<div style='position:relative;min-height:38em;width:100%'>" +
            "<div style='position:absolute;min-height:37.7em;width:100%;background-image:url(\"img/ajax-loader.gif\");background-repeat:no-repeat;background-position: center'></div></div>");
        $.ajax({
            type:'get',
            url:'dmCheck',
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
    function PDC_DMCheck()
    {
        $("#content").html("<div style='position:relative;min-height:38em;width:100%'>" +
            "<div style='position:absolute;min-height:37.7em;width:100%;background-image:url(\"img/ajax-loader.gif\");background-repeat:no-repeat;background-position: center'></div></div>");
        $.ajax({
            type:'get',
            url:'PDC_DMCheck',
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
    function cancelCheck()
    {
        $("#content").html("<div style='position:relative;min-height:38em;width:100%'>" +
            "<div style='position:absolute;min-height:37.7em;width:100%;background-image:url(\"img/ajax-loader.gif\");background-repeat:no-repeat;background-position: center'></div></div>");
        $.ajax({
            type:'get',
            url:'CancelledCheck',
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
    function postedCheck()
    {
        $("#content").html("<div style='position:relative;min-height:38em;width:100%'>" +
            "<div style='position:absolute;min-height:37.7em;width:100%;background-image:url(\"img/ajax-loader.gif\");background-repeat:no-repeat;background-position: center'></div></div>");
        $.ajax({
            type:'get',
            url:'PostedCheck',
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

    function staleCheck()
    {
        $("#content").html("<div style='position:relative;min-height:38em;width:100%'>" +
            "<div style='position:absolute;min-height:37.7em;width:100%;background-image:url(\"img/ajax-loader.gif\");background-repeat:no-repeat;background-position: center'></div></div>");
        $.ajax({
            type:'get',
            url:'staleCheck',
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

    function reconItems()
    {
        $("#content").html("<div style='position:relative;min-height:38em;width:100%'>" +
            "<div style='position:absolute;min-height:37.7em;width:100%;background-image:url(\"img/ajax-loader.gif\");background-repeat:no-repeat;background-position: center'></div></div>");
        $.ajax({
            type:'get',
            url:'ReconItems',
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

    function dataCV()
    {
        BootstrapDialog.show({
            type:BootstrapDialog.TYPE_INFO,
            title:'CV Middleware',
            message:$('<div></div>').load('dataCV'),
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
	
	function excelCV()
    {
        $("#content").html("<div style='position:relative;min-height:38em;width:100%'>" +
            "<div style='position:absolute;min-height:37.7em;width:100%;background-image:url(\"img/ajax-loader.gif\");background-repeat:no-repeat;background-position: center'></div></div>");
        $.ajax({
            type:'get',
            url:'acctCVBankList/{{\Illuminate\Support\Facades\Auth::user()->company_id}}/{{\Illuminate\Support\Facades\Auth::user()->bunitid}}',
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

	
	 document.addEventListener("DOMContentLoaded",function(){
		$("#view_upload_bs").click(function () {
			
			BootstrapDialog.show({
				title: '',
				message:function(dialog) {
                var $message = $("<div><img src='ajax.gif'><small class='text-danger'>please wait...</small></div>");
                var pageToLoad = dialog.getData('pageToLoad');
                setTimeout(function(){
                    $message.load(pageToLoad,function(e,st){

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
                    });
                },1000);
                return $message;
            },
            data: {
                'pageToLoad': 'viewUpBS',
            },
            onhidden: function(dialogRef){

            },
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
	});

</script>