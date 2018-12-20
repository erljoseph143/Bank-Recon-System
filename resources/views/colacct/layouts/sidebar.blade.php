<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('colacct/assets/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>
                    {{ $login_user->firstname . ' ' . $login_user->lastname }}
                </p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="treeview">
                <a href="#"><i class="fa fa-dashboard"></i><span>Dashboard</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                <ul class="treeview-menu">
                    <li class="active"><a href="{{ url('home') }}" data-selected-links="{{ asset('home') }}" class="nav-item"><i class="fa fa-home"></i> Home</a></li>
                    <li class="active"><a href="{{ url('colacct/upload') }}" data-selected-links="{{ asset('colacct/upload') }}" class="nav-item"><i class="fa fa-home"></i> Upload Checks</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-file-text"></i><span>Checking Accounts</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ asset('colacct/checking_accounts') }}" data-selected-links="{{ asset('colacct/checking_accounts') }}" class="nav-item"><i class="fa fa-eye"></i> Table View</a></li>
                    {{--<li><a href="{{ asset('checking_accounts/bar_chart') }}" data-selected-links="{{ asset('checking_accounts/bar_chart') }}" class="nav-item"><i class="fa fa-eye"></i> Bar Chart View</a></li>--}}
                </ul>
            </li>
            {{--<li class="treeview">--}}
                {{--<a href="#">--}}
                    {{--<i class="fa fa-book"></i><span>Disbursements</span>--}}
                    {{--<span class="pull-right-container">--}}
	{{--<i class="fa fa-angle-left pull-right"></i>--}}
	{{--</span>--}}
                {{--</a>--}}
                {{--<ul class="treeview-menu">--}}
                    {{--<li><a href="{{ asset('disbursement') }}" data-selected-links="{{ asset('disbursement') }}" class="nav-item"><i class="fa fa-dot-circle-o"></i> View</a></li>--}}
                {{--</ul>--}}
            {{--</li>--}}
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-flag"></i><span>Reports</span>
                    <span class="pull-right-container">
	<i class="fa fa-angle-left pull-right"></i>
	</span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('colacct/reports/disbursement_summary') }}" data-selected-links="{{ url('colacct/reports/disbursement_summary') }}" class="nav-item"><i class="fa fa-file-o"></i> Disbursement Summary</a></li>
                </ul>
            </li>
            <li class="header">MAIN NAVIGATION</li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>