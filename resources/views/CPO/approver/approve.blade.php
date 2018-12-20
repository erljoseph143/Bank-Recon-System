<div class="row">
    <form action="post" id="approve-form">
        {{csrf_field()}}
        <div class="col-md-12">
            <div class="form-group">
                <label class="col-md-3 control-label">Amount Requested: </label>
                <div class="col-md-6">
                    <div class="input-group">
                     <span class="input-group-addon">
                         ₱
                     </span>
                        <input type="hidden" name="id" value="{{$cpo->id}}">
                        <input type="text" name="amount" class="form-control input-lg amount" value="{{number_format($cpo->amount_edited,2)}}" style="text-align:right" placeholder="">
                    </div>
                    <textarea name="amt_words" class="form-control amt-words" readonly rows="3" style="resize:none;margin: 0px -2px 0px 0px; height: 73px; width: 503px;">
                   {{$cpo->amt_words}}
                </textarea>
                </div>
            </div>
        </div>
    </form>
    <div class="clearfix"></div>
</div>
<script>
    $(document).on('focus','.amount',function(){
        $(this).maskMoney();
        $(this).keyup(function(){
            $(".amt-words").val($(this).AmountInWords());
        });
    })
</script>

