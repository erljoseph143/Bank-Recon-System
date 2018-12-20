<li>
    <a href="{{ url('admin/home') }}" class="waves-effect waves-primary"><i
                class="ti-home"></i><span> Dashboard </span></a>
</li>
<li>
    <a href="{{ url('admin/bankcodes?p=all') }}" class="waves-effect waves-primary"><i
                class="ti-key"></i><span> Bank Codes </span></a>
</li>
<li>
    <a href="{{ url('admin/bankaccounts') }}" class="waves-effect waves-primary"><i
                class="ti-credit-card"></i><span> Bank Accounts </span></a>
</li>
<li class="has_sub">
    <a href="javascript:void(0);" class="waves-effect waves-primary"><i class="ti-user"></i> <span> Users </span>
        <span class="menu-arrow"></span></a>
    <ul class="list-unstyled">
        <li><a href="{{ url('admin/users') }}">Manage users</a></li>
        <li><a href="{{ url('admin/usertypes') }}">Manage user types</a></li>
    </ul>
</li>
<li>
    <a href="{{ url('admin/companies') }}" class="waves-effect waves-primary"><i
                class="ti-bar-chart"></i><span> Company </span></a>
</li>
<li>
    <a href="{{ url('admin/departments') }}" class="waves-effect waves-primary"><i
                class="ti-rocket"></i><span> Departments </span></a>
</li>
{{--<li>--}}
    {{--<a href="{{ url('admin/checking-accounts') }}" class="waves-effect waves-primary"><i--}}
                {{--class="ti-receipt"></i><span> Checking Accounts </span></a>--}}
{{--</li>--}}
<li>
    <a href="{{ url('admin/dtr') }}" class="waves-effect waves-primary"><i
                class="ti-receipt"></i><span> DTR Bank Statements </span></a>
</li>
<li>
    <a href="{{ url('admin/bank-statements') }}" class="waves-effect waves-primary"><i
                class="ti-money"></i><span> Bank Statements </span></a>
</li>
<li>
    <a href="{{ url('admin/disbursements') }}" class="waves-effect waves-primary"><i
                class="ti-book"></i><span> Disbursements </span></a>
</li>
<li class="has_sub">
    <a href="javascript:void(0)" class="waves-effect waves-primary">
        <i class="ti-settings"></i>
        <span> Tools </span>
        <span class="menu-arrow"></span>
    </a>
    <ul class="list-unstyled">
        <li><a href="{{ route('settings.index') }}">General</a></li>
        <li><a href="{{ url('admin/backup') }}">Back Up DB</a></li>
        <li><a href="{{ url('admin/archive') }}">Archive</a></li>
        <li><a href="{{ url('admin/cashlogs') }}">Cash Logs</a></li>
        <li><a href="{{ url('admin/checklogs') }}">Check Logs</a></li>
        <li><a href="{{ url('admin/adjustmentlogs') }}">Adjustment Logs</a></li>
        <li><a href="{{ url('admin/branchcodes') }}">Branch Codes</a></li>
        <li><a href="{{ url('admin/cashpullouts') }}">Cash Pull out</a></li>
    </ul>
</li>
<li><a href="{{ url('admin/about') }}" class="waves-effect waves-primary"><i
                class="ti-info"></i><span>About Us</span></a></li>