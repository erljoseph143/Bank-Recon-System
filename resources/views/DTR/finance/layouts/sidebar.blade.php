<ul class="page-sidebar-menu page-sidebar-menu-light page-sidebar-menu-closed" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
    <li class="start active link" id="dash-board">
        <a href="#">
            <i class="icon-home"></i>
            <span class="title">Dashboard</span>
            <span class="selected"></span>
        </a>
    </li>
    {{--<li class="link" id="upload-file">--}}
        {{--<a href="#">--}}
            {{--<i class="icon-paper-plane"></i>--}}
            {{--<span class="title">Upload File</span>--}}
        {{--</a>--}}
    {{--</li>--}}
</ul>

@push('scripts')
    <script>
//        $(".link").click(function(){
//            //var Handler = $(this);
//            if($(this).hasClass('start')==false)
//            {
//                $(".link").removeClass('start active');
//                $(".lnik").find('a').remove('selected');
//                $(this).addClass("start active");
//                $(this).find('a').append('<span class="selected"></span>');
//            }
//        });
//
//        $("#dash-board").click(function(){
//            dashBoard();
//        });
//
//        $("#upload-file").click(function(){
//            uploadDTR();
//        });
    </script>
@endpush