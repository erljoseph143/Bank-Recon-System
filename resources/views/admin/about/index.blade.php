@extends('admin.layouts.main')

@section('crumb')

    <li class='breadcrumb-item active'>{{ $pagetitle }}</li>

@endsection

@section('content')
    <div class="row">
        {!! $person1 !!}
        {!! $person2 !!}
        {!! $person3 !!}
        {!! $person4 !!}
    </div>

    {{--<div class="row">--}}
        {{--<div class="col-lg-4 col-md-6">--}}
            {{--<div class="text-center card-box">--}}
                {{--<div class="member-card">--}}
                    {{--<div class="thumb-lg member-thumb m-b-10 center-page">--}}
                        {{--<img src="{{ url('admin/minton/assets/images/users/avatar-4.jpg') }}" class="rounded-circle img-thumbnail" alt="profile-image">--}}
                    {{--</div>--}}

                    {{--<div class="">--}}
                        {{--<h4 class="m-b-5 mt-2">Jelarry Cadutdut</h4>--}}
                        {{--<p class="text-muted">@webdeveloper</p>--}}
                    {{--</div>--}}

                    {{--<button type="button" class="btn btn-success btn-sm w-sm waves-effect m-t-10 waves-light">Follow</button>--}}
                    {{--<button type="button" class="btn btn-danger btn-sm w-sm waves-effect m-t-10 waves-light">Message</button>--}}

                    {{--<div class="text-left m-t-40">--}}
                        {{--<p class="text-muted font-13"><strong>Full Name :</strong> <span class="m-l-15">Jelarry Cadutdut</span></p>--}}

                        {{--<p class="text-muted font-13"><strong>Mobile :</strong><span class="m-l-15">(123) 123 1234</span></p>--}}

                        {{--<p class="text-muted font-13"><strong>Email :</strong> <span class="m-l-15">cadutdutjedd@gmail.com</span></p>--}}

                        {{--<p class="text-muted font-13"><strong>Location :</strong> <span class="m-l-15">PHILIPPINES</span></p>--}}
                    {{--</div>--}}

                    {{--<ul class="social-links list-inline m-t-30 mb-0">--}}
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

                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

@endsection

@push('styles')

    <link href="{{ asset('admin/minton/plugins/jquery-circliful/css/jquery.circliful.css') }}" rel="stylesheet" type="text/css" />

@endpush

@push('scripts')
    <!-- circliful Chart -->
    <script src="{{ asset('admin/minton/plugins/jquery-circliful/js/jquery.circliful.min.js') }}"></script>
    <script src="{{ asset('admin/minton/plugins/waypoints/lib/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('admin/minton/plugins/counterup/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/counterup.js') }}"></script>
@endpush