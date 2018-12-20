
<link rel="stylesheet" href="{{asset('chosen/chosen.css')}}">
{!! Form::select('datein',[''=>'-------------Select Month-------------------']+$new_array,null,['class'=>'form-control chosen-select','id'=>'month-list']) !!}

<script src="{{asset('chosen/chosen.jquery.js')}}"></script>
<script src="{{asset('chosen/docsupport/prism.js')}}"></script>
<script src="{{asset('chosen/docsupport/init.js')}}"></script>