@extends('admin::layouts.admin-layout-default')

@section('css')
    @include('vof.admin.usermanagment::partials.style')
    <script
            src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous"></script>
@endsection

@section('title')
    <title>@lang('vof.admin.usermanagment::usermanagment.edit.headline') | VOF Admin</title>
@endsection()

@section('sidebar')
    @include('admin::partials.sidebar')
@endsection()

@section('content')
    <h2>@lang('vof.admin.usermanagment::usermanagment.edit.headline')</h2>
    <div class="row">
        <div class="col-sm-12">
            @include('vof.admin.usermanagment::partials.form-status')
        </div>
    </div>
    @include('vof.admin.usermanagment::partials.create-read-update-form', ['create' => false, 'disabled' => false, 'admin' => $admin])
@endsection()
