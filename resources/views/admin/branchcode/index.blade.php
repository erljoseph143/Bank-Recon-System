@extends('admin.layouts.main')

@section('content')
    <div class="row">


        <div class="col-sm-12">

            <div class="card-box">
                <h4 class="header-title m-t-0 m-b-30">Manage Branch Codes</h4>

                <ul class="nav nav-tabs tabs-bordered">
                    <li class="nav-item">
                        <a href="#list" data-toggle="tab" aria-expanded="false" class="nav-link active">
                            Upload
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="view" href="#home" data-toggle="tab" aria-expanded="false" class="nav-link">
                            View
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="list">
                        <form id="upload-branchcode" action="{{ url('admin/branchcodes') }}" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="">Select File</label>
                                <input name="bcodefile" class="form-control" type="file">
                            </div>
                            <div class="form-group">
                                <label for="">Select Bank</label>
                                <select class="form-control" name="bank" id="bankname">
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->bankname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="home">
                        <div class="form-group">
                            <label for="">Select Bank</label>
                            <input id="posturl" type="hidden" value="{{ url('admin/branchcodes') }}">
                            <select class="form-control col-md-3" name="bank" id="bankname2">
                                @foreach($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->bankname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <i id="spinner" class="fa fa-spinner fa-spin" style="display: none"></i>
                        <table id="datatable" class="table table-hover">
                            <thead>
                            <tr>
                                <th>Bank Code</th>
                                <th>Branch Name</th>
                                <th>Bank Name</th>
                                {{--<th>Uploaded At</th>--}}
                                <th>Uploaded By</th>
                            </tr>

                            </thead>
                            <tbody>
                            {{--@foreach($branchcodes as $branchcode)--}}
                            {{--<tr>--}}
                                {{--<td>{{ $branchcode->bank_code }}</td>--}}
                                {{--<td>{{ $branchcode->branch_name }}</td>--}}
                                {{--<td>{{ $branchcode->bank->bankname }}</td>--}}
                                {{--<td>{{ $branchcode->created_at->format('F d Y') }}</td>--}}
                                {{--<td>{{ $branchcode->creator->firstname }}</td>--}}
                            {{--</tr>--}}
                            {{--@endforeach--}}
                            </tbody>
                        </table>
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
    <!-- circliful Chart -->
    <script src="{{ asset('admin/assets/js/branchcode.js') }}"></script>
@endpush