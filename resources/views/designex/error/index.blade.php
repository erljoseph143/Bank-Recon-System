@extends('designex.layouts.snoopy')
@section('content')
    <div class="col-sm-12">
        <div class="panel panel-default panel-tabs card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">Fix error</h6>
                </div>
                <div class="pull-right">
                    <a href="#" class="slide-toggle pull-left inline-block search mr-15">
                        Controls
                    </a>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                    <div id="searchbox" class="collapse aria-slide">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="">table name</label>
                                    @for($i=0; $i < count($tables);$i++)
                                    <div class="radio radio-primary">
                                        <input type="radio" name="tableradio" id="table-{{$i}}" value="{{ $tables[$i] }}" {{ ($i==0)?'checked':'' }}>
                                        <label for="table-{{$i}}"> {{ $tables[$i] }} </label>
                                    </div>
                                    @endfor
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="">error type</label>
                                    @for($i=0; $i < count($errors);$i++)
                                        <div class="radio radio-primary">
                                            <input type="radio" name="typeradio" id="error-type-{{$i}}" value="{{ $errors[$i] }}" {{ ($i==0)?'checked':'' }}>
                                            <label for="error-type-{{$i}}"> {{ $errors[$i] }} </label>
                                        </div>
                                    @endfor
                                    or
                                    <input type="text">
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-sm-4">
                                <div class="form-group">

                                    <label for="">error value</label>
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-wrap">
                                <div class="table-responsive">
                                    <table id="errorlist" class="table table-hover display  pb-30">
                                        <thead>
                                            <tr>
                                                <th>error type</th>
                                                <th>error value</th>
                                                <th>table name</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>error type</th>
                                                <th>error value</th>
                                                <th>table name</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('styles')
@endsection
@section('scripts')
@endsection
@section('endscripts')
@endsection