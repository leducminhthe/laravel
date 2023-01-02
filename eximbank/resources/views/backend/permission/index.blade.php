@extends('layouts.backend')

@section('page_title', trans('lamenu.permission'))

@section('header')

@endsection

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('lamenu.permission') }}
        </h2>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-2">
            <div class="scb-category-icon">
                <a href="{{ route('backend.permission.list_permisstion') }}"><span class="fa fa-building"></span></a>
                <a href="{{ route('backend.permission.list_permisstion') }}">{{ trans('laother.list_permission') }}</a>
            </div>
        </div>

        {{--<div class="col-md-2">
            <div class="scb-category-icon">
                <a href="{{ route('backend.permission_group') }}"><span class="fa fa-building"></span></a>
                <a href="{{ route('backend.permission_group') }}">{{ trans('backend.permission_group') }}</a>
            </div>
        </div>--}}

        {{--<div class="col-md-2">
            <div class="scb-category-icon">
                <a href="{{ route('backend.unit_permission') }}"><span class="fa fa-building"></span></a>
                <a href="{{ route('backend.unit_permission') }}">{{ trans('backend.unit_group') }}</a>
            </div>
        </div>--}}

    </div>
@stop
