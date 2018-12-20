<ul class="page-sidebar-menu page-sidebar-menu-light " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
    <li class="start active link">
        <a href="#" id="cash-pull-out-list">
            <i class="fa fa-money"></i>
            <span class="title">Cash Pull Out List</span>
            <span class="selected"></span>
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

        $("#cash-pull-out-list").click(function(){
            cpoList();
        });
    </script>
@endpush

<style>
    .modal .modal-dialog {
        z-index: 20051;
    }
</style>