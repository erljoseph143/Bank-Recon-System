@extends('admin.layouts.main')

@section('crumb')

	<li class='breadcrumb-item active'>{{ $pagetitle }}</li>

@endsection

@section('content')

	<div class="row">
		<div class="col-sm-6 col-lg-3">
			<div class="widget-simple-chart text-right card-box">
				<div title="percentage of active users" class="circliful-chart" data-dimension="90" data-text="{{ $users_percent }}%" data-width="5" data-fontsize="14" data-percent="{{ $users_percent }}" data-fgcolor="#5fbeaa" data-bgcolor="#ebeff2"></div>
				<h3 class="text-success counter m-t-10">{{ $users->count }}</h3>
				<p class="text-muted text-nowrap m-b-10">Total users</p>
			</div>
		</div>

		<div class="col-sm-6 col-lg-3">
			<div class="widget-simple-chart text-right card-box">
				<div title="percentage of overall match bank statements" class="circliful-chart" data-dimension="90" data-text="{{ $bs_percent }}%" data-width="5" data-fontsize="14" data-percent="{{ $bs_percent }}" data-fgcolor="#3bafda" data-bgcolor="#ebeff2"></div>
				<h3 class="text-primary counter m-t-10">{{ $bs->count }}</h3>
				<p class="text-muted text-nowrap m-b-10">Total BS Entries</p>
			</div>
		</div>

		<div class="col-sm-6 col-lg-3">
			<div class="widget-simple-chart text-right card-box">
				<div title="percentage of overall match disbursements" class="circliful-chart" data-dimension="90" data-text="{{ $dis_percent }}%" data-width="5" data-fontsize="14" data-percent="{{ $dis_percent }}" data-fgcolor="#f76397" data-bgcolor="#ebeff2"></div>
				<h3 class="text-pink m-t-10"><span class="counter">{{ $dis->count }}</span></h3>
				<p class="text-muted text-nowrap m-b-10">Total Disburse Entries</p>
			</div>
		</div>

		<div class="col-sm-6 col-lg-3">
			<div class="widget-simple-chart text-right card-box">
				<div title="percentage of overall match checks" class="circliful-chart" data-dimension="90" data-text="{{ $check_percent }}%" data-width="5" data-fontsize="14" data-percent="{{ $check_percent }}" data-fgcolor="#98a6ad" data-bgcolor="#ebeff2"></div>
				<h3 class="text-inverse counter m-t-10">{{ $check->count }}</h3>
				<p class="text-muted text-nowrap m-b-10">Total Checks</p>
			</div>
		</div>
	</div>

	<div class="row">
		{{--<div class="col-md-6">--}}
			{{--<div class="portlet">--}}
				{{--<!-- /primary heading -->--}}
				{{--<div class="portlet-heading">--}}
					{{--<h3 class="portlet-title text-dark"> Bar Chart </h3>--}}
					{{--<div class="portlet-widgets">--}}
						{{--<a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a>--}}
						{{--<span class="divider"></span>--}}
						{{--<a data-toggle="collapse" data-parent="#accordion1" href="#bg-default"><i class="ion-minus-round"></i></a>--}}
						{{--<span class="divider"></span>--}}
						{{--<a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>--}}
					{{--</div>--}}
					{{--<div class="clearfix"></div>--}}
				{{--</div>--}}
				{{--<div id="bg-default" class="panel-collapse collapse show">--}}
					{{--<div class="portlet-body">--}}
						{{--<div class="text-center">--}}
							{{--<ul class="list-inline chart-detail-list">--}}
								{{--<li class="list-inline-item">--}}
									{{--<h5><i class="fa fa-circle m-r-5" style="color: #3bafda;"></i>Users</h5>--}}
								{{--</li>--}}
								{{--<li class="list-inline-item">--}}
									{{--<h5><i class="fa fa-circle m-r-5" style="color: #dcdcdc;"></i>Bank Statements</h5>--}}
								{{--</li>--}}
								{{--<li class="list-inline-item">--}}
									{{--<h5><i class="fa fa-circle m-r-5" style="color: #80deea;"></i>Disbursements</h5>--}}
								{{--</li>--}}
								{{--<li class="list-inline-item">--}}
									{{--<h5><i class="fa fa-circle m-r-5" style="color: #80deea;"></i>Checks</h5>--}}
								{{--</li>--}}
							{{--</ul>--}}
						{{--</div>--}}
						{{--<div id="morris-bar-example" style="height: 300px;"></div>--}}
					{{--</div>--}}
				{{--</div>--}}
			{{--</div>--}}
		{{--</div>--}}

		<!-- Donut Chart -->
		{{--<div class="col-md-6">--}}
			{{--<div class="portlet">--}}
				{{--<!-- /primary heading -->--}}
				{{--<div class="portlet-heading">--}}
					{{--<h3 class="portlet-title text-dark"> Donut Chart </h3>--}}
					{{--<div class="portlet-widgets">--}}
						{{--<a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a>--}}
						{{--<span class="divider"></span>--}}
						{{--<a data-toggle="collapse" data-parent="#accordion1" href="#portlet5"><i class="ion-minus-round"></i></a>--}}
						{{--<span class="divider"></span>--}}
						{{--<a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>--}}
					{{--</div>--}}
					{{--<div class="clearfix"></div>--}}
				{{--</div>--}}
				{{--<div id="portlet5" class="panel-collapse collapse show">--}}
					{{--<div class="portlet-body">--}}
						{{--<div id="morris-donut-example" style="height: 300px;"></div>--}}

						{{--<div class="text-center">--}}
							{{--<ul class="list-inline chart-detail-list">--}}
								{{--<li class="list-inline-item">--}}
									{{--<h5><i class="fa fa-circle m-r-5" style="color: #ededed;"></i>In-Store Sales</h5>--}}
								{{--</li>--}}
								{{--<li class="list-inline-item">--}}
									{{--<h5><i class="fa fa-circle m-r-5" style="color: #80deea;"></i>Mail-Order Sales</h5>--}}
								{{--</li>--}}
								{{--<li class="list-inline-item">--}}
									{{--<h5><i class="fa fa-circle m-r-5" style="color: #3bafda;"></i>Download Sales</h5>--}}
								{{--</li>--}}
							{{--</ul>--}}
						{{--</div>--}}
					{{--</div>--}}
				{{--</div>--}}
			{{--</div>--}}
			{{--<!-- /Portlet -->--}}
		{{--</div>--}}
	</div>

@endsection

@push('styles')

	<link href="{{ asset('admin/minton/plugins/jquery-circliful/css/jquery.circliful.css') }}" rel="stylesheet" type="text/css" />

	<!--Morris Chart CSS -->
	<link rel="stylesheet" href="{{ asset('admin/minton/plugins/morris/morris.css') }}">

	<link href="{{ asset('admin/assets/css/main.css') }}" rel="stylesheet">

@endpush

@push('scripts')
	<!-- circliful Chart -->
	<script src="{{ asset('admin/minton/plugins/jquery-circliful/js/jquery.circliful.min.js') }}"></script>
	<script src="{{ asset('admin/minton/plugins/waypoints/lib/jquery.waypoints.min.js') }}"></script>
	<script src="{{ asset('admin/minton/plugins/counterup/jquery.counterup.min.js') }}"></script>

	<!--Morris Chart-->
	{{--<script src="{{ asset('admin/minton/plugins/morris/morris.min.js') }}"></script>--}}
	{{--<script src="{{ asset('admin/minton/plugins/raphael/raphael-min.js') }}"></script>--}}
	{{--<script src="{{ asset('admin/minton/assets/pages/morris.init.js') }}"></script>--}}

	<script src="{{ asset('admin/assets/js/counterup.js') }}"></script>

@endpush