@extends('CPO.layouts.app')
@push('sidebar')
    @include('CPO.layouts.borrower.sideBar')
@endpush

@push('content')
    @include('CPO.borrower.CPOform')
@endpush
@push('scripts')
    @include('CPO.layouts.borrower.js')
@endpush