@extends('designex.layouts.snoopy')
@section('content')
    <div class="alert alert-success alert-style-1">
        <input id="name" type="hidden" value="{{ $login_user->firstname.' '.$login_user->lastname }}">
        <i class="zmdi zmdi-check"></i>Today is {{ date("l").' '.date("F d, Y") }}.
    </div>

    @include('designex.dashboad.databox')

    {{--<div class="col-sm-12">--}}
        {{--<div class="panel panel-default card-view">--}}
            {{--<div class="panel-heading">--}}
                {{--<div class="pull-left">--}}
                    {{--<h6 class="panel-title txt-dark">prooflist disburse chart</h6>--}}
                {{--</div>--}}
                {{--<div class="pull-right">--}}
                    {{--<div class="dropdown  pull-left">--}}
                        {{--<select name="" id="">--}}
                            {{--@foreach($pryears as $pryear)--}}
                                {{--<option value="">{{ $pryear->year }}</option>--}}
                            {{--@endforeach--}}
                        {{--</select>--}}
                        {{--<select name="" id="">--}}
                            {{--@foreach($prbanks as $bank)--}}
                                {{--<option value="">{{ $bank->check_bank }}</option>--}}
                            {{--@endforeach--}}
                        {{--</select>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="clearfix"></div>--}}
            {{--</div>--}}
            {{--<div class="panel-wrapper collapse in">--}}
                {{--<div class="panel-body">--}}
                    {{--<canvas id="myChart" height="100"></canvas>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
@endsection

@section('styles')
<!-- Toast CSS -->
<link href="{{ asset('snoopy/vendors/bower_components/jquery-toast-plugin/dist/jquery.toast.min.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('scripts')
<!-- Toast JavaScript -->
<script src="{{ asset('snoopy/vendors/bower_components/jquery-toast-plugin/dist/jquery.toast.min.js') }}"></script>
<script src="{{ asset('snoopy/vendors/chart.js/Chart.min.js') }}"></script>
@endsection
@section('endscripts')
    <script type="text/javascript" src="{{ asset('designex/larry-scripts/dashboard.js') }}"></script>
    <script type="text/javascript">

        {{--var ctx1 = document.getElementById("myChart").getContext("2d");--}}

        {{--var data1 = new Chart(ctx1, {--}}
            {{--type: 'line',--}}
            {{--data: {--}}
                {{--labels: {!! $prlabels !!},--}}
                {{--datasets: [{--}}
                    {{--label: 'prooflists',--}}
                    {{--data: {{ $prdata }},--}}
                    {{--backgroundColor: 'rgba(255, 99, 132, 1)',--}}
                    {{--borderColor: 'rgba(255,99,132,1)',--}}
                    {{--borderWidth: 1,--}}
                    {{--fill: false,--}}
                    {{--pointRadius: 10,--}}
                    {{--pointHoverRadius: 15,--}}
                    {{--showLine: true--}}
                {{--}]--}}
            {{--},--}}
            {{--options: {--}}
                {{--responsive: true,--}}
                {{--legend: {--}}
                    {{--display: false--}}
                {{--},--}}
                {{--title: {--}}
                    {{--display: false,--}}
                    {{--text: 'Point style: test'--}}
                {{--},--}}
                {{--elements: {--}}
                    {{--point: {--}}
                        {{--pointStyle: 'circle'--}}
                    {{--}--}}
                {{--},--}}
                {{--scales: {--}}
                    {{--yAxes: [{--}}
                        {{--stacked: false,--}}
                        {{--gridLines: {--}}
                            {{--display: false,--}}
                            {{--drawBorder: false--}}
                        {{--},--}}
                        {{--ticks: {--}}
                            {{--fontFamily: "Roboto",--}}
                            {{--fontColor:"#878787"--}}
                        {{--}--}}
                    {{--}],--}}
                    {{--xAxes: [{--}}
                        {{--stacked: false,--}}
                        {{--gridLines: {--}}
                            {{--display: false--}}
                        {{--},--}}
                        {{--ticks: {--}}
                            {{--fontFamily: "Roboto",--}}
                            {{--fontColor:"#878787"--}}
                        {{--}--}}
                    {{--}]--}}
                {{--}--}}
            {{--}--}}
        {{--});--}}

        $(window).on("load",function(){
            @if (!empty($transtypesnow))
            window.setTimeout(function(){
                $.toast({
                    heading: 'Updates!',
                    text: 'There are {{ number_format($transtypesnow) }} new transaction types added! Click here to view <a href="#">data</a>',
                    position: 'bottom-right',
                    loaderBg:'#e6b034',
                    icon: 'info',
                    hideAfter: 50000,
                    stack: 6
                });
            }, 500);
            @endif
            @if (!empty($prooflistsnow))
            window.setTimeout(function(){
            $.toast({
                heading: 'Updates!',
                text: 'There are {{ number_format($prooflistsnow) }} new prooflists uploaded! Click here to view <a href="#">data</a>',
                position: 'bottom-right',
                loaderBg:'#e6b034',
                icon: 'success',
                hideAfter: 50000,
                stack: 6
            });
            }, 2000);
            @endif
            @if (!empty($slsnow))
            window.setTimeout(function(){
                $.toast({
                    heading: 'Updates!',
                    text: 'There are {{ number_format($slsnow) }} new subsidiary ledgers uploaded! Click here to view <a href="#">data</a>',
                    position: 'bottom-right',
                    loaderBg:'#e6b034',
                    icon: 'warning',
                    hideAfter: 50000,
                    stack: 6
                });
            }, 3000);
            @endif
            @if (!empty($ledgersnow))
            window.setTimeout(function(){
                $.toast({
                    heading: 'Updates!',
                    text: 'There are {{ number_format($ledgersnow) }} new ledgers uploaded! Click here to view <a href="#">data</a>',
                    position: 'bottom-right',
                    loaderBg:'#e6b034',
                    icon: 'error',
                    hideAfter: 50000,
                    stack: 6
                });
            }, 4000);
            @endif
            @if (!empty($accountsnow))
            window.setTimeout(function(){
                $.toast({
                    heading: 'Updates!',
                    text: 'There are {{ number_format($accountsnow) }} new accounts uploaded! Click here to view <a href="#">data</a>',
                    position: 'bottom-right',
                    loaderBg:'#e6b034',
                    hideAfter: 50000
                });
            }, 5000);
            @endif
        });
    </script>
@endsection