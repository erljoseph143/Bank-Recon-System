@extends('admin.layouts.main')

@section('crumb')

    <li class='breadcrumb-item active'>{{ $pagetitle }}</li>

@endsection

@section('content')

    <div class="row">
        <div class="col-xl-3 col-lg-4">

            <div class="text-center card-box">
                <div class="member-card">
                    <div class="thumb-xl member-thumb m-b-10 center-block">
                        <img src="{{ url('admin/minton/assets/images/users/avatar-1.jpg') }}" class="rounded-circle img-thumbnail" alt="profile-image">
                    </div>

                    <div class="">
                        <h5 class="m-b-5">{{ $login_user_firstname . ' ' . $login_user_lastname }}</h5>
                        <p class="text-muted">{{ '@' . $user_type->user_type_name }}</p>
                    </div>
                    <form id="form-upload-profile-2134521" action="{{ url('admin/upload-new-profile') }}" method="post">
                        <label for="update-profile" class="btn btn-success btn-sm w-sm waves-effect m-t-10 waves-light">Update Profile</label>
                        <input style="display: none;" type="file" id="update-profile">
                    </form>

                    <div class="text-left m-t-40">
                        <p class="text-muted font-13"><strong>Full Name :</strong> <span class="m-l-15">{{ $login_user_firstname . ' ' . $login_user_lastname }}</span></p>

                        <p class="text-muted font-13"><strong>Username :</strong><span class="m-l-15">{{ $login_user_username }}</span></p>

                        <p class="text-muted font-13"><strong>Gender :</strong> <span class="m-l-15">{{ $login_user_gender }}</span></p>

                        <p class="text-muted font-13"><strong>Company :</strong> <span class="m-l-15">{{ $login_user_company->company }}</span></p>

                        <p class="text-muted font-13"><strong>Business unit :</strong> <span class="m-l-15">{{ $login_user_bu->bname }}</span></p>
                    </div>

                    {{--<ul class="social-links list-inline m-t-30">--}}
                        {{--<li class="list-inline-item">--}}
                            {{--<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="#" data-original-title="Facebook"><i class="fa fa-facebook"></i></a>--}}
                        {{--</li>--}}
                        {{--<li class="list-inline-item">--}}
                            {{--<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="#" data-original-title="Twitter"><i class="fa fa-twitter"></i></a>--}}
                        {{--</li>--}}
                        {{--<li class="list-inline-item">--}}
                            {{--<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="#" data-original-title="Skype"><i class="fa fa-skype"></i></a>--}}
                        {{--</li>--}}
                    {{--</ul>--}}

                </div>

            </div> <!-- end card-box -->

        </div>

        <div class="col-lg-8 col-xl-9">
            <div class="">
                <div class="card-box">
                    <ul class="nav nav-tabs tabs-bordered">
                        <li class="nav-item">
                            <a href="#cp" data-toggle="tab" aria-expanded="true" class="nav-link active">
                                CHANGE PASSWORD
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="cp">
                            <div class="alert alert-warning alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                Note: Create a password that you can easily remember.
                            </div>
                            <form id="update-password-235430" action="{{ url('admin/users/profile/update-password') }}" role="">
                                <div class="form-group">
                                    {{csrf_field()}}
                                    <label for="newpassword">New Password</label>
                                    <div id="np-info" class="alert alert-danger" style="display: none;"></div>
                                    <div id="validate-np" class="validate">
                                        <input id="newpassword" type="password" class="form-control"placeholder="At least 5 Characters" autocomplete="false" name="password" value="">
                                        <div id="complexity" aria-live="polite" class="">

                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary waves-effect waves-light w-md" type="submit">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-md-6">

        </div>
    </div>

@endsection

@push('styles')

    <link href="{{ asset('admin/minton/plugins/jquery-circliful/css/jquery.circliful.css') }}" rel="stylesheet" type="text/css" />

@endpush

@push('scripts')
    <script src="{{ asset('admin/assets/plugins/swal/sweetalert.min.js') }}"></script>
    <!-- circliful Chart -->
    <script type="text/javascript" src="{{ asset('admin/assets/js/profile.js') }}"></script>


@endpush