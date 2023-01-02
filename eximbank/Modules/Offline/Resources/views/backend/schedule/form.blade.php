@extends('layouts.backend')

@section('page_title', trans('latraining.classroom'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.training_organizations'),
                'url' => ''
            ],
            [
                'name' => trans('lamenu.offline_course'),
                'url' => route('module.offline.management')
            ],
            [
                'name' => $course->name,
                'url' => route('module.offline.edit', ['id' => $course->id])
            ],
            /*[
                'name' => trans('latraining.classroom'),
                'url' => route('module.offline.class', ['id' => $course->id]),
                'drop-menu'=>$classArray
            ],*/
            [
                'name' => trans('latraining.schedule'),
                'url' => route('module.offline.schedule', ['id' => $course->id, 'class_id' => $class->id]),
            ],
            [
                'name' => $class->name,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
<div role="main" class="form_offline_course">
        @include('offline::backend.includes.navgroup')
        <br>
        <div class="row">
            <div class="col-12">
                <div class="tPanel">
                    <ul class="nav nav-pills mb-2" role="tablist" id="mTab">
                        <li class="nav-item"><a href="#base" class="nav-link active" role="tab" data-toggle="tab">{{ trans('latraining.info') }}</a></li>
                        @if ($model->id && $model->type_study != 3)
                            <li class="nav-item"><a href="#teacher" class="nav-link" data-toggle="tab">{{ trans('latraining.teacher') }}</a></li>
                        @endif
                    </ul>
                    <div class="tab-content">
                        <input type="hidden" id="teacherTNT" value="{{ $teacherTNT }}">
                        <div id="base" class="tab-pane active">
                            @include('offline::backend.schedule.info')
                        </div>
                        @if ($model->id)
                            <div id="teacher" class="tab-pane">
                                @include('offline::backend.schedule.teacher')
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
