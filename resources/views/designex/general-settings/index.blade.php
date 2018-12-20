@extends('designex.layouts.snoopy')
@section('content')
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">general settings</h6>
                </div>
                <div class="pull-right">
                    <a class="pull-left inline-block mr-15" data-toggle="collapse" href="#general_setting_1" aria-expanded="true"><i class="zmdi zmdi-chevron-down"></i><i class="zmdi zmdi-chevron-up"></i></a>
                    <a href="#" class="pull-left inline-block full-screen mr-15"><i class="zmdi zmdi-fullscreen"></i></a>
                </div>
                <div class="clearfix"></div>
            </div>
            <div id="general_setting_1" class="panel-wrapper collapse in">
                <div class="panel-body">

                </div>
            </div>
        </div>
    </div>
@endsection