@extends('layouts.backend')

@section('page_title', trans('latraining.history'))

@php
    $tabs = Request::segment(4);
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
                        @can('quiz-history')
                            <a class="nav-item nav-link @if ($tabs == 'user')
                                active
                                @endif" id="nav-user-tab" href="{{ route('module.quiz.history_user') }}" >
                                <img src="{{ asset('images/icon_menu_backend/internal_user_history.png') }}" alt="" width="25px" height="25px">
                                {{ trans('lamenu.internal_user_history') }}
                            </a>
                        @endcan

                        @can('quiz-history-user-second')
                            <a class="nav-item nav-link @if ($tabs == 'user-second')
                                active
                                @endif" id="nav-user-second-tab" href="{{ route('module.quiz.history_user_second') }}" >
                                <img src="{{ asset('images/icon_menu_backend/external_user_history.png') }}" alt="" width="25px" height="25px">
                                {{ trans('lamenu.external_user_history') }}
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
                        @switch(Request::segment(4))
                            @case('user')
                                @include('quiz::backend.history_user.index')
                                @break
                            @case('user-second')
                                @include('quiz::backend.history_user_second.index')
                                @break
                        @endswitch
                    </div>
                </div>
            </div>
        </div>
@endsection
