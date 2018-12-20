@extends('admin.layouts.main')

@section('content')
    <div class="row">


        <div class="col-sm-12">

            <div class="card-box">
                <h4 class="header-title m-t-0 m-b-30">Manage Check Logs</h4>

                <ul class="nav nav-tabs tabs-bordered">
                    <li class="nav-item">
                        <a href="#home" data-toggle="tab" aria-expanded="false" class="nav-link active">
                            Lists
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#add" data-toggle="tab" aria-expanded="false" class="nav-link">
                            Add
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="home">

                        <table id="check-log" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    {{--<th>Action</th>--}}
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($checklogs as $log)
                                <tr>
                                    <td>{{ $log->description }}</td>
                                    {{--<td>--}}
                                        {{--<a href="">Edit </a>--}}
                                        {{--<a href="">Delete </a>--}}
                                    {{--</td>--}}
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                    <div class="tab-pane fade" id="add">
                        <form id="addchecklog" method="POST" action="{{ url('admin/checklogs') }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="">Description</label>
                                <input name="checklogdesc" type="text" placeholder="Check Log Description" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="action" value="savechecklog">
                                <button class="btn btn-primary" type="submit">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')

    <link href="{{ asset('admin/assets/css/main.css') }}" rel="stylesheet">

@endpush

@push('scripts')
    <script src="{{ asset('admin/assets/plugins/swal/sweetalert.min.js') }}"></script>
    <!-- circliful Chart -->
    <script src="{{ asset('admin/assets/js/checklog.js') }}"></script>
@endpush