@extends('admin::layouts.admin-layout-default')

@section('css')
    @include('vof.admin.usermanagment::partials.style')
    <script
            src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous"></script>
    @include('vof.admin.usermanagment::scripts.delete-modal-script')
@endsection

@section('title')
    <title>@lang('vof.admin.usermanagment::usermanagment.index.headline') | VOF Admin</title>
@endsection()

@section('sidebar')
    @include('admin::partials.sidebar')
@endsection()

@section('content')
    <h2>@lang('vof.admin.usermanagment::usermanagment.index.headline')</h2>
    @include('vof.admin.usermanagment::partials.table', ['admins' => $admins])
@endsection()
