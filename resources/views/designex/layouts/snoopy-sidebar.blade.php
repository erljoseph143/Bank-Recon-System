<div class="fixed-sidebar-left">
    <ul class="nav navbar-nav side-nav nicescroll-bar">
        <li>
            <a class="active" aria-expanded="{{ ($ptitle == 'dashboard' || $ptitle == 'profile')?'true':'false' }}" href="javascript:void(0);" data-toggle="collapse" data-target="#dashboard_dr"><div class="pull-left"><i class="zmdi zmdi-landscape mr-20"></i><span class="right-nav-text">Dashboard</span></div><div class="pull-right"><i class="zmdi zmdi-caret-down"></i></div><div class="clearfix"></div></a>
            <ul id="dashboard_dr" class="collapse collapse-level-1 {{ ($ptitle == 'dashboard')?'in':'' }}">
                <li><a class="{{ ($ptitle == 'dashboard')?'active-page':'' }}" href="{{ route('designex.dashboard') }}">home</a></li>
                <li><a class="{{ ($ptitle == 'profile')?'active-page':'' }}" href="{{ route('xprofile') }}">profile</a></li>
            </ul>
        </li>
        {{--<li>--}}
            {{--<a href="{{ route('xdisburse') }}"><div class="pull-left"><i class="zmdi zmdi-money-box mr-20"></i><span class="right-nav-text">disbursements</span></div><div class="clearfix"></div></a>--}}
        {{--</li>--}}
        {{--<li>--}}
            {{--<a href="#"><div class="pull-left"><i class="zmdi zmdi-book mr-20"></i><span class="right-nav-text">transactions</span></div><div class="clearfix"></div></a>--}}
        {{--</li>--}}
        <li>
            <a class="{{ ($ptitle == 'prooflist')?'active-page':'' }}" href="{{ route('xprooflist')}}"><div class="pull-left"><i class="zmdi zmdi-file mr-20"></i><span class="right-nav-text">prooflists</span></div><div class="clearfix"></div></a>
        </li>
        <li>
            <a class="{{ ($ptitle == 'sl')?'active-page':'' }}" href="{{ route('sl.index')}}"><div class="pull-left"><i class="zmdi zmdi-file-text mr-20"></i><span class="right-nav-text">subsidiary ledgers</span></div><div class="clearfix"></div></a>
        </li>
        <li>
            <a class="{{ ($ptitle == 'upload')?'active-page':'' }}" href="{{ route('xuploadui') }}"><div class="pull-left"><i class="zmdi zmdi-cloud-upload mr-20"></i><span class="right-nav-text">upload</span></div><div class="clearfix"></div></a>
        </li>
        <li>
            <a class="{{ ($ptitle == 'report')?'active-page':'' }}" href="{{ route('reports.index') }}"><div class="pull-left"><i class="zmdi zmdi-assignment mr-20"></i><span class="right-nav-text">reports</span></div><div class="clearfix"></div></a>
        </li>
        <li>
            <a class="active" aria-expanded="{{ ($ptitle == 'file-setting' || $ptitle == 'general-setting')?'true':'false' }}" href="javascript:void(0);" data-toggle="collapse" data-target="#ui_dr"><div class="pull-left"><i class="zmdi zmdi-settings mr-20"></i><span class="right-nav-text">settings</span></div><div class="pull-right"><i class="zmdi zmdi-caret-down"></i></div><div class="clearfix"></div></a>
            <ul id="ui_dr" class="collapse collapse-level-1 two-col-list">
                <li>
                    <a class="{{ ($ptitle == 'general-setting')?'active-page':'' }}" href="{{ route('general-settings.index') }}">General</a>
                </li>
                <li>
                    <a class="{{ ($ptitle == 'file-setting')?'active-page':'' }}" href="{{ route('transaction-types.index') }}">Files</a>
                </li>
            </ul>
        </li>
        <li><hr class="light-grey-hr mb-10"/></li>
    </ul>
</div>