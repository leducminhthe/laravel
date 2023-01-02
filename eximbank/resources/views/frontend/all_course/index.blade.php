@extends('layouts.app')

@section('page_title', trans('latraining.all_course'))

@section('header')
    @php
        $get_color_menu = \App\Models\SettingColor::where('name','color_menu')->first();
        $background_menu = $get_color_menu->background;
        $hover_background_menu = $get_color_menu->hover_background;
    @endphp
    @if ($course_type == 3)
        <link rel="stylesheet" href="{{ asset('css/tree-folder.css') }}">
        <style>
            .button_training i {
                color: {{ $background_menu }};
            }
            .button_training:hover i {
                color: {{ $hover_background_menu }};
            }
        </style>
    @endif
    <style>
        .filter_show .icon_filter {
            width: 25px;
            height: 25px;
            background-color: {{ $background_menu }};
        }
        .filter_show .active_list,
        .filter_show .icon_filter:hover {
            background-color: {{ $hover_background_menu }};
        }
        #training-by-title .progress2 {
            height: 0.7rem !important;
        }
    </style>
@endsection

@section('content')
@php
    $get_color = \App\Models\Config::where('name','setting_color')->first();
    $get_hover_color = \App\Models\Config::where('name','setting_hover_color')->first();
    $trainingProgram = request()->get('trainingProgramId') ? request()->get('trainingProgramId') : 0;
    if($trainingProgram > 0) {
        $nameTrainingProgram = \App\Models\Categories\TrainingProgram::find($trainingProgram, ['name']);
    }
@endphp
    <link rel="stylesheet" href="{{ myasset('css/all_course.css') }}">
    <div class="sa4d25">
        <div class="container-fluid" id="all_course">
            <div class="row">
                <div class="col-md-12">
                    <div class="_14d25">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="ibox-content forum-container">
                                    <h2 class="st_title">
                                        <a href="/" class="home">
                                            <i class="uil uil-apps"></i>
                                            <span>{{ trans('lamenu.home_page') }}</span>
                                        </a>
                                        @if ($course_type == 1)
                                            <i class="uil uil-angle-right"></i>
                                            <a href="{{ route('frontend.all_course',['type' => 1]) }}">{{ trans('lamenu.online_course') }}</a>
                                            @if ($nameTrainingProgram)
                                                <i class="uil uil-angle-right"></i>
                                                <span>{{ $nameTrainingProgram->name }}</span>
                                            @endif
                                        @elseif ($course_type == 2)
                                            <i class="uil uil-angle-right"></i>
                                            <a href="{{ route('frontend.all_course',['type' => 2]) }}">{{ trans('lamenu.offline_course') }}</a>
                                        @elseif ($course_type == 3)
                                            <i class="uil uil-angle-right"></i>
                                            <a href="{{ route('frontend.all_course',['type' => 3]) }}">{{ trans('latraining.my_course') }}</a>
                                        @elseif ($course_type == 4)
                                            <i class="uil uil-angle-right"></i>
                                            <a href="{{ route('frontend.all_course',['type' => 4]) }}">Khóa học đánh dấu</a>
                                        @else
                                            <i class="uil uil-angle-right"></i>
                                            <a href="{{ route('frontend.all_course',['type' => 0]) }}">{{ trans('lamenu.course') }}</a>
                                        @endif
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-2">
                                <button class="btn open_search" onclick="openSearch()">
                                    <i class="fas fa-search-plus"></i>
                                    <span>{{ trans('labutton.search') }}</span>
                                </button>
                                <div class="float-right filter_show">
                                    <div class="d_flex_align">
                                        <span class="mr-2">{{ trans('laother.type_form') }}</span>
                                        <div class="icon_filter cursor_pointer mr-2" onclick="filterShow()"
                                            style="-webkit-mask: url({{ asset('images/svg-frontend/setting_all_course.svg') }}) no-repeat;
                                            mask: url({{ asset('images/svg-frontend/setting_all_course.svg') }}) no-repeat;
                                            -webkit-mask-size: 25px 25px;">
                                        </div>
                                        <div class="icon_filter cursor_pointer mr-2 {{ $listType == 'horizontal' ? 'active_list' : '' }}" onclick="horizontalMenu({{ $course_type }})" title="Danh sách nằm ngang"
                                            style="-webkit-mask: url({{ asset('images/svg-frontend/list.svg') }}) no-repeat;
                                            mask: url({{ asset('images/svg-frontend/list.svg') }}) no-repeat;
                                            -webkit-mask-size: 25px 25px;">
                                        </div>
                                        <div class="icon_filter cursor_pointer mr-2 {{ $listType != 'horizontal' ? 'active_list' : '' }}" onclick="verticalMenu({{ $course_type }})" title="Danh sách nằm dọc"
                                            style="-webkit-mask: url({{ asset('images/svg-frontend/vertical_menu.svg') }}) no-repeat;
                                            mask: url({{ asset('images/svg-frontend/vertical_menu.svg') }}) no-repeat;
                                            -webkit-mask-size: 23px 23px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 all_search">
                                <div class="_14d25 mt-1 mb-2">
                                    <div class="row m-0">
                                        <div class="col-6 col-md-3 mb-2 p-1">
                                            <div>
                                                <h3>{{ trans('laother.status') }}</h3>
                                                <input type="checkbox" id="registed" name="status_course" class="search_status" value="1">
                                                <label for="registed">{{ trans('laother.register') }}</label><br>
                                                <input type="checkbox" id="pending" name="status_course" class="search_status" value="3">
                                                <label for="pending">{{ trans('laother.pending') }}</label><br>
                                                <input type="checkbox" id="finish" name="status_course" class="search_status" value="4">
                                                <label for="finish">{{ trans('latraining.finish') }}</label><br>
                                                <input type="checkbox" id="end" name="status_course" class="search_status" value="5">
                                                <label for="end">{{ trans('laother.finished') }}</label><br>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-1">
                                            <div>
                                                <h3>{{ trans('lamenu.course') }}</h3>
                                                <input type="checkbox" id="course_learning" name="search_course_type" class="search_course_type" value="4">
                                                <label for="course_learning">{{ trans('latraining.course_studied') }}</label><br>
                                                <input type="checkbox" id="course_add" name="search_course_type" class="search_course_type" value="5">
                                                <label for="course_add">{{ trans('latraining.course_check') }}</label><br>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 mb-2 p-1">
                                            <h3>{{ trans('lamenu.category') }}</h3>
                                            @if ($trainingProgram == 0)
                                                <div class="search_training_program mb-3">
                                                    <span onclick="showTrainingProgram()">{{ trans('latraining.training_program') }}</span>
                                                </div>
                                            @endif
                                            <div class="search_level_subject">
                                                <span onclick="showLevelSubject()">{{ trans('latraining.subject') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 mb-2 p-1">
                                            <h3>{{ trans('latraining.time') }}</h3>
                                            <div class="mb-2">
                                                <div class="ui left input swdh11">
                                                    <input class="datetimepicker form-control" type="text" id="fromdate" placeholder="{{ trans('laother.start_date') }}" name="fromdate">
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <div class="ui left input swdh11">
                                                    <input class="datetimepicker form-control" type="text" id="todate" placeholder="{{ trans('laother.end_date') }}" name="todate">
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <div class="ui left input swdh11">
                                                    <input class="form-control w-100" type="text" id="search_course" placeholder="{{ trans('labutton.search') }}" name="search">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if ($course_type == 3)
                            <div class="row mt-2 progress_user_learn mx-0">
                                <div class="col-12">
                                    <div class="progress_user mb-3 pt-1 d_flex_align">
                                        <img class="mr-1" src="{{ asset('images/destination.png') }}" alt="">
                                        <span class="title text-center"><strong>1.</strong> % {{ trans('laother.learning_progress_training') }} <strong>{{$profile_name->firstname }} ({{ $countSubjectRoadmapCompleted }}/{{ $totalSubjectRoadmap }})</strong> | <span class="cursor_pointer" onclick="detailRoadmapCourse()">{{ trans('laother.see_detail') }}</span></span>
                                    </div>
                                    <div class="progress progress2 bg-white mb-1" style="border-radius: 10px;">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $progressRoadmap }}%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                            {{ number_format($progressRoadmap) }}%
                                        </div>
                                        @if ($progressRoadmap < 100 && $progressRoadmap > 1)
                                            <img src="{{ asset('images/runner.png') }}" alt="">
                                        @endif
                                    </div>
                                    <div class="pl-1 wrapped_user_complete text-center">
                                        <span class="user_complete">{{ trans('laother.you_have_completed') }}</span>
                                        <span><strong> {{ number_format($progressRoadmap) }}%</strong></span>
                                    </div>
                                </div>
                                <div class="col-12 list_user_course mt-2 training_user">
                                    <div class="row">
                                        <div class="col-md-6 col-12 p-1 text-center">
                                            <img class="mr-1" src="{{ asset('images/click.png') }}" alt="">
                                            <span class="title"><strong>2.</strong> {{ trans('laother.training_route_reality') }} <strong>{{$profile_name->firstname }}</strong> | <span class="cursor_pointer" onclick="detailTrainingByTitle()">{{ trans('laother.see') }}</span></span>
                                            <div class="progress progress2 bg-white mt-2" style="border-radius: 10px;">
                                                <div class="progress-bar" role="progressbar" style="width: {{ $progressTrainingByTitle }}%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                                    {{ number_format($progressTrainingByTitle) }}%
                                                </div>
                                                @if ($progressTrainingByTitle < 100 && $progressTrainingByTitle > 1)
                                                    <img src="{{ asset('images/runner.png') }}" alt="">
                                                @endif
                                            </div>
                                            <div class="mt-1 wrapped_user_complete text-center">
                                                <span class="user_complete">{{ trans('laother.you_have_completed') }} {{ number_format($progressTrainingByTitle) }}%</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12 p-1 text-center">
                                            <img class="mr-1" src="{{ asset('images/click.png') }}" alt="">
                                            <span class="title"><strong>3.</strong> {{ trans('laother.career_roadmap_by') }} <strong>{{$profile_name->firstname }}</strong> | <span class="cursor_pointer" onclick="detailCareerRoadmap()">{{ trans('laother.see') }}</span></span>
                                            <div class="progress progress2 bg-white mt-2" style="border-radius: 10px;">
                                                <div class="progress-bar" role="progressbar" style="width: {{ $progressCareerRoadmap }}%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                                    {{ number_format($progressCareerRoadmap) }}%
                                                </div>
                                                @if ($progressCareerRoadmap < 100 && $progressCareerRoadmap > 1)
                                                    <img src="{{ asset('images/runner.png') }}" alt="">
                                                @endif
                                            </div>
                                            <div class="mt-1 wrapped_user_complete text-center">
                                                <span class="user_complete">{{ trans('laother.you_have_completed') }} {{ number_format($progressCareerRoadmap) }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row list_user_course d_flex_align py-3">
                                        <img class="mr-1" src="{{ asset('images/list_course.png') }}" alt="">
                                        <span class="title text-center">{{ trans('laother.list_course_by') }} <strong>{{$profile_name->firstname }} ({{ $count_course }})</strong></span>
                                    </div>
                                </div>
                                <div class="button_show button_training" onclick="showUserTraining()">
                                    <i class="fas fa-angle-down"></i>
                                </div>
                                <div class="button_hide button_training" onclick="hideUserTraining()">
                                    <i class="fas fa-angle-up"></i>
                                </div>
                            </div>
                        @endif
                        <div class="row mt-1">
                            <div class="col-md-12">
                                <div class="_14d25 mt-1">
                                    @if (!empty($items))
                                        <div class="row m-0" id="results">
                                        </div>
                                        <div class="ajax-loading text-center mb-5">
                                            <div class="spinner-border" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </div>
                                        @if ($course_type == 3)
                                            <div class="more_course">
                                                <button class="btn" onclick="moreCourse()">{{ trans('laother.show_more') }}</button>
                                            </div>
                                        @endif
                                    @else
                                        <div class="row">
                                            <div class="fcrse_1 mb-20">
                                                <div class="text-center">
                                                    <span>@lang('app.not_found')</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="wrraped_related_course">
                                    @if ($course_type == 3 && !$course_subject->isEmpty())
                                        <div class="row related_course">
                                            <div class="col-12">
                                                <h3 class="related_course_title mb-2">
                                                    <span>{{ trans('latraining.related_course') }}</span>
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="row m-0">
                                            @foreach($course_subject as $item)
                                                @include('frontend.all_course.item', ['type' => $item->course_type])
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($course_type == 3)
        {{-- MODAL CHI TIẾT LỘ TRÌNH --}}
        <div class="modal fade" id="modal-roadmap-course">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ trans('laother.detail_study_roadmap') }} {{$profile_name->firstname }}</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <table class="tDefault table table-bordered" id="table-roadmap">
                                    <thead>
                                        <tr>
                                            <th data-field="subject_code" data-width="10%">@lang('laprofile.subject_code')</th>
                                            <th data-field="subject_name">@lang('laprofile.subject')</th>
                                            <th data-field="title_name" data-formatter="result_formatter_roadmap" class="td-title-name" data-align="center"></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL CHI TIẾT LỘ TRÌNH ĐÀO TẠO --}}
        <div class="modal fade" id="modal-training-by-title">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ trans('laother.detail_training_title_by') }} <strong>{{$profile_name->firstname }}</strong></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12" id="training-by-title">
                                <div class="row">
                                    <div class="col-md-3 col-12 p-1 img_training">
                                        <img src="{{ image_file($imageTrainingByTitle->image2) }}" alt="" width="100%">
                                    </div>
                                    <div class="col-md-9 col-12 p-1">
                                        <div id="tree-unit-training" class="tree">
                                            <div class="">
                                                <img src="{{ image_file(\App\Models\Profile::avatar()) }}" alt="" class="w-5 rounded-circle">
                                                <span class="h4">
                                                    {{ trans('latraining.completed') }}
                                                    <span class="total_percent font-weight-bold">{{ $progress }}</span>%
                                                    ({{ $count_subject_completed }}/{{ $count_training_by_title_detail }} {{ trans('lamenu.course') }})
                                                </span>
                                            </div>
                                            <ul class="ul_parent">
                                                @php
                                                    $old_date = '';
                                                @endphp
                                                @foreach($training_by_title_category as $key => $item)
                                                    <li>
                                                        <div class="item mb-1">
                                                            <i class="uil uil-plus"></i>
                                                            @if ($key == 0)
                                                                @php
                                                                    $old_date =\Carbon\Carbon::parse($start_date)->addDays($item->num_date_category + 1);
                                                                @endphp
                                                                <a href="javascript:void(0)" data-id="{{ $item->id }}" data-route="{{ route('module.frontend.user.training_by_title.tree_folder.get_child_level_subject', ['id' => $item->id,'start_date' => $start_date]) }}" class="tree-item-level-subject">
                                                                    <span class="font-weigth-bold">{{ mb_strtoupper($item->name, 'UTF-8') }}</span>
                                                                    {{-- ({{ $count_subject_completed . '/'. $item->trainingtitledetail->count() }}) --}}
                                                                    <span>
                                                                        ({{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($start_date)->addDays($item->num_date_category)->format('d/m/Y')  }})
                                                                    </span>
                                                                </a>
                                                            @else
                                                                @php
                                                                    $start_date = \Carbon\Carbon::parse($old_date)->format('Y-m-d');
                                                                    $old_value_format = \Carbon\Carbon::parse($old_date)->format('d/m/Y');
                                                                    $end_date = \Carbon\Carbon::parse($old_date)->addDays($item->num_date_category);
                                                                    $old_date = \Carbon\Carbon::parse($old_date)->addDays($item->num_date_category + 1);
                                                                @endphp
                                                                <a href="javascript:void(0)" data-id="{{ $item->id }}" data-route="{{ route('module.frontend.user.training_by_title.tree_folder.get_child_level_subject', ['id' => $item->id,'start_date' => $start_date]) }}" class="tree-item-level-subject">
                                                                    <span class="font-weigth-bold">{{ mb_strtoupper($item->name, 'UTF-8') }}</span> ({{$count_subject_completed . '/'. $item->trainingtitledetail->count()}})

                                                                    <span>
                                                                        ( {{ $old_value_format }} - {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y')  }} )
                                                                    </span>
                                                                </a>
                                                            @endif
                                                        </div>
                                                        <div id="list{{ $item->id }}"></div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL CHI TIẾT LỘ TRÌNH NGHỀ NGHIỆP --}}
        <div class="modal fade" id="modal-career-roadmap">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ trans('laother.career_roadmap_by') }} <strong>{{$profile_name->firstname }}</strong></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="row mt-1">
                                    @foreach($sub_titles as $index => $sub_title)
                                        <div class="text-center">
                                            @if($sub_title->percent == 100)
                                                <img src="{{ asset('themes/mobile/img/check.png') }}" class="img-responsive" width="40px" height="40px">
                                            @else
                                                <img src="{{ asset('themes/mobile/img/padlock.png') }}" class="img-responsive" width="40px" height="40px">
                                            @endif
                                            <p class="title_name" style="width: 75px; word-break: break-word">
                                                {{ $sub_title->title->name }}
                                            </p>
                                        </div>
                                        @if($index < count($sub_titles) )
                                            <span class="progress progress2 {{ $sub_title->percent < 1 ? 'not' : '' }}" style="flex-basis: 10%; margin-top: 10px;">
                                                <div class="progress-bar" style="width: {{ $sub_title->percent }}%" role="progressbar" aria-valuemin="0" aria-valuemax="100"> {{ number_format($sub_title->percent, 2) }}% </div>
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div id="tree-unit" class="tree bg-white">
                                    @foreach($roadmaps as $roadmap)
                                        <div class="bg-primary roadmap_name p-2">
                                            {{ $roadmap->name }}
                                        </div>
                                        @php
                                            $sub_titles = $roadmap->getTitles('0');
                                        @endphp
                                        @foreach($sub_titles as $index => $sub_title)
                                            <div class="row item mt-2">
                                                <div class="col-md-10">
                                                    <a href="javascript:void(0)" data-id="{{ $sub_title->id }}" data-type="1" class="tree-item">
                                                        <i class="uil uil-plus"></i> {{ str_repeat('-- ', $sub_title->level) . $sub_title->title->name }}
                                                    </a>
                                                    <span class="seniority_careers_roadmap">{{ trans('laprofile.seniority') }}: {{ $sub_title->seniority }}</span>
                                                </div>
                                                <div class="col-md-2 text-right">
                                                    <a href="javascript:void(0)" class="btn view-career"
                                                        data-id="{{ $sub_title->title->id }}" data-name="{{ $sub_title->title->name }}">
                                                        <i class="fa fa-eye"></i> @lang('laother.see')
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12" id="list{{ $sub_title->id }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="course-modal" tabindex="-1" role="dialog" aria-labelledby="course-modal-label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title title-name" id="course-modal-label"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table bootstrap-table text-nowrap" id="table_course_modal">
                            <thead>
                            <tr>
                                <th data-width="5%" data-formatter="index_formatter">#</th>
                                <th data-field="subject_code" data-width="10%">@lang('laprofile.subject_code')</th>
                                <th data-field="subject_name">@lang('laprofile.subject')</th>
                                <th data-field="title_name" data-formatter="result_formatter" class="td-title-name" data-align="center"></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">@lang('labutton.close')</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL ĐIỀU KIỆN GHI DANH --}}
    <div class="modal fade" id="modal-condition-register">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Hoàn thành hoạt động trước khi ghi danh</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <table class="tDefault table table-bordered" id="table-condition-register">
                                <thead>
                                    <tr>
                                        <th>Yêu cầu</th>
                                        <th style="width: 15%" class="text-center">Trạng thái</th>
                                        <th style="width: 15%" class="text-center">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_condition_register">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                </div>
            </div>
        </div>
    </div>

    @include('frontend.all_course.modal')
    <div class="element_data"
        data-course_type = '{{ $course_type }}'
        data-training_program = '{{ $trainingProgram }}'
        data-url_all_course = "{{ route('frontend.all_course',['type' => $course_type]) }}"
        data-locale = "{{ \App::getLocale() }}"
        data-url_career_roadmap = "{{ route('module.career_roadmap.frontend.get_courses', ['title_id' => $profile_name->title_id ? $profile_name->title_id : 0]) }}"
        data-url_career_roadmap_0 = "{{ route('module.career_roadmap.frontend.get_courses', [0]) }}"
        data-url_career_roadmap_tree_folder = "{{ route('module.career_roadmap.frontend.tree_folder.get_child') }}"
        data-edit = "{{ trans('app.edit') }}"
        data-delete = "{{ trans('career.delete') }}"
        data-view = "{{ trans('career.view') }}"
        data-ajax_course_training_program = "{{ route('frontend.ajax_course_training_program') }}"
        data-not_found = "@lang('laother.not_found')"
        data-url_ajax_bonus_course = "{{ route('frontend.ajax_bonus_course') }}"
        data-url_promotion_get_setting = "{{ route('module.promotion.get_setting', ['courseId' => ':id', 'course_type' => 1, 'code' => 'landmarks']) }}"
        data-url_online_share_course = "{{ route('module.online.detail.share_course', ['id' => ':id', 'type' => 1]) }}"
        data-url_offline_share_course = "{{ route('module.offline.detail.share_course', ['id' => ':id', 'type' => 2]) }}"
        data-url_online_detail = "{{ route('module.online.detail_online', ['id' => ':id']).'?share_key=' }}"
        data-url_offline_detail = "{{ route('module.offline.detail', ['id' => ':id']).'?share_key=' }}"
        data-url_ajax_content_course = "{{ route('frontend.ajax_content_course') }}"
        data-url_ajax_summary_course = "{{ route('frontend.ajax_summary_course') }}"
        data-note_course_register_expired = "{{ trans('laother.note_course_register_expired') }}"
        data-expired_registration = "{{ trans('laother.expired_registration') }}"
        data-note_course_finished = "{{ trans('laother.note_course_finished') }}"
        data-course_end = "{{ trans('laother.course_end') }}"
        data-note_course_pending_approved = "{{ trans('laother.note_course_pending_approved') }}"
        data-course_pending_approved = "{{ trans('laother.course_pending_approved') }}"
        data-url_ajax_object_course = "{{ route('frontend.ajax_object_course') }}"
        data-note_user_want_register = "{{ trans('laother.note_user_want_register') }}?"
        data-url_online_register_course = "{{ route('module.online.register_course', ['id' => ':id']) }}"
        data-url_offline_register_course = "{{ route('module.offline.register_course', ['id' => ':id']) }}"
        data-check_night_mode = "{{ (session()->exists('nightMode') && session()->get('nightMode') == 1) ? 1 : 0 }}"
        data-completed = '{{ trans("latraining.completed") }}'
        data-color = '{{ $get_color ? $get_color->value : "#1b4486" }}'
        data-get_hover_color = '{{ $get_hover_color ? $get_hover_color->value : "#1b4486" }}'
        data-url_remove_bookmark = "{{ route('frontend.home.remove_course_bookmark', ['course_id' => ':id', 'course_type' => ':type', 'my_course' => 0]) }}"
        data-url_save_bookmark = "{{ route('frontend.home.save_course_bookmark', ['course_id' => ':id', 'course_type' => ':type', 'my_course' => 0]) }}"
        data-unbookmark = "{{ trans('app.unbookmark') }}"
        data-bookmark = "{{ trans('app.bookmark') }}"
        data-url_condition_register = "{{ route('frontend.ajax_modal_condition_register') }}"
        data-url_register_quiz = "{{ route('frontend.ajax_register_quiz') }}"
        data-list_type = "{{ $listType }}"
    >
    </div>

    <script src="{{ mix('js/allCourse.js') }}" type="text/javascript"></script>
@stop
