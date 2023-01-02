@extends('layouts.backend')

@section('page_title', trans('latraining.all_course'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.teacher_permission'),
                'url' => route('backend.category.training_teacher.list_permission')
            ],
            [
                'name' => trans('latraining.all_course'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="nav-item"><a href="#course-teaching" class="nav-link active" role="tab" data-toggle="tab">{{ trans('latraining.course_teaching') }}</a></li>
                <li class="nav-item"><a href="#course-taught" class="nav-link" data-toggle="tab">{{trans('latraining.course_taught')}}</a></li>
            </ul>
            <div class="tab-content">
                <div id="course-teaching" class="tab-pane active">
                    @include('backend.category.training_teacher.course.course_teaching')
                </div>
                <div id="course-taught" class="tab-pane">
                    @include('backend.category.training_teacher.course.course_taught')
                </div>
            </div>
        </div>
    </div>
@stop
