@extends('layouts.backend')

@section('page_title', trans('lacourse.learning_manager'))

@php
    $tabs = Request::segment(2);
@endphp

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lacourse.learning_manager'),
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
                        @can('training-roadmap')
                            <a class="mb-2 nav-item nav-link @if ($tabs == 'trainingroadmap')
                                active
                                @endif" id="nav-trainingroadmap-tab" href="{{ route('module.trainingroadmap') }}" >
                                <img src="{{ asset('images/icon_menu_backend/trainingroadmap.png') }}" alt="" width="25px" height="25px">
                                {{ trans('lamenu.trainingroadmap') }}
                            </a>
                        @endcan

                        @can('training-by-title')
                            <a class="mb-2 nav-item nav-link @if ($tabs == 'training-by-title')
                                active
                                @endif" id="nav-training-by-title-tab" href="{{ route('module.training_by_title') }}">
                                <img src="{{ asset('images/icon_menu_backend/learning_path.png') }}" alt="" width="25px" height="25px">
                                {{ trans('lamenu.learning_path') }}
                            </a>
                        @endcan

                        @can('training-by-title-result')
                            <a class="mb-2 nav-item nav-link @if ($tabs == 'training-by-title-result')
                                active
                                @endif" id="nav-training-by-title-result-tab" href="{{ route('module.training_by_title.result') }}">
                                <img src="{{ asset('images/icon_menu_backend/learning_path_result.png') }}" alt="" width="25px" height="25px">
                                {{ trans('lamenu.learning_path_result') }}
                            </a>
                        @endcan

                        {{--  @can('mergesubject')
                            <a class="mb-2 nav-item nav-link @if ($tabs == 'mergesubject')
                                active
                                @endif" id="nav-mergesubject-tab" href="{{ route('module.mergesubject.index') }}">
                                <img src="{{ asset('images/icon_menu_backend/merge_subject.png') }}" alt="" width="25px" height="25px">
                                {{ trans('lamenu.merge_subject') }}
                            </a>
                        @endcan

                        @can('splitsubject')
                            <a class="mb-2 nav-item nav-link @if ($tabs == 'splitsubject')
                                active
                                @endif" id="nav-splitsubject-tab" href="{{ route('module.splitsubject.index') }}">
                                <img src="{{ asset('images/icon_menu_backend/split_subject.png') }}" alt="" width="25px" height="25px">
                                {{ trans('lamenu.split_subject') }}
                            </a>
                        @endcan

                        @can('subjectcomplete')
                            <a class="mb-2 nav-item nav-link @if ($tabs == 'subjectcomplete')
                                active
                                @endif" id="nav-subjectcomplete-tab" href="{{ route('module.subjectcomplete.index') }}">
                                <img src="{{ asset('images/icon_menu_backend/subject_complete.png') }}" alt="" width="25px" height="25px">
                                {{ trans('lamenu.subject_complete') }}
                            </a>
                        @endcan

                        @can('movetrainingprocess')
                            <a class="mb-2 nav-item nav-link @if ($tabs == 'movetrainingprocess')
                                active
                                @endif" id="nav-movetrainingprocess-tab" href="{{ route('module.movetrainingprocess.index') }}">
                                <img src="{{ asset('images/icon_menu_backend/move_training_process.png') }}" alt="" width="25px" height="25px">
                                {{ trans('lamenu.move_training_process') }}
                            </a>
                        @endcan  --}}
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
                            @case('trainingroadmap')
                                @include('trainingroadmap::index.index')
                                @break
                            @case('training-by-title')
                                @include('trainingbytitle::backend.training_by_title.index')
                                @break
                            @case('training-by-title-result')
                                @include('trainingbytitle::backend.training_by_title_result.index')
                                @break
                            @case('mergesubject')
                                @include('mergesubject::backend.index')
                                @break
                            @case('splitsubject')
                                @include('splitsubject::backend.index')
                                @break
                            @case('subjectcomplete')
                                @include('subjectcomplete::backend.index')
                                @break
                            @case('movetrainingprocess')
                                @include('movetrainingprocess::backend.index')
                                @break
                        @endswitch
                    </div>
                </div>
            </div>
        </div>
@endsection
