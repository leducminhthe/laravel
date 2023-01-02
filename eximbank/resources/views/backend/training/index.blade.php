@extends('layouts.backend')

@section('page_title', trans('lamenu.training_organizations'))

@php
    $tabs = Request::segment(2);
@endphp

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.training_organizations'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12" id="list-training">
            <div class="course_tabs" id="my-course">
                <nav>
                    <div class="nav nav-pills mb-4 tab_crse" id="nav-tab" role="tablist">
                        {{-- @can('virtual-classroom')
                            <a class="nav-item nav-link @if ($tabs == 'virtualclassroom')
                                active
                                @endif" id="nav-online-tab" href="{{ route('module.virtualclassroom.index') }}" >
                                <img src="{{ asset('images/icon_menu_backend/virtual.svg') }}" alt="" style="width: 32px;">
                                <span>{{ trans('backend.virtual_classroom') }}</span>
                            </a>
                        @endcan --}}

                        @can('online-course')
                            <a class="nav-item nav-link mb-1 @if ($tabs == 'online')
                                active
                                @endif" id="nav-online-tab" href="{{ route('module.online.management') }}" >
                                <img src="{{ asset('images/icon_menu_backend/online_course.png') }}" alt="">
                                <span>{{ trans('lamenu.online_course') }}</span>
                            </a>
                        @endcan

                        @can('offline-course')
                            <a class="nav-item nav-link mb-1 @if ($tabs == 'offline')
                                active
                                @endif" id="nav-offline-tab" href="{{ route('module.offline.management') }}" >
                                <img src="{{ asset('images/icon_menu_backend/offline_course.png') }}" alt="">
                                <span>{{ trans('lamenu.offline_course') }}</span>
                            </a>
                        @endcan

                        @can('training-plan')
                            <a class="nav-item nav-link mb-1 @if ($tabs == 'training-plan')
                                active
                                @endif" id="nav-training-plan-tab" href="{{ route('module.training_plan') }}">
                                <img src="{{ asset('images/icon_menu_backend/training_plan.png') }}" alt="">
                                <span>{{ trans('lamenu.training_plan') }}</span>
                            </a>
                        @endcan

                        @can('course-plan')
                            <a class="nav-item nav-link mb-1 @if ($tabs == 'course-plan')
                                active
                                @endif" id="nav-course-plan-tab" href="{{ route('module.course_plan.management') }}">
                                <img src="{{ asset('images/icon_menu_backend/month_elearning_plan.png') }}" alt="">
                                <span>{{ trans('lamenu.month_elearning_plan') }}</span>
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
                            @case('virtualclassroom')
                                @include('virtualclassroom::backend.virtual_classroom.index')
                                @break
                            @case('online')
                                @include('online::backend.online.index')
                                @break
                            @case('offline')
                                @include('offline::backend.offline.index')
                                @break
                            @case('training-plan')
                                @include('trainingplan::backend.plan.index')
                                @break
                            @case('course-plan')
                                @include('courseplan::backend.index')
                                @break
                            @case('courseold')
                                @include('courseold::index')
                                @break
                        @endswitch
                    </div>
                </div>
            </div>
        </div>
@endsection
