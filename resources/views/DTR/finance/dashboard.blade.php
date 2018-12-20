<div class="row all-banks col-md-offset-2" style="margin-top: 8%;margin-bottom: 8%;">
    <div class="col-sm-12 col-md-2 banks"  data-bank="LBP">

        <div class="thumbnail" style="height: 9em;border: 1px solid #ddd;background-color: #fafafa;">
            <img src="{{url('banklogo/Land Bank.png')}}" alt="" style="width: 100px;height: 70px;margin-top: 12px;">
        </div>
    </div>
    <div class="col-sm-12 col-md-2 banks" data-bank="BPI">

        <div class="thumbnail" style="height: 9em;border: 1px solid #ddd;background-color: #fafafa;">
            <img src="{{url('banklogo/BPI.png')}}" alt="" style="width: 90px;height: 55px;margin-top: 24px;">
        </div>
    </div>
    <div class="col-sm-12 col-md-2 banks" data-bank="MB">

        <div class="thumbnail" style="height: 9em;border: 1px solid #ddd;background-color: #fafafa;">
            <img src="{{url('banklogo/Metro Bank.png')}}" alt="" style="width: 90px;height: 90px;margin-top: 6px;">
        </div>
    </div>
    <div class="col-sm-12 col-md-2 banks" data-bank="PNB">

        <div class="thumbnail" style="height: 9em;border: 1px solid #ddd;background-color: #fafafa;">
            <img src="{{url('banklogo/PNB.png')}}" alt="" style="width: 90px;height: 90px;margin-top: 10px;">
        </div>
    </div>
</div>

<div class="row margin-top-10 hidden data-bank">
    <button class="btn btn-default show-bank " style="margin-left:15px;">
        <i class="glyphicon glyphicon-arrow-left"></i>
        Show all banks
    </button>
    <div class="col-md-12 form-group margin-top-10" id="bs-acct-table">

    </div>
</div>

<style>
    .banks:hover
    {
        cursor: pointer;
        background: #ff8100;
    }
</style>

@if(isset($ajax_load))
    <script src="{{asset('chosen/chosen.jquery.js')}}" type="text/javascript"></script>
    <script>
        $(".banks").click(function(){
            var bank = $(this).data('bank');

            $.ajax({
                type:'get',
                url:'{{url('dtr/allbanks')}}/'+bank,
                success:function(data)
                {
                    $(".all-banks").toggle("slide");
                    $(".data-bank").removeClass("hidden");
                    $("#bs-acct-table").fadeOut(function(){
                        $(this).html(data);
                    });
                    $("#bs-acct-table").fadeIn();
                }
            });

        });

    </script>
@else
    @push('scripts')

        <script src="{{asset('chosen/chosen.jquery.js')}}" type="text/javascript"></script>
        <script>
            $(".banks").click(function(){
                var bank = $(this).data('bank');
                $.ajax({
                    type:'get',
                    url:'{{url('dtr/allbanks')}}/'+bank,
                    success:function(data)
                    {
                        $(".all-banks").toggle("slide");
                        $(".data-bank").removeClass("hidden");
                        $("#bs-acct-table").fadeOut(function(){
                            $(this).html(data);
                        });
                        $("#bs-acct-table").fadeIn();
                    }
                });

            });
            $(".show-bank").click(function(){
                $(".all-banks").fadeIn();
                $(".data-bank").addClass("hidden");
                $("#bs-acct-table").html('');
            });
        </script>
    @endpush

@endif