@extends('CPO.layouts.app')
@push('sidebar')
    @include('CPO.layouts.approver.sideBar')
@endpush

@push('content')
    @include('CPO.approver.requestedCash')
@endpush

@push('scripts')
    @include('CPO.layouts.approver.js')
@endpush