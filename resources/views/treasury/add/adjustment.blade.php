<div class="row">
    <form id="add-adjustment" method="post">
        {{csrf_field()}}
        <div class="col-md-12">
            <div class="input-group form-group">
                <input type="hidden" name="id-adj" id="id-adj" value="">
                <input type="hidden" name="date_adj" id="date-adj" value="{{$date}}">
                {!! Form::select('description',[''=>'---select type---']+$adj,null,['class'=>'form-control','id'=>'description']) !!}
                {{--<select name="description" id="description" class="form-control">--}}
                    {{--<option value="PDC">PDC</option>--}}
                    {{--<option value="Due Checks">Due Checks</option>--}}
                {{--</select>--}}
            </div>
            <div class="input-group form-group">
                <input type="text" name="amount" id="amt-data" class="form-control" value="">
            </div>
        </div>
    </form>
</div>

<script>
    $(document).on("focus", "#amt-data", function () {
        $(this).maskMoney();
        $(this).css('text-align','right');
    });
</script>


