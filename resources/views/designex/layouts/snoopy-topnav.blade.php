<div id="mobile_only_nav" class="mobile-only-nav pull-right">
    <ul class="nav navbar-right top-nav pull-right">
        <li class="dropdown auth-drp">
            <a href="#" class="dropdown-toggle pr-0" data-toggle="dropdown"><img src="{{ asset('snoopy/dist/img/user1.png') }}" alt="user_auth" class="user-auth-img img-circle"/><span class="user-online-status"></span></a>
            <ul class="dropdown-menu user-auth-dropdown" data-dropdown-in="flipInX" data-dropdown-out="flipOutX">
                <li>
                    <a href="{{ route('xprofile') }}"><i class="zmdi zmdi-account"></i><span>Profile</span></a>
                </li>
                <li>
                    <a href="#"><i class="zmdi zmdi-settings"></i><span>Settings</span></a>
                </li>
                <li class="divider"></li>
                <li class="sub-menu show-on-hover">
                    <a href="#" class="dropdown-toggle pr-0 level-2-drp"><i class="zmdi zmdi-check text-success"></i> available</a>
                    <ul class="dropdown-menu open-left-side">
                        <li>
                            <a href="#"><i class="zmdi zmdi-check text-success"></i><span>available</span></a>
                        </li>
                        <li>
                            <a href="#"><i class="zmdi zmdi-circle-o text-warning"></i><span>busy</span></a>
                        </li>
                        <li>
                            <a href="#"><i class="zmdi zmdi-minus-circle-outline text-danger"></i><span>offline</span></a>
                        </li>
                    </ul>
                </li>
                <li class="divider"></li>
                <li>
                    <a id="logout-app" href="#"><i class="zmdi zmdi-power"></i><span>Log Out</span><form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form></a>
                </li>
            </ul>
        </li>
    </ul>
</div>