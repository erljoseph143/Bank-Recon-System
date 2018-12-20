<div class="navbar navbar-default" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">

    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="row">
        <div class="collapse navbar-collapse navbar-ex1-collapse">

            <form class="navbar-form " role="search">
                <div class="form-group col-md-4">
                    {{--<input type="text" class="form-control" placeholder="Search">--}}
                    {!! Form::select('bu',[''=>'']+$allBU,null,['class'=>'form-control all-bu','data-placeholder'=>"Select Business Unit" ]) !!}
                    <div class="clearfix"></div>
                </div>

                <div class="form-group col-md-4 bank-account">
                    <input type="text" placeholder="Bank Account" disabled="disabled" class="form-control">
                </div>
                <div class="form-group col-md-2">
                    <button type="button" class="btn blue data-bu">Submit</button>
                </div>

            </form>

        </div>
    </div>



    <!-- /.navbar-collapse -->
</div>

<div class="row margin-top-10">
    <div class="col-md-12 form-group" id="bs-table">

    </div>
</div>
<link rel="stylesheet" href="{{asset('chosen/chosen.css')}}">
<style>
    .chosen-container-single .chosen-single
    {
        padding: 4px 0 0 8px;
        height:34px;
        width:325px;
    }
    .chosen-container-single .chosen-single div b
    {
        margin: 5px 0 4px 0px;
    }
    .form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control
    {
        width:100%;
    }
</style>

@if(isset($ajax_load))
    <script src="{{asset('chosen/chosen.jquery.js')}}" type="text/javascript"></script>
    <script>
        var allBU   = $(".all-bu").chosen();
        var allbank = 0;
        var bu      = 0;
        allBU.change(function(){
            bu = $(this).val();
            $.ajax({
                type:'get',
                url:'{{url('dtr/allBanks')}}/'+bu,
                success:function(data)
                {
                    $(".bank-account").html(data);
                }
            })
        });

        $(".data-bu").click(function(){
            allbank = $(".all-bank").chosen().val();

            $.ajax({
                type:'get',
                url:'{{url('dtr/getBStable')}}/'+bu+'/'+allbank,
                success:function(data)
                {
                    $("#bs-table").html(data);
                    $(".bank-data").text($(".all-bank option:selected").text());
                }
            });
        });
    </script>
@else
    @push('scripts')

        <script src="{{asset('chosen/chosen.jquery.js')}}" type="text/javascript"></script>
        <script>
            var allBU   = $(".all-bu").chosen();
            var allbank = 0;
            var bu      = 0;
            allBU.change(function(){
                bu = $(this).val();
                $.ajax({
                    type:'get',
                    url:'{{url('dtr/allBanks')}}/'+bu,
                    success:function(data)
                    {
                        $(".bank-account").html(data);
                    }
                })
            });

            $(".data-bu").click(function(){
                allbank = $(".all-bank").chosen().val();

                $.ajax({
                    type:'get',
                    url:'{{url('dtr/getBStable')}}/'+bu+'/'+allbank,
                    success:function(data)
                    {
                        $("#bs-table").html(data);
                        $(".bank-data").text($(".all-bank option:selected").text());
                    }
                });
            });
        </script>
    @endpush

@endif

