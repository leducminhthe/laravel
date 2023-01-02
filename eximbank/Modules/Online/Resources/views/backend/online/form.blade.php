@extends('layouts.backend')

@section('page_title', $page_title)

@section('header')
    {{-- <link rel="stylesheet" href="{{ asset('css/tree-folder.css') }}">
    <script src="{{asset('styles/vendor/jqueryplugin/printThis.js')}}"></script> --}}
    <script src="{{asset('modules/online/js/course.userpoint.js')}}"></script>
@endsection

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.training_organizations'),
                'url' => ''
            ],
            [
                'name' => trans('lamenu.online_course'),
                'url' => route('module.online.management')
            ],
            [
                'name' => $page_title,
                'url' => ''
            ],
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
    <div role="main" id="form_online_course">
        <div class="row">
            @if($model->id)
            <div class="col-md-12 text-center">
                @can('online-course-register')
                    <a href="{{ route('module.online.register', [$model->id]) }}" class="btn_link_online">
                        <div><i class="fa fa-edit"></i></div>
                        <div>{{ trans('latraining.internal_registration') }}</div>
                    </a>

                    {{-- <a href="{{ route('module.online.register_secondary', [$model->id]) }}" class="btn_link_online">
                        <div><i class="fa fa-edit"></i></div>
                        <div>{{ trans('latraining.external_enrollment') }}</div>
                    </a> --}}
                @endcan

                @if(!$user_invited)
                    @can('online-course-result')
                    <a href="{{ route('module.online.result', [$model->id]) }}" class="btn_link_online">
                        <div><i class="fa fa-briefcase"></i></div>
                        <div>{{ trans('latraining.training_result') }}</div>
                    </a>
                    @endcan

                    <a href="{{ route('module.online.quiz', [$model->id]) }}" class="btn_link_online">
                        <div><i class="fa fa-question-circle"></i></div>
                        <div>{{ trans('latraining.quiz_list') }}</div>
                    </a>

                    @can('online-course-rating-level-result')
                        <a href="{{ route('module.online.rating_level.list_report', [$model->id]) }}" class="btn_link_online">
                            <div><i class="fa fa-star"></i></div>
                            <div>{{ trans('latraining.rating_level_result') }}</div>
                        </a>
                    @endcan
                @endif
            </div>
            @endif
        </div>
        <br>
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                @if($model->id && userCan(['online-course-create', 'online-course-edit']) && !$user_invited)
                    <li class="nav-item">
                        <a href="{{ route('module.online.edit', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'edit' ? 'active' : '' }} {{ in_array('edit', $courseTabEdit) ? 'tab_edit' : '' }}">
                            {{ trans('latraining.info') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('module.online.edit_object', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'object' ? 'active' : '' }} {{ in_array('object', $courseTabEdit) ? 'tab_edit' : '' }}">
                            {{ trans('latraining.object_join') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('module.online.edit.prerequisite_condition', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'prerequisite-condition' ? 'active' : '' }} {{ in_array('prerequisite-condition', $courseTabEdit) ? 'tab_edit' : '' }}">
                            {{ trans('latraining.prerequisite_condition') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('module.online.edit.condition_register', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'condition-register' ? 'active' : '' }} {{ in_array('condition-register', $courseTabEdit) ? 'tab_edit' : '' }}">
                            {{ trans('latraining.condition_register') }}
                        </a>
                    </li>
                    {{--  <li class="nav-item">
                        <a href="{{ route('module.online.edit_tutorial', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'tutorial' ? 'active' : '' }} {{ in_array('tutorial', $courseTabEdit) ? 'tab_edit' : '' }}">
                            {{ trans('latraining.study_guide') }}
                        </a>
                    </li>  --}}
                    {{--  <li class="nav-item">
                        <a href="{{ route('module.online.edit_cost', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'cost' ? 'active' : '' }} {{ in_array('cost', $courseTabEdit) ? 'tab_edit' : '' }}">
                            {{ trans('latraining.training_cost') }}
                        </a>
                    </li>  --}}
                    <li class="nav-item">
                        <a href="{{ route('module.online.edit_activity_lesson', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'activity-lesson' ? 'active' : '' }} {{ in_array('activity-lesson', $courseTabEdit) ? 'tab_edit' : '' }}">
                            {{ trans('latraining.activity_lesson') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('module.online.edit_condition', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'condition' ? 'active' : '' }} {{ in_array('condition', $courseTabEdit) ? 'tab_edit' : '' }}">
                            {{ trans('latraining.conditions') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('module.online.edit_history', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'history' ? 'active' : '' }} {{ in_array('history', $courseTabEdit) ? 'tab_edit' : '' }}">
                            {{ trans('latraining.history') }}
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a href="{{ route('module.online.edit_libraryFile', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'libraryFile' ? 'active' : '' }} {{ in_array('libraryFile', $courseTabEdit) ? 'tab_edit' : '' }}">
                            {{ trans('latraining.library_file') }}
                        </a>
                    </li> --}}
                    {{--  <li class="nav-item">
                        <a href="{{ route('module.online.edit_ask_answer', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'ask-answer' ? 'active' : '' }} {{ in_array('ask-answer', $courseTabEdit) ? 'tab_edit' : '' }}">
                            {{ trans('latraining.ask_answer') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('module.online.edit_note_evaluate', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'note-evaluate' ? 'active' : '' }} {{ in_array('note-evaluate', $courseTabEdit) ? 'tab_edit' : '' }}">
                            {{ trans('latraining.note_evaluate') }}
                        </a>
                    </li>  --}}
                    @if(Module::has('Promotion') && $modulePromotion)
                        <li class="nav-item">
                            <a href="{{ route('module.online.edit_userpoint', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'userpoint' ? 'active' : '' }} {{ in_array('userpoint', $courseTabEdit) ? 'tab_edit' : '' }}">
                                {{ trans('latraining.reward_points') }}
                            </a>
                        </li>
                    @endif
                    {{--  <li class="nav-item">
                        <a href="{{ route('module.online.edit_setting_percent', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'setting_percent' ? 'active' : '' }} {{ in_array('setting_percent', $courseTabEdit) ? 'tab_edit' : '' }}">
                            {{ trans('latraining.setting_percent') }}
                        </a>
                    </li>  --}}
                    <li class="nav-item">
                        <a href="{{ route('module.online.edit_rating_level', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'rating_level' ? 'active' : '' }} {{ in_array('rating_level', $courseTabEdit) ? 'tab_edit' : '' }}">
                            {{ trans('lamenu.kirkpatrick_model') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('module.online.edit_setting_join', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'setting_join' ? 'active' : '' }} {{ in_array('setting_join', $courseTabEdit) ? 'tab_edit' : '' }}">
                            {{ trans('latraining.join_setup') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('module.online.edit_document', ['id' => $model->id]) }}" class="nav-link {{ $tab_3 == 'document' ? 'active' : '' }} {{ in_array('document', $courseTabEdit) ? 'tab_edit' : '' }}">
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
                        @include('online::backend.online.form.info')
                        @break
                    @case('edit')
                        @include('online::backend.online.form.info')
                        @break
                    @case('object')
                        @include('online::backend.online.form.object')
                        @break
                    @case('tutorial')
                        @include('online::backend.online.form.tutorial')
                        @break
                    @case('cost')
                        @include('online::backend.online.form.cost')
                        @break
                    @case('activity-lesson')
                        @include('online::backend.online.form.activity')
                        @break
                    @case('condition')
                        @include('online::backend.online.form.condition')
                        @break
                    @case('history')
                        @include('online::backend.online.form.history')
                        @break
                    @case('libraryFile')
                        @include('online::backend.online.form.libraryFile')
                        @break
                    @case('note-evaluate')
                        @include('online::backend.online.form.note_evaluate')
                        @break
                    @case('ask-answer')
                        @include('online::backend.online.form.ask_answer')
                        @break
                    @case('userpoint')
                        @include('online::backend.online.form.userpoint')
                        @break
                    @case('setting_percent')
                        @include('online::backend.online.form.setting_percent')
                        @break
                    @case('rating_level')
                        @include('online::backend.online.form.rating_level')
                        @break
                    @case('setting_join')
                        @include('online::backend.online.form.setting_join')
                        @break
                    @case('prerequisite-condition')
                        @include('online::backend.online.form.prerequisite_condition')
                        @break
                    @case('condition-register')
                        @include('online::backend.online.form.condition_register')
                        @break
                    @case('document')
                        @include('online::backend.online.form.document')
                        @break
                @endswitch
            </div>
        </div>

        <script type="text/javascript">
            var ajax_get_course_code = "{{ route('module.online.ajax_get_course_code') }}";
        </script>
        <script src="{{ asset('styles/module/online/js/online.js') }}"></script>
    </div>
@stop
