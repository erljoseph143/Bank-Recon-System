<ul class="page-sidebar-menu page-sidebar-menu-light " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
    <li class="start active link">
        <a href="#" id="requested-cash">
            <i class="fa fa-money"></i>
            <span class="title">Requested cash</span>
            <span class="selected"></span>
        </a>
    </li>
    <li class="link" id="approved-cash-requested">
        <a href="#">
            <i class="glyphicon glyphicon-thumbs-up"></i>
            <span class="title">Approved Cash Requested</span>
        </a>
    </li>
    <li class="link" id="cpo-replenished">
        <a href="#">
            <i class="icon-briefcase"></i>
            <span class="title">CPO Replenishment</span>
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
            requestedCash();
        });

        $("#approved-cash-requested").click(function(){
            approveRequestedCash();
        });

        $("#cpo-replenished").click(function(){
            cpoReplenished();
        });

    </script>
@endpush

<style>
    .modal .modal-dialog {
        z-index: 20051;
    }
</style>