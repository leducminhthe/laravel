@extends('layouts.app')

@section('page_title', trans('lamenu.user_info'))

@php
    $tabs = Request::segment(2);
    $user_type = \Modules\Quiz\Entities\Quiz::getUserType();
@endphp
@section('header')
    @if ($tabs=='referer')
    <script type="text/javascript"  src="{{ asset('styles/module/qrcode/js/vue.min.js') }}"></script>
    <script type="text/javascript"  src="{{ asset('styles/module/qrcode/js/vue-qrcode-reader.browser.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('styles/module/qrcode/css/vue-qrcode-reader.css') }}">
    @endif

    @if ($agent->isMobile())
    <link href="{{ asset('vendor/swiper/css/swiper.min.css') }}" rel="stylesheet">
    <script src="{{ asset('vendor/swiper/js/swiper.min.js') }}"></script>
    @endif

    @if($tabs == 'training-by-title')
        <link rel="stylesheet" href="{{ asset('css/tree-folder.css') }}">
    @endif
    <style>
        .wrapper .infomation_of_user{
            height: 100%;
            background: white;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid _215b15 infomation_of_user">
        @include('user::frontend.web')
    </div>
    <div class="_215b17">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="course_tab_content mb-5">
                        <div class="tab-content" id="nav-tabContent">
                            @switch(Request::segment(2))
                                @case('my-capability')
                                    @include('user::frontend.my_capabilities.index')
                                    @break
                                @case('info')
                                    @include('user::frontend.info.index')
                                    @break
                                @case('referer')
                                    @include('user::frontend.referer.index')
                                    @break
                                @case('trainingprocess')
                                    @include('user::frontend.trainingprocess.index')
                                    @break
                                @case('working-process')
                                    @include('user::frontend.working_process.index')
                                    @break
                                @case('quizresult')
                                    @include('user::frontend.quizresult.index')
                                    @break
                                @case('subject_type')
                                    @include('user::frontend.subject_type.index')
                                    @break
                                @case('roadmap')
                                    @include('user::frontend.roadmap.index')
                                    @break
                                @case('subjectregister')
                                    @include('user::frontend.subjectregister.index')
                                    @break
                                @case('my-course')
                                    @include('user::frontend.my_course.index')
                                    @break
                                @case('my-promotion')
                                    @include('user::frontend.my_promotion.orders')
                                    @break
                                @case('my-promotion-history')
                                    @include('user::frontend.my_promotion.history')
                                    @break
                                {{-- @case('point-hist')
                                    @include('user::frontend.point_hist.index')
                                    @break --}}
                                @case('training-by-title')
                                    @include('user::frontend.training_by_title.index')
                                    @break
                                @case('my-career-roadmap')
                                    @include('user::frontend.my_career_roadmap.index2')
                                    @break
                                @case('violate-rules')
                                    @include('user::frontend.violate_rules.index')
                                    @break
                                @case('student-cost')
                                    @include('user::frontend.students_cost.index')
                                    @break

                                @case('my-certificate')
                                    @include('user::frontend.my_certificate.index')
                                    @break
                                @case('add-my-certificate' || 'edit-my-certificate')
                                    @include('user::frontend.my_certificate.form')
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
