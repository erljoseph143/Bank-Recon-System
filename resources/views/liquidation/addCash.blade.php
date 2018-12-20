<form action="{{url('liquidation/saveCash')}}" id="save-cash" method="post" class="form-horizontal">
    {{csrf_field()}}
    <table class="table table-condensed table-hover" id="add-cash">
        <thead>
            <tr>
                <th style="width: 33%;">Dimension</th>
                <th style="width: 33%;">Deposit Slip Number</th>
                <th style="width: 34%;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cashList as $key => $cash)
                @if(preg_match('/AR#/',$cash[1])>0)
                    <tr>
                        <td style="width: 33%;">
                            <label class="hidden description">{{$cash[1]}}</label>
                            {{$cash[1]}}
                            <input required class="fromAR" type="text" placeholder="from" id="fromAR{{$cash[0]}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"  name="fromAR[]" class="fromAR" style="padding:5px;width:30%">
                            to
                            <input required class="toAR" type="text" placeholder="to" id="toAR{{$cash[0]}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"  name="toAR[]" class="toAR" style="padding:5px;width:30%">
                            Others
                        </td>
                        <td style="width: 33%;">
                            <input required class="form-control ds_number" autofocus="true" type="text" name="ds_no[]" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" placeholder="input DS number here">
                        </td>
                        <td style="width: 34%;">
                            <input type="hidden" name="cash_log[]" value="{{$cash[0]}}">
                            <input required type="text" name="cash[]"  class="form-control amount text-right" placeholder="Input the Amount here..."  value="">
                        </td>
                    </tr>
                @else
                    <tr>
                        <td style="width: 33%">
                            <label class="hidden description">{{$cash[1]}}</label>
                            <input type="hidden" class="fromAR" name="fromAR[]">
                            <input type="hidden" class="toAR" name="toAR[]">
                            {{$cash[1]}}
                        </td>
                        <td style="width: 33%">
                            <input required class="form-control ds_number"  autofocus="true"  placeholder="input DS number here" type="text" name="ds_no[]" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                        </td>
                        <td style="width: 34%">
                            <input type="hidden" name="cash_log[]" value="{{$cash[0]}}">
                            <input required type="text" name="cash[]" class="form-control amount text-right" placeholder="Input the Amount here..."  value="">
                        </td>
                    </tr>

                @endif
            @endforeach
        </tbody>
    </table>
    <button class="btn btn-success">
        <i class="glyphicon glyphicon-send"></i>
        Submit
    </button>
</form>
<div id="testing-data"  style="display: none;">
    <table border="1" id="data-tables" class="table table-bordered table-striped" width="100%">
        <thead>
        <tr>
            <th>Cash Type</th>
            <th>DS Number</th>
            <th>Amount</th>
        </tr>
        </thead>
        <tbody id="tbody-cash">

        </tbody>
    </table>
</div>

@if(!isset($login_user))
    <script>
        $('.amount').maskMoney();
        $('.title-page').text('{{$content_title}}');
        $("#save-cash").submit(function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            $('#tbody-cash').html('');
            $('.description').each(function(index,value){
                //console.log(index +" => "+$('.amount').eq(index).val());
                $('#tbody-cash').append('<tr><td>'+$('.description').eq(index).text()+'</td><td>'+$('.ds_number').eq(index).val()+'</td><td>'+$('.amount').eq(index).val()+'</td></tr>');
            });
            saveCash(url);
        });
    </script>
@endif

@push('scripts')
<script>
    $('.amount').maskMoney();
    $("#save-cash").submit(function(e) {
        e.preventDefault();
        var url = $(this).attr('action');
        $('#tbody-cash').html('');
        $('.description').each(function(index,value){
            //console.log(index +" => "+$('.amount').eq(index).val());
            $('#tbody-cash').append('<tr><td>'+$('.description').eq(index).text()+'</td><td>'+$('.ds_number').eq(index).val()+'</td><td>'+$('.amount').eq(index).val()+'</td></tr>');
        });
        saveCash(url);
    });
</script>
@endpush