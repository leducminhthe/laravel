@extends('layouts.backend')

@section('page_title', $page_title)

@section('header')
<script src="{{asset('styles/vendor/jqueryplugin/printThis.js')}}"></script>
<script src="{{asset('modules/offline/js/course.userpoint.js')}}"></script>
<style>
    table tbody th {
        font-weight: normal !important;
    }
</style>
<link rel="stylesheet" href="{{ asset('css/tree-folder.css') }}">
@endsection

@section('breadcrumb')
    @php
        if ($model->id)
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
                    'name' => $page_title,
                    'url' => route('module.offline.edit',[$model->id])
                ],
            ];
        else
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
                    'name' => $page_title,
                    'url' => ''
                ]
            ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
@php
    $tabs = request()->get('tabs', null);
    $modulePromotion = array_key_exists('Promotion',Module::allEnabled());
    $tab_3 = Request::segment(3);
@endphp
<div role="main" id="form_offline_course">
    @include('offline::backend.includes.navgroup')
    <br>
    <div class="tPanel">
        <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
            @if($model->id && userCan(['offline-course-create', 'offline-course-edit']) && !$user_invited)
                <li class="nav-item">
                    <a href="{{ route('module.offline.edit', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'edit' ? 'active' : '' }} {{ in_array('edit', $courseTabEdit) ? 'tab_edit' : '' }}">
                        {{ trans('latraining.info') }}
                    </a>
                </li>
                {{--  <li class="nav-item">
                    <a href="{{ route('module.offline.class', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'class' ? 'active' : '' }} {{ in_array('class', $courseTabEdit) ? 'tab_edit' : '' }}">
                        {{ trans('latraining.classroom') }}
                    </a>
                </li>  --}}
                <li class="nav-item">
                    <a href="{{ route('module.offline.edit_object', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'object' ? 'active' : '' }} {{ in_array('object', $courseTabEdit) ? 'tab_edit' : '' }}">
                        {{ trans('latraining.object_join') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('module.offline.edit.prerequisite_condition', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'prerequisite-condition' ? 'active' : '' }} {{ in_array('prerequisite-condition', $courseTabEdit) ? 'tab_edit' : '' }}">
                        {{ trans('latraining.prerequisite_condition') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('module.offline.edit.condition_register', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'condition-register' ? 'active' : '' }} {{ in_array('condition-register', $courseTabEdit) ? 'tab_edit' : '' }}">
                        {{ trans('latraining.condition_register') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('module.offline.edit_cost', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'cost' ? 'active' : '' }} {{ in_array('cost', $courseTabEdit) ? 'tab_edit' : '' }}">
                        {{ trans('latraining.training_cost') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('module.offline.edit_cost_student', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'cost_student' ? 'active' : '' }} {{ in_array('cost_student', $courseTabEdit) ? 'tab_edit' : '' }}">
                        {{ trans('latraining.student_cost') }}
                    </a>
                </li>
                {{--  <li class="nav-item">
                    <a href="{{ route('module.offline.edit_activity_lesson', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'activity-lesson' ? 'active' : '' }} {{ in_array('activity-lesson', $courseTabEdit) ? 'tab_edit' : '' }}">
                        {{ trans('latraining.activity_lesson') }}
                    </a>
                </li>  --}}
                <li class="nav-item">
                    <a href="{{ route('module.offline.edit_condition', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'condition' ? 'active' : '' }} {{ in_array('condition', $courseTabEdit) ? 'tab_edit' : '' }}">
                        {{ trans('latraining.conditions') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('module.offline.edit_history', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'history' ? 'active' : '' }} {{ in_array('history', $courseTabEdit) ? 'tab_edit' : '' }}">
                        {{ trans('latraining.history') }}
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="{{ route('module.offline.edit_upload', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'upload' ? 'active' : '' }} {{ in_array('upload', $courseTabEdit) ? 'tab_edit' : '' }}">
                        {{ trans('latraining.file_manager') }}
                    </a>
                </li> --}}
                @if(Module::has('Promotion') && $modulePromotion)
                    <li class="nav-item">
                        <a href="{{ route('module.offline.edit_userpoint', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'userpoint' ? 'active' : '' }} {{ in_array('userpoint', $courseTabEdit) ? 'tab_edit' : '' }}">
                            {{ trans('latraining.reward_points') }}
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('module.offline.edit_rating_level', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'rating_level' ? 'active' : '' }} {{ in_array('rating_level', $courseTabEdit) ? 'tab_edit' : '' }}">
                        {{ trans('lamenu.kirkpatrick_model') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('module.offline.edit_setting_join', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'setting_join' ? 'active' : '' }} {{ in_array('setting_join', $courseTabEdit) ? 'tab_edit' : '' }}">
                        {{ trans('latraining.join_setup') }}
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="{{ route('module.offline.meeting', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'meeting' ? 'active' : '' }} {{ in_array('meeting', $courseTabEdit) ? 'tab_edit' : '' }}">
                        {{ trans('latraining.meeting_online') }}
                    </a>
                </li> --}}
                <li class="nav-item">
                    <a href="{{ route('module.offline.edit_document', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'document' ? 'active' : '' }} {{ in_array('document', $courseTabEdit) ? 'tab_edit' : '' }}">
                        Tài liệu học tập
                    </a>
                </li>
            @else
                <li class="nav-item">
                    <a href="#" class="nav-link active">{{ trans('latraining.info') }}</a>
                </li>
            @endif
        </ul>
        <div class="tab-content">
            @switch($tab_3)
                @case('create')
                    @include('offline::backend.offline.form.info')
                    @break
                @case('edit')
                    @include('offline::backend.offline.form.info')
                    @break
                @case('object')
                    @include('offline::backend.offline.form.object')
                    @break
                @case('cost')
                    @include('offline::backend.offline.form.cost')
                    @break
                @case('activity-lesson')
                    @include('offline::backend.offline.form.activity')
                    @break
                @case('cost_student')
                    @include('offline::backend.offline.form.cost_student')
                    @break
                @case('condition')
                    @include('offline::backend.offline.form.condition')
                    @break
                @case('history')
                    @include('offline::backend.offline.form.history')
                    @break
                @case('upload')
                    @include('offline::backend.offline.form.uploadFile')
                    @break
                @case('userpoint')
                    @include('offline::backend.offline.form.userpoint')
                    @break
                @case('rating_level')
                    @include('offline::backend.offline.form.rating_level')
                    @break
                @case('setting_join')
                    @include('offline::backend.offline.form.setting_join')
                    @break
                @case('meeting')
                    @include('offline::backend.offline.form.meeting')
                    @break
                @case('class')
                    @include('offline::backend.offline.form.class')
                    @break
                @case('prerequisite-condition')
                    @include('offline::backend.offline.form.prerequisite_condition')
                    @break
                @case('condition-register')
                    @include('offline::backend.offline.form.condition_register')
                    @break
                @case('document')
                    @include('offline::backend.offline.form.document')
                    @break
            @endswitch
        </div>
    </div>
    <script type="text/javascript">
        // ClassicEditor.create( document.querySelector( '.editor' ) )
        // .then( editor => {} )
        // .catch( error => {
        //     console.error(error);
        // } );
        var ajax_get_course_code = "{{ route('module.offline.ajax_get_course_code') }}";
    </script>
    <script src="{{ asset('styles/module/offline/js/offline.js') }}"></script>
</div>
@stop
