<header class="main-header">
    <!-- Logo -->
    <a href="{{ url('home') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>B</b>RS</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>BRS</b> Colonnade</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Notifications: style can be found in dropdown.less -->
                <!-- Tasks: style can be found in dropdown.less -->
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ asset('colacct/assets/dist/img/user2-160x160.jpg') }}" class="user-image" alt="User Image">
                        <span class="hidden-xs">{{ $login_user->firstname . ' ' . $login_user->lastname }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="{{ asset('colacct/assets/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">

                            <p>
                                {{ $login_user->firstname . ' ' . $login_user->lastname . ' - ' . $login_user_type->user_type_name }}
                                <small>Member since
                                    @if($login_user->created_at != null)
                                        {{ $login_user->created_at->format('M. d, Y') }}
                                    @endif
                                </small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="javascript:void(0);" id="logout-app" class="btn btn-default btn-flat">
                                    Sign out
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
            </ul>
        </div>
    </nav>
</header>