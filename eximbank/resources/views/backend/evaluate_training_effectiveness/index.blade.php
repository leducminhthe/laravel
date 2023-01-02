@extends('layouts.backend')

@section('page_title', trans('lamenu.rating_template'))

@php
    $tabs = Request::segment(2);
    $segment = Request::segment(3);
@endphp

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('latraining.training_evaluation'),
                'url' => route('module.rating.template')
            ],
            [
                'name' => trans('lamenu.rating_template'),
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
                        @can('rating-template')
                            <a class="nav-item nav-link @if ($tabs == 'evaluationform' && $segment=='rating')
                                active
                                @endif" id="nav-evaluationform-tab" href="{{ route('module.rating.template') }}" >
                                <img src="{{ asset('images/icon_menu_backend/rating_template.png') }}" alt="" width="25px" height="25px">
                                {{ trans('lamenu.rating_template') }}
                            </a>
                        @endcan

                        @can('rating-levels')
                            <a class="nav-item nav-link @if ($tabs == 'rating-organization')
                                active
                                @endif" id="nav-rating-organization-tab" href="{{ route('module.rating_organization') }}" >
                                <img src="{{ asset('images/icon_menu_backend/rating_organization.png') }}" alt="" width="25px" height="25px">
                                Mô hình Kirkpatrick
                            </a>
                        @endcan
                        @can('plan-app-template')
                            <a class="nav-item nav-link @if ($tabs == 'evaluationform' && $segment=='plan-app') active @endif" id="nav-rating-organization-tab"
                               href="{{ route('module.plan_app.template') }}" >
                                <img src="{{ asset('images/icon_menu_backend/rating_organization.png') }}" alt="" width="25px" height="25px">
                                Mẫu Kế hoạch ứng dụng
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
                            @case('evaluationform')
                                @if($segment=='rating')
                                    @include('rating::backend.template.index')
                                @else
                                    @include('planapp::backend.index')
                                @endif
                                @break
                            @case('rating-organization')
                                @include('rating::backend.rating_organization.index')
                                @break
                        @endswitch
                    </div>
                </div>
            </div>
        </div>
@endsection
