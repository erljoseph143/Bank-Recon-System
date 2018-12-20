@extends('admin.layouts.main')
@section('content')
    <div class="row">
        <div class="col-sm-3">
            <div class="portlet">
                <div class="portlet-heading portlet-default">
                    <h3 class="portlet-title text-dark">
                        Setup Purpose
                    </h3>
                    <div class="portlet-widgets">
                        <a data-toggle="collapse" data-parent="#accordion1" href="#bg-default2"><i class="ion-minus-round"></i></a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div id="bg-default2" class="panel-collapse collapse show">
                    <div class="portlet-body">
                        <form id="cashpullout-form" action="{{ route('cashpullouts.index') }}" method="get">
                            <div class="form-group">
                                <label for="">Description</label>
                                <input name="data[0][description]" type="text" class="form-control" placeholder="description">
                            </div>
                            <button class="btn btn-primary waves-effect waves-light">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-9">
            <div class="portlet">
                <div class="portlet-heading portlet-default">
                    <h3 class="portlet-title text-dark">
                        Purposes
                    </h3>
                    <div class="portlet-widgets">
                        <a data-toggle="collapse" data-parent="#accordion1" href="#bg-default"><i class="ion-minus-round"></i></a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div id="bg-default" class="panel-collapse collapse show">
                    <div class="portlet-body">
                        <table id="purpose-t" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @include('admin.cashpullout.load')
                            </tbody>
                        </table>
                        <div class="paginator-container">
                            @include('admin.cashpullout.pagination')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <link href="{{ asset('admin/minton/plugins/jquery-circliful/css/jquery.circliful.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/assets/css/main.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <!-- circliful Chart -->
    <script src="{{ asset('admin/minton/plugins/jquery-circliful/js/jquery.circliful.min.js') }}"></script>
    <script src="{{ asset('admin/minton/plugins/waypoints/lib/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('admin/assets/plugins/swal/sweetalert.min.js') }}"></script>
    <script src="{{ asset('designex/vendor/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/cashpullout.js') }}"></script>
@endpush