<ul class="page-sidebar-menu page-sidebar-menu-light " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
    <li class="start active link">
        <a href="#" id="requested-cash">
            <i class="fa fa-money"></i>
            <span class="title">Pull Out Form</span>
            <span class="selected"></span>
        </a>
    </li>
    <li class="link" id="cash-requested">
        <a href="#">
            <i class="icon-paper-plane"></i>
            <span class="title">Cash Requested</span>
        </a>
    </li>
    <li class="link" id="cpo-paid">
        <a href="#">
            <i class="icon-briefcase"></i>
            <span class="title">CPO Paid</span>
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

        $("#requested-cash").click(function(){
            CPOform();
        });

        $("#cash-requested").click(function(){
            cashRequested();
        });

        $("#cpo-paid").click(function(){
            cpoPaid();
        });

    </script>
@endpush