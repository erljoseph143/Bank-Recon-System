@extends('colacct.layouts.main')

{{--@section('crumb')--}}

    {{--<li class='breadcrumb-item active'>{{ $title }}</li>--}}

{{--@endsection--}}

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Dashboard<small>Control panel</small></h1>
            <ol class="breadcrumb">
                <li class="active">Dashboard</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Small boxes (Stat box) -->
            <div class="callout callout-default">
                    <h4>Welcome {{ $login_user->firstname }}!</h4>
                    <p></p>
            </div>

        </section><!-- /.content -->
    </div>

@endsection

@push('styles')
@endpush

@push('scripts')

@endpush