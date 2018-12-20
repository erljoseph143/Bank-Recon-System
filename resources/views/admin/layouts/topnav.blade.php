<!-- item-->
<a href="{{ url('admin/users/profile') }}" class="dropdown-item notify-item">
    <i class="mdi mdi-account-star-variant"></i> <span>Profile</span>
</a>
<!-- item-->
<a href="javascript:void(0);" class="dropdown-item notify-item">
    <i class="mdi mdi-lock-open"></i> <span>Lock Screen</span>
</a>
<!-- item-->
<a href="javascript:void(0);" id="logout-app" class="dropdown-item notify-item">
    <i class="mdi mdi-logout"></i> <span>Logout</span>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
</a>