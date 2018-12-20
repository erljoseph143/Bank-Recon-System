{!! Form::select('bank',[''=>'']+$bank,null,['class'=>'form-control all-bank','data-placeholder'=>"Select Bank Account" ]) !!}
<script src="{{asset('chosen/chosen.jquery.js')}}" type="text/javascript"></script>
<script>
    $(".all-bank").chosen();



</script>