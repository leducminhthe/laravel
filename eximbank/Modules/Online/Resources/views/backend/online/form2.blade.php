@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.online_course'),
                'url' => route('module.online.management')
            ],
            [
                'name' => trans('latraining.course_for_concentration'),
                'url' => route('module.online.course_for_offline')
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
        $tab_4 = Request::segment(4);
        $tab_3 = Request::segment(3);
        $type = $tab_3 == 'course-for-offline' ? 1 : 0;
    @endphp
    <div role="main" id="form_online_course_offline">
        <input type="hidden" class="type" value="{{ $type }}">
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                @if($model->id && userCan(['online-course-create', 'online-course-edit']) && !$user_invited)
                    <li class="nav-item">
                        <a href="{{ route('module.online.course_for_offline.edit', ['id' => $model->id]) }}" class="nav-link {{ $tab_4 == 'edit' ? 'active' : '' }} {{ in_array('edit', $courseTabEdit) ? 'tab_edit' : '' }}">
                            {{ trans('latraining.info') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('module.online.course_for_offline.edit_activity_lesson', ['id' => $model->id]) }}" class="nav-link {{ $tab_4 == 'activity-lesson' ? 'active' : '' }} {{ in_array('activity-lesson', $courseTabEdit) ? 'tab_edit' : '' }}">
                            {{ trans('latraining.activity_lesson') }}
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a href="{{ route('module.online.course_for_offline.edit_image_activity', ['id' => $model->id]) }}" class="nav-link {{ $tab_4 == 'image-activity' ? 'active' : '' }} {{ in_array('image-activity', $courseTabEdit) ? 'tab_edit' : '' }}">
                            {{ trans('latraining.image_activity') }}
                        </a>
                    </li> --}}
                    <li class="nav-item">
                        <a href="{{ route('module.online.course_for_offline.edit_condition', ['id' => $model->id]) }}" class="nav-link {{ $tab_4 == 'condition' ? 'active' : '' }} {{ in_array('condition', $courseTabEdit) ? 'tab_edit' : '' }}">
                            {{ trans('latraining.conditions') }}
                        </a>
                    </li>
                    @if(Module::has('Promotion') && $modulePromotion)
                        <li class="nav-item">
                            <a href="{{ route('module.online.course_for_offline.edit_userpoint', ['id' => $model->id]) }}" class="nav-link {{ $tab_4 == 'userpoint' ? 'active' : '' }} {{ in_array('userpoint', $courseTabEdit) ? 'tab_edit' : '' }}">
                                {{ trans('latraining.reward_points') }}
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ route('module.online.course_for_offline.edit_setting_percent', ['id' => $model->id]) }}" class="nav-link {{ $tab_4 == 'setting-percent' ? 'active' : '' }} {{ in_array('setting-percent', $courseTabEdit) ? 'tab_edit' : '' }}">
                            {{ trans('latraining.setting_percent') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('module.online.course_for_offline.quiz', ['id' => $model->id]) }}" class="nav-link {{ $tab_4 == 'quiz' ? 'active' : '' }} {{ in_array('quiz', $courseTabEdit) ? 'tab_edit' : '' }}">
                            {{ trans('latraining.quiz_list') }}
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="#" class="nav-link active">{{ trans('latraining.info') }}</a>
                    </li>
                @endif
            </ul>
            <div class="tab-content">
                @switch($tab_4)
                    @case('create')
                        @include('online::backend.online.form2.info')
                        @break
                    @case('edit')
                        @include('online::backend.online.form2.info')
                        @break
                    @case('activity-lesson')
                        @include('online::backend.online.form.activity')
                        @break
                    {{-- @case('image-activity')
                        @include('online::backend.online.form.image_activity')
                        @break --}}
                    @case('condition')
                        @include('online::backend.online.form.condition')
                        @break
                    @case('userpoint')
                        @include('online::backend.online.form.userpoint')
                        @break
                    @case('setting-percent')
                        @include('online::backend.online.form.setting_percent')
                        @break
                    @case('quiz')
                        @include('online::backend.online.form2.quiz')
                        @break
                @endswitch
            </div>
        </div>
    </div>
    <script src="{{asset('modules/online/js/course.userpoint.js')}}"></script>
@stop
