<ul class="page-sidebar-menu page-sidebar-menu-light " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
    <li class="start active link" id="cash-adding">
        <a href="#">
            <i class="fa fa-money"></i>
            <span class="title">Adding Cash</span>
            <span class="selected"></span>
        </a>
    </li>
    <li class="link" id="cash-viewing">
        <a href="#">
            <i class="icon-paper-plane"></i>
            <span class="title">View Cash</span>
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

        $("#cash-adding").click(function(){
            //$(this).unbind();
            addCash();
        });

        $("#cash-viewing").click(function(){
            monthlyCash();
        });

</script>
@endpush