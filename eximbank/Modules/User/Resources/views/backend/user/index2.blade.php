@extends('layouts.backend')

@section('page_title', trans('lamenu.user'))

@php
    $tabs = Request::segment(2);
@endphp

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.user'),
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
                @if (!\App\Models\User::isRoleLeader())
                    <nav>
                        <div class="nav nav-pills mb-4 tab_crse" id="nav-tab" role="tablist">
                            @can('user')
                                <a class="nav-item nav-link @if ($tabs == 'user')
                                    active
                                    @endif" id="nav-user-tab" href="{{ route('module.backend.user') }}" >
                                    <img src="{{ asset('images/icon_menu_backend/user.png') }}" alt="" width="25px" height="25px">
                                    {{ trans('lamenu.user') }}
                                </a>
                            @endcan

                            @can('user-take-leave')
                                <a class="nav-item nav-link @if ($tabs == 'user-take-leave')
                                    active
                                    @endif" id="nav-user-take-leave-tab" href="{{ route('module.backend.user_take_leave') }}" >
                                    <img src="{{ asset('images/icon_menu_backend/user_take_leave.png') }}" alt="" width="25px" height="25px">
                                    {{ trans('lamenu.user_take_leave') }}
                                </a>
                            @endcan

                            {{--  @can('user-contact')
                                <a class="nav-item nav-link @if ($tabs == 'user-contact')
                                active
                                @endif" id="nav-user-contact-tab" href="{{ route('backend.user-contact') }}">
                                    <img src="{{ asset('images/icon_menu_backend/user_contact.png') }}" alt="" width="25px" height="25px">
                                    {{ trans('lamenu.user_contact') }}
                                </a>
                            @endcan  --}}

                            @can('quiz-user-secondary')
                                <a class="nav-item nav-link @if ($tabs == 'user-secondary')
                                active
                                @endif" id="nav-user-secondary-tab" href="{{ route('module.quiz.user_secondary') }}" >
                                    <img src="{{ asset('images/icon_menu_backend/user_secondary.png') }}" alt="" width="25px" height="25px">
                                    {{ trans('lamenu.user_secondary') }}
                                </a>
                            @endcan
                        </div>
                    </nav>
                @endif
            </div>
        </div>
    </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="course_tab_content">
                    <div class="tab-content" id="nav-tabContent">
                        @switch(Request::segment(2))
                            @case('user')
                                @include('user::backend.user.index')
                                @break
                            @case('user-take-leave')
                                @include('user::backend.user_take_leave.index')
                                @break
                            @case('user-contact')
                                @include('backend.user_contact.index')
                                @break
                            @case('user-secondary')
                                @include('quiz::backend.user_secondary.index')
                                @break
                        @endswitch
                    </div>
                </div>
            </div>
        </div>
@endsection
