@extends('admin.layouts.main')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <h4 class="header-title m-t-0 m-b-30">Manage Cash Logs</h4>
                <ul class="nav nav-pills navtab-bg">
                    <li class="nav-item">
                        <a href="#home" data-toggle="tab" aria-expanded="false" class="nav-link active">
                            User control
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
                        <form id="createcashlog" action="{{ url('admin/cashlogs/savecashlogs') }}">
                            {{ csrf_field() }}
                            <div class="card-box">
                                <h4 class="text-dark header-title m-t-0">
                                    Users
                                </h4>
                                <p class="text-muted font-13 m-b-30">
                                    Lists of liquidation clerk users
                                </p>
                                <div class="form-group">
                                    <label class="" for="">Select Users</label>
                                    <select class="form-control select2" name="user">
                                        @foreach($users as $user)
                                            <option value="{{ $user->user_id }}" data-cashlogs="{{ $user->cash_log }}">{{ $user->firstname . ' ' . $user->lastname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{--<div class="form-group inbox-widget nicescroll">--}}
                                    {{--@foreach($cashlogs as $cashlog)--}}
                                        {{--<div class="checkbox checkbox-primary">--}}
                                            {{--<input class"cashlogs" id="cashlog-{{ $cashlog->id }}" name="cashlog{{ $cashlog->id }}" type="checkbox" data-id="{{ $cashlog->id }}">--}}
                                            {{--<label for="cashlog-{{ $cashlog->id }}">--}}
                                                {{--{{ $cashlog->description }}--}}
                                            {{--</label>--}}
                                        {{--</div>--}}
                                    {{--@endforeach--}}
                                {{--</div>--}}
                                <div class="scrollable-wrap">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <div class="checkbox checkbox-primary checkbox-single">
                                                        <input type="checkbox" id="singleCheckbox2" value="option2" checked aria-label="Single checkbox Two">
                                                        <label></label>
                                                    </div>
                                                </th>
                                                <th>Description</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($cashlogs as $cashlog)
                                            <tr>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input class="cashlogs logscheck" id="cashlog-{{ $cashlog->id }}" name="cashlog{{ $cashlog->id }}" type="checkbox" data-id="{{ $cashlog->id }}">
                                                        <label for="cashlog-{{ $cashlog->id }}">
                                                        {{--{{ $cashlog->description }}--}}
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>{{ $cashlog->description }}</td>
                                                <td>
                                                    <select data-url="{{ route('cashlogs.update',$cashlog->id) }}" class="logs_status" name="" id="">
                                                        <option value="automatic" {{ (strtolower($cashlog->cash_status)=='automatic')?"selected":"" }}>Automatic</option>
                                                        <option value="manual" {{ (strtolower($cashlog->cash_status)=='manual')?"selected":"" }}>Manual</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <button class="btn btn-primary" type="submit">Save</button>

                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="add">
                        <form id="addcashlog" method="POST" action="{{ url('admin/cashlogs/postLogs') }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="">Description</label>
                                <input name="cashlogdesc" type="text" placeholder="Cash Log Description" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="action" value="savecashlog">
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
{{--    <script src="{{ asset('admin/minton/plugins/datatables/jquery.dataTables.min.js') }}"></script>--}}
{{--    <script src="{{ asset('admin/minton/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>--}}
    <link rel="stylesheet" href="{{ asset('admin/assets/css/main.css') }}">
@endpush

@push('scripts')
    <!-- circliful Chart -->
{{--    <script src="{{ asset('admin/minton/plugins/datatables/jquery.dataTables.min.js') }}"></script>--}}
{{--    <script src="{{ asset('admin/minton/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>--}}
    <script src="{{ asset('admin/assets/js/cashlog.js') }}"></script>
@endpush