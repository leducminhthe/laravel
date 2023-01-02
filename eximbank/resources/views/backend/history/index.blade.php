@extends('layouts.backend')

@section('page_title', trans('latraining.history'))

@php
    $tabs = Request::segment(2);
@endphp

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('latraining.history'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="course_tabs" id="my-course">
                <nav>
                    <div class="nav nav-pills mb-4 tab_crse" id="nav-tab" role="tablist">
                        @can('model-history')
                            <a class="nav-item nav-link @if ($tabs == 'model-history')
                                active
                                @endif" id="nav-model-history-tab" href="{{ route('module.modelhistory.index') }}" >
                                <img src="{{ asset('images/icon_menu_backend/modelhistory.png') }}" alt="" width="25px" height="25px">
                                {{ trans('lamenu.modelhistory') }}
                            </a>
                        @endcan

                        @can('login-history')
                            <a class="nav-item nav-link @if ($tabs == 'login-history')
                            active
                            @endif" id="nav-login-history-tab" href="{{ route('backend.login-history') }}" >
                                <img src="{{ asset('images/icon_menu_backend/login_history.png') }}" alt="" width="25px" height="25px">
                                {{ trans('lamenu.login_history') }}
                            </a>
                        @endcan
                        

                        @can('log-view-course')
                            <a class="nav-item nav-link @if ($tabs == 'log-view-course')
                                active
                                @endif" id="nav-log-view-course-tab" href="{{ route('module.log.view.course.index') }}">
                                <img src="{{ asset('images/icon_menu_backend/log_view_course.png') }}" alt="" width="25px" height="25px">
                                {{ trans('lamenu.log_view_course') }}
                            </a>
                        @endcan
                    </div>
                </nav>
            </div>
        </div>
    </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="course_tab_content">
                    <div class="tab-content" id="nav-tabContent">
                        @switch(Request::segment(2))
                            @case('model-history')
                                @include('modelhistory::backend.index')
                                @break
                            @case('login-history')
                                @include('backend.login_history.index')
                                @break
                            @case('log-view-course')
                                @include('logviewcourse::backend.index')
                                @break
                        @endswitch
                    </div>
                </div>
            </div>
        </div>
@endsection
