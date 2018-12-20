@extends('designex.layouts.snoopy')
@section('content')
<div class="col-lg-3 col-xs-12">
    <div class="panel panel-default card-view  pa-0">
        <div class="panel-wrapper collapse in">
            <div class="panel-body  pa-0">
                <div class="profile-box">
                    <div class="profile-cover-pic">
                        <div class="fileupload btn btn-default">
                            <span class="btn-text">edit</span>
                            <input class="upload" type="file">
                        </div>
                        <div class="profile-image-overlay"></div>
                    </div>
                    <div class="profile-info text-center">
                        <div class="profile-img-wrap">
                            <img class="inline-block mb-10" src="{{ asset('snoopy/dist/img/gallery/mock2.jpg') }}" alt="user"/>
                            <div class="fileupload btn btn-default">
                                <span class="btn-text">edit</span>
                                <input class="upload" type="file">
                            </div>
                        </div>
                        <h5 class="profname block mt-10 mb-5 weight-500 capitalize-font txt-orange">{{ $login_user->firstname.' '.$login_user->lastname }}</h5>
                        <h6 class="profusername block capitalize-font pb-20">{{ $login_user->usertype->user_type_name }}</h6>
                    </div>
                    <div class="social-info">
                        <div class="row">
                            <div class="col-xs-4 text-center">
                                <span class="counts block head-font"><span class="counter-anim">345</span></span>
                                <span class="counts-text block">uploads</span>
                            </div>
                            <div class="col-xs-4 text-center">
                                <span class="counts block head-font"><span class="counter-anim">246</span></span>
                                <span class="counts-text block">downloads</span>
                            </div>
                            <div class="col-xs-4 text-center">
                                <span class="counts block head-font"><span class="counter-anim">898</span></span>
                                <span class="counts-text block">match</span>
                            </div>
                        </div>
                        <button class="btn btn-warning btn-block  btn-anim mt-30" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil"></i><span class="btn-text">edit profile</span></button>
                        <div id="myModal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h5 class="modal-title" id="myModalLabel">Edit Profile</h5>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Row -->
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="">
                                                    <div class="panel-wrapper collapse in">
                                                        <div class="panel-body pa-0">
                                                            <div class="col-sm-12 col-xs-12">
                                                                <div class="form-wrap">
                                                                    <form id="save-profile" action="{{ route('xprofileupdate') }}">
                                                                        {{ csrf_field() }}
                                                                        <div class="form-body overflow-hide">
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group">
                                                                                    <label class="control-label mb-10" for="firstname">First Name</label>
                                                                                    <div class="input-group">
                                                                                        <div class="input-group-addon"><i class="icon-user"></i></div>
                                                                                        <input value="{{ $login_user->firstname }}" name="firstname" type="text" class="form-control" id="firstname" placeholder="{{ $login_user->firstname }}" autocomplete="off">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="control-label mb-10" for="lastname">Last Name</label>
                                                                                    <div class="input-group">
                                                                                        <div class="input-group-addon"><i class="icon-user"></i></div>
                                                                                        <input value="{{ $login_user->lastname }}" name="lastname" type="text" class="form-control" id="lastname" placeholder="{{ $login_user->lastname }}" autocomplete="off">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="control-label mb-10" for="username">Username</label>
                                                                                    <div class="input-group">
                                                                                        <div class="input-group-addon"><i class="icon-user"></i></div>
                                                                                        <input value="{{ $login_user->username }}" name="username" type="text" class="form-control" id="username" placeholder="{{ $login_user->username }}" autocomplete="off">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="control-label mb-10" for="email">Email address</label>
                                                                                    <div class="input-group">
                                                                                        <div class="input-group-addon"><i class="icon-envelope-open"></i></div>
                                                                                        <input value="{{ $login_user->email }}" name="email" type="email" class="form-control" id="email" placeholder="{{ $login_user->email }}" autocomplete="off">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="control-label mb-10" for="contact">Contact number</label>
                                                                                    <div class="input-group">
                                                                                        <div class="input-group-addon"><i class="icon-phone"></i></div>
                                                                                        <input value="{{ $login_user->contact }}" name="contact" type="text" class="form-control" id="contact" placeholder="{{ $login_user->contact }}">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group">
                                                                                    <label class="control-label mb-10">Gender</label>
                                                                                    <div>
                                                                                        <div class="radio">
                                                                                            <input type="radio" name="gender" id="male" value="male" {{ ($login_user->gender=='male')?'checked':'' }}>
                                                                                            <label for="male">
                                                                                                Male
                                                                                            </label>
                                                                                        </div>
                                                                                        <div class="radio">
                                                                                            <input type="radio" name="gender" id="female" value="female" {{ ($login_user->gender=='female')?'checked':'' }}>
                                                                                            <label for="female">
                                                                                                Female
                                                                                            </label>
                                                                                        </div>
                                                                                        <div class="radio">
                                                                                            <input type="radio" name="gender" id="other" value="other" {{ ($login_user->gender!='male'&&$login_user->gender!='female')?'checked':'' }}>
                                                                                            <label for="other">
                                                                                                Other
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="control-label mb-10" for="location">Location</label>
                                                                                    <textarea class="form-control" name="location" id="location" rows="4" placeholder="{{ $login_user->location }}">{{ $login_user->location }}</textarea>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="control-label mb-10" for="about">About</label>
                                                                                    <textarea class="form-control" name="about" id="about" rows="6" placeholder="{{ $login_user->about }}">{{ $login_user->about }}</textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-actions mt-10">
                                                                            <button type="submit" name="updateprofile" class="btn btn-success mr-10 mb-30">Update profile</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-success waves-effect" data-dismiss="modal">Save</button>
                                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-9 col-xs-12">
    <div class="panel panel-default card-view pa-0">
        <div class="panel-wrapper collapse in">
            <div  class="panel-body pb-0">
                <div  class="tab-struct custom-tab-1">
                    <ul role="tablist" class="nav nav-tabs nav-tabs-responsive" id="myTabs_8">
                        <li class="active" role="presentation"><a  data-toggle="tab" id="profile_tab_8" role="tab" href="#profile_8" aria-expanded="false"><span>about</span></a></li>
                        <li role="presentation" class=""><a  data-toggle="tab" id="settings_tab_8" role="tab" href="#settings_8" aria-expanded="false"><span>settings</span></a></li>
                    </ul>
                    <div class="tab-content" id="myTabContent_8">
                        <div  id="profile_8" class="tab-pane fade active in" role="tabpanel">
                            <div class="col-md-12">
                                <div class="pt-20">
                                    <p class="profabout mb-20">{{ ($login_user->about)?$login_user->about:'Not Provided' }}</p>
                                </div>
                            </div>
                        </div>
                        <div  id="settings_8" class="tab-pane fade" role="tabpanel">
                            <!-- Row -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="">
                                        <div class="panel-wrapper collapse in">
                                            <div class="panel-body pa-0">
                                                <div class="form-body overflow-hide mb-20">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="control-label mb-10" for="prof_name">Name</label>
                                                            <div class="input-group">
                                                                <div class="input-group-addon"><i class="icon-user"></i></div>
                                                                <input type="text" class="form-control" id="prof_name" placeholder="{{ $login_user->firstname.' '.$login_user->lastname }}" disabled>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10" for="prof_email">Email address</label>
                                                            <div class="input-group">
                                                                <div class="input-group-addon"><i class="icon-envelope-open"></i></div>
                                                                <input type="email" class="form-control" id="prof_email" placeholder="{{ ($login_user->email)?$login_user->email:'Not Provided' }}" disabled>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10" for="prof_user">Username</label>
                                                            <div class="input-group">
                                                                <div class="input-group-addon"><i class="icon-lock"></i></div>
                                                                <input type="text" class="form-control" id="prof_user" placeholder="{{ ($login_user->username)?$login_user->username:'Not Provided' }}" value="" disabled>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10" for="prof_contact">Contact number</label>
                                                            <div class="input-group">
                                                                <div class="input-group-addon"><i class="icon-phone"></i></div>
                                                                <input type="text" class="form-control" id="prof_contact" placeholder="{{ ($login_user->contact)?$login_user->contact:'Not Provided' }}" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="control-label mb-10">Gender</label>
                                                            <div>
                                                                <div class="radio">
                                                                    <input type="radio" name="radio1" id="radio_01" value="option1" {{ ($login_user->gender=='male')?'checked':'' }}>
                                                                    <label for="radio_01">
                                                                        Male
                                                                    </label>
                                                                </div>
                                                                <div class="radio">
                                                                    <input type="radio" name="radio1" id="radio_02" value="option2" {{ ($login_user->gender=='female')?'checked':'' }}>
                                                                    <label for="radio_02">
                                                                        Female
                                                                    </label>
                                                                </div>
                                                                <div class="radio">
                                                                    <input type="radio" name="radio1" id="radio_03" value="option3" {{ ($login_user->gender!='male'&&$login_user->gender!='female')?'checked':'' }}>
                                                                    <label for="radio_02">
                                                                        Other
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10">Location</label>
                                                            <textarea class="form-control" name="" id="prof_location" rows="2" placeholder="{{ ($login_user->location)?$login_user->location:'Not Provided' }}" disabled></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
    <!--alerts CSS -->
    <link href="{{ asset('snoopy/vendors/bower_components/sweetalert/dist/sweetalert.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('scripts')
    <script src="{{ asset('designex/vendor/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <!-- Sweet-Alert  -->
    <script src="{{ asset('snoopy/vendors/bower_components/sweetalert/dist/sweetalert.min.js') }}"></script>
    <!-- Counter Animation JavaScript -->
    <script src="{{ asset('snoopy/vendors/bower_components/waypoints/lib/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('snoopy/vendors/bower_components/jquery.counterup/jquery.counterup.min.js') }}"></script>
@endsection
@section('endscripts')
    <script src="{{ asset('designex/larry-scripts/profile.js') }}"></script>
@endsection