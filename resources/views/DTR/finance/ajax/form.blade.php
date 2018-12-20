<div class="form-me">
    <form action="{{url('dtr/DTRsaving')}}" id="dtr-upload" method="POST" enctype="multipart/form-data">
        <div class="col-md-12 margin-top-10">
            <div class="input-group full-width">
                <span class="input-group-btn span-width">
                    <button class="btn form-btn" type="button">Bank Account</button>
                </span>
                <div class="full-width">
                    <div class="form-control data-for"></div>
                    {{csrf_field()}}
                    <input type="hidden" name="bankAcct" class="bankAcct" id="bankAcct" value="{{$bankacct}}">
                    <input type="hidden" name="com" class="com" id="com" value="{{$com}}">
                    <input type="hidden" name="bu" class="bu" id="bu" value="{{$bu}}">
                </div>
            </div>
        </div>

        <div class="col-md-12 margin-top-10 bpi-type hidden">
            <div class="input-group full-width">
                <span class="input-group-btn span-width">
                    <button class="btn form-btn" type="button">BPI TYPE</button>
                </span>
                <div class="full-width">
                    {!! Form::select('bpiType',[
                        ''=>'-------------------Select BPI type---------------------',
                        'BIZLINK'=>'BPI-BIZLINK',
                        'EXPLINK'=>'BPI-EXPLINK'
                    ],null,['class'=>'form-control','id'=>'bpi-type']) !!}
                </div>
            </div>
        </div>

        <div class="col-md-12 margin-top-10 bank-year hidden">
            <div class="input-group full-width">
                <span class="input-group-btn span-width">
                    <button class="btn form-btn" type="button">Year</button>
                </span>
                <div class="full-width">
                    {!! Form::select('year',[
                        ''=>'-------------------Select Bank Year---------------------',
                    ]+$yearData,null,['class'=>'form-control','id'=>'year']) !!}
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-12 margin-top-10">
                <label for="file" class="btn btn-default fileinput-button">
                    {{--<i class="fa fa-plus"></i>--}}
                    {{--<span>--}}
                    {{--Add files--}}
                    {{--</span>--}}
                    <input id="file" type="file" name="dtr" >
                </label>
                <label for="" class="num-file"></label>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-12 margin-top-10">
                <button type="submit" class="btn btn-default" >
                    <i class="fa fa-cloud-upload"></i>
                    Upload
                </button>
            </div>
        </div>
    </form>
    <div class="clearfix"></div>
</div>
<div class="progress-me hidden">
    @include("DTR.finance.ajax.progressBar")
</div>

<script>
    $("#bpi-type").change(function(){
        var typeBPI = $(this).val();
        if(typeBPI.match(/EXPLINK/))
        {
            $(".bank-year").removeClass("hidden");
        }
        else
        {
            $(".bank-year").addClass("hidden");
        }
    });
    $("#dtr-upload").submit(function(e){
        e.preventDefault();

        var form = $(this)[0];
        // var form = $('form')[0]; // You need to use standard javascript object here
        var formData = new FormData(form);

        $(".form-me").fadeOut();
        $(".progress-me").removeClass("hidden");

        $.ajax({
            type:'post',
            data:formData,
            processData:false,
            contentType: false,
            url:"{{url('dtr/DTRsaving')}}",
            //dataType: 'text',
            success:function(data)
            {
                //console.log(data);
            },
            beforeSend: function (jqXHR, settings)
            {
                var self = this;
                var xhr = settings.xhr;
                settings.xhr = function () {
                    var output = xhr();
                    output.previous_text = '';
                    output.onreadystatechange = function () {
                        try{
                            // var new_response = output.responseText.substring(output.previous_text.length);
                            var result       = JSON.parse( output.responseText.match(/{[^}]+}$/gi) );
                            //var result = output.responseText;

                            if (output.readyState == 3)
                            {
                                $("#percent-data").text("0%");
                                if(result.error.trim() == "Not balance")
                                {
                                    $(".progress-me").addClass("hidden");
                                    jqXHR.abort();
                                    $(".form-me").fadeOut(function(){
                                        $(this).load('{{url('dtr/errorbalance')}}/'+result.messageError.trim());
                                    });
                                    $(".form-me").fadeIn();

                                }
                                else if(result.error.trim()=="Invalid format")
                                {
                                    $(".progress-me").addClass("hidden");
                                    jqXHR.abort();
                                    $(".form-me").fadeOut(function(){
                                        $(this).load('{{url('dtr/invalid')}}/'+result.messageError.trim().replace(/\s/g,"%20"));
                                    });
                                    $(".form-me").fadeIn();
                                }
                                else if(result.error.trim() == "Data Error")
                                {
                                    $(".progress-me").addClass("hidden");
                                    jqXHR.abort();
                                    $(".form-me").fadeOut(function(){
                                        $.ajax({
                                            type:'post',
                                            data:{errorArray:result.messageError},
                                            url:'{{url('dtr/showErrors')}}',
                                            success:function(data)
                                            {
                                                $(".form-me").html(data);
                                            }
                                        })
                                    });
                                    $(".form-me").fadeIn();
                                }
                                else
                                {
                                    $(".form-me").fadeOut();
                                    $(".progress-me").removeClass("hidden");
                                }

                                //	console.log(result.message);
                                $("#text-message").text(result.message);
                                $("#text-message").html(result.time_elapse);
                                if(Math.round(result.pecent)==99)
                                {
                                    prog = 100;
                                }
                                else
                                {
                                    prog = result.percent.toFixed(2);
                                }
                                $(".progress-bar").css('width',prog+"%");
                                $("#percent-data").text(prog+"%");
                            }
                            else if(output.readyState == 4)
                            {
                                $(".progress-bar").css('width',"100%");
                                $("#percent-data").text("100%");
                                setTimeout(function(){
                                    modal.close();
                                },1500);
                                $("#date"+bankId).text(result.date.trim());
                                $("#balance"+bankId).text(result.bank_balance.trim());
                                console.log(bankId);

                            }
                        }catch(e)
                        {
                            console.log("[XHR STATECHANGE] Exception: " + e);
                        }
                    };
                    return output;
                }
            }
        });
    });
</script>