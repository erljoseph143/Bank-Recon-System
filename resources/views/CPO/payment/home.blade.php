@extends('CPO.layouts.app')
@push('sidebar')
    @include('CPO.layouts.payment.sideBar')
@endpush

@push('content')
    @include('CPO.payment.cpo')
@endpush

@push('scripts')
    @include('CPO.layouts.payment.js')
@endpush