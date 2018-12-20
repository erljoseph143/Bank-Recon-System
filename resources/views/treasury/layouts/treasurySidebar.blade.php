<ul class="page-sidebar-menu page-sidebar-menu-light " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
    <li class="start active link">
        <a href="#" id="cash-for-dep">
            <i class="fa fa-money"></i>
            <span class="title">Cash For Deposit</span>
            <span class="selected"></span>
        </a>
    </li>
    <li class="link" id="cash-re">
        <a href="#">
            <i class="icon-paper-plane"></i>
            <span class="title">Cash Releasing</span>
        </a>
    </li>
    <li class="link" id="dep-cash">
        <a href="#">
            <i class="icon-briefcase"></i>
            <span class="title">Cash Deposited</span>
        </a>
    </li>

</ul>

@push('scripts')
    <script>
        $(".link").click(function(){
            //var Handler = $(this);
            if($(this).hasClass('start')==false)
            {
                $(".link").removeClass('start active');
                $(".lnik").find('a').remove('selected');
                $(this).addClass("start active");
                $(this).find('a').append('<span class="selected"></span>');
            }
        });

        $("#cash-for-dep").click(function(){
            cashForDep();
        });

        $("#cash-re").click(function(){
            cashRelease();
        });

        $("#dep-cash").click(function(){
            cashDeposited();
        });

    </script>
@endpush