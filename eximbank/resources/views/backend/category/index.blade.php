@extends('layouts.backend')

@section('page_title', trans('lamenu.category'))

@section('content')

    <div class="row mb-4">
        @canany(['category-unit','category-unit-type','category-titles','category-cert'])
            {{-- TỔ CHỨC --}}
            <div class="col-3 mb-4">
                <div class="wrapped_category">
                    <div class="title mb-3">
                        <h4 class="font-weight-bold">{{ mb_strtoupper(trans('lacategory.organize')) }}</h4>
                    </div>
                    @can('category-unit')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.unit', ['level' => 0]) }}">{{ trans('lacategory.company_categories') }}</a>
                        </div>
                        @for($i = 1; $i <= 6; $i++)
                            <div class="item_name mb-2">
                                <a href="{{ route('backend.category.unit', ['level' => $i]) }}">{{ trans('lacategory.unit_level', ['i' => $i]) }}</a>
                            </div>
                        @endfor
                    @endcan
                </div>
            </div>
            {{-- VỊ TRÍ ĐỊA LÝ --}}
            <div class="col-3 mb-4">
                <div class="wrapped_category">
                    <div class="title mb-3">
                        <h4 class="font-weight-bold">{{ mb_strtoupper(trans('lacategory.geographical_location')) }}</h4>
                    </div>
                    @can('category-area')
                        @for($i = 1; $i <= $max_level_area; $i++)
                            <div class="item_name mb-2">
                                <a href="{{ route('backend.category.area', ['level' => $i]) }}">{{ $level_name_area($i) }}</a>
                            </div>
                        @endfor
                    @endcan
                </div>
            </div>
            {{-- THÔNG TIN --}}
            <div class="col-3 mb-4">
                <div class="wrapped_category">
                    <div class="title mb-3">
                        <h4 class="font-weight-bold">{{ mb_strtoupper(trans('lacategory.info')) }}</h4>
                    </div>
                    @can('category-unit-type')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.unit_type') }}">{{ trans('lacategory.unit_type') }}</a>
                        </div>
                    @endcan
                    @can('category-titles')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.title_rank') }}">{{ trans('lacategory.title_level') }}</a>
                        </div>
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.titles') }}">{{ trans('lacategory.title') }}</a>
                        </div>
                    @endcan
                    @can('category-cert')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.cert') }}">{{ trans('lacategory.level') }}</a>
                        </div>
                    @endcan
                    @can('category-titles')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.position') }}">{{ trans('lacategory.position') }}</a>
                        </div>
                    @endcan
                </div>
            </div>
        @endcanany
        @canany(['category-training-program', 'category-subject','category-training-location','category-training-form','category-quiz-type'])
            {{-- ĐÀO TẠO --}}
            <div class="col-3 mb-4">
                <div class="wrapped_category">
                    <div class="title mb-3">
                        <h4 class="font-weight-bold">{{ mb_strtoupper(trans('lacategory.training')) }}</h4>
                    </div>
                    @can('category-training-program')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.training_program') }}">{{ trans('lacategory.training_program') }}</a>
                        </div>
                    @endcan
                    @can('category-level-subject')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.level_subject') }}">{{ trans('lacategory.type_subject') }}</a>
                        </div>
                    @endcan
                    @can('category-subject')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.subject') }}">{{ trans('backend.subject') }}</a>
                        </div>
                    @endcan
                    @can('category-training-form')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.training_form') }}">{{ trans('lacategory.training_form') }}</a>
                        </div>
                    @endcan
                    @can('category-training-type')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.training-type') }}">{{ trans('lacategory.training_type') }}</a>
                        </div>
                    @endcan
                    @can('category-training-form')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.training-object') }}">{{ trans('lacategory.training_object_group') }}</a>
                        </div>
                    @endcan
                    @can('category-quiz-type')
                        <div class="item_name mb-2">
                            <a href="{{ route('module.quiz.type.manager') }}">{{ trans('lacategory.quiz_type') }}</a>
                        </div>
                    @endcan
                </div>
            </div>
        @endcanany
        @canany(['category-absent', 'category-discipline', 'category-absent-reason'])
            {{-- KỶ LUẬT --}}
            <div class="col-3 mb-4">
                <div class="wrapped_category">
                    <div class="title mb-3">
                        <h4 class="font-weight-bold">{{ mb_strtoupper(trans('lacategory.discipline')) }}</h4>
                    </div>
                    @can('category-absent')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.absent') }}">{{ trans('lacategory.absent_type') }}</a>
                        </div>
                    @endcan
                    @can('category-discipline')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.discipline') }}">{{ trans('lacategory.violator_list') }}</a>
                        </div>
                    @endcan
                    @can('category-absent-reason')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.absent-reason') }}">{{ trans('lacategory.absent_reason') }}</a>
                        </div>
                    @endcan
                </div>
            </div>
        @endcanany
        @canany(['category-training-cost','category-student-cost','commit-month'])
            {{-- CHI PHÍ --}}
            <div class="col-3 mb-4">
                <div class="wrapped_category">
                    <div class="title mb-3">
                        <h4 class="font-weight-bold">{{ mb_strtoupper(trans('lacategory.cost')) }}</h4>
                    </div>
                    @can('category-training-cost')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.type_cost') }}">{{ trans('lacategory.fee_type') }}</a>
                        </div>
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.training_cost') }}">{{ trans('lacategory.training_cost') }}</a>
                        </div>
                    @endcan
                    @can('category-student-cost')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.student_cost') }}">{{ trans('lacategory.student_cost') }}</a>
                        </div>
                    @endcan
                    @can('commit-month')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.commit_month') }}">{{ trans('lacategory.commit') }}</a>
                        </div>
                    @endcan
                </div>
            </div>
        @endcanany
        @canany(['category-teacher','category-teacher-type','category-partner','coaching-group'])
            {{-- GIẢNG VIÊN --}}
            <div class="col-3 mb-4">
                <div class="wrapped_category">
                    <div class="title mb-3">
                        <h4 class="font-weight-bold">{{ mb_strtoupper(trans('lacategory.teacher')) }}</h4>
                    </div>
                    @can('category-partner')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.training_partner') }}">{{ trans('lacategory.partner') }}</a>
                        </div>
                    @endcan
                    @can('category-teacher-type')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.teacher_type') }}">{{ trans('lacategory.teacher_type') }}</a>
                        </div>
                    @endcan
                    @can('category-teacher')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.training_teacher') }}">{{ trans('lacategory.list_teacher') }}</a>
                        </div>
                    @endcan
                    {{-- @can('coaching-group')
                        <div class="item_name mb-2">
                            <a href="{{ route('module.coaching_group') }}">{{ trans('lamenu.coaching_group') }}</a>
                        </div>
                    @endcan
                    @can('coaching-mentor-method')
                        <div class="item_name mb-2">
                            <a href="{{ route('module.coaching_mentor_method') }}">{{ trans('lamenu.coaching_mentor_method') }}</a>
                        </div>
                    @endcan --}}
                </div>
            </div>
        @endcanany
        @canany(['category-province','category-district','category-training-location'])
            {{-- ĐỊA ĐIỂM ĐÀO TẠO --}}
            <div class="col-3 mb-4">
                <div class="wrapped_category">
                    <div class="title mb-3">
                        <h4 class="font-weight-bold">{{ mb_strtoupper(trans('lacategory.training_location')) }}</h4>
                    </div>
                    @can('category-province')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.province') }}">{{ trans('lacategory.province') }}</a>
                        </div>
                    @endcan
                    @can('category-district')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.district') }}">{{ trans('lacategory.district') }}</a>
                        </div>
                    @endcan
                    @can('category-training-location')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.training_location') }}">{{ trans('lacategory.training_location') }}</a>
                        </div>
                    @endcan
                </div>
            </div>
        @endcanany

            {{-- ĐIỂM THƯỞNG --}}
            <div class="col-3 mb-4">
                <div class="wrapped_category">
                    <div class="title mb-3">
                        <h4 class="font-weight-bold">{{ trans('lacategory.reward_points') }}</h4>
                    </div>
                    @can('category-userpoint-item')
                        <div class="item_name mb-2">
                            <a href="{{ route('module.userpoint.manager',["id"=>2]) }}">{{ trans('lacategory.onl_course') }}</a>
                        </div>
                    @endcan
                    @can('category-userpoint-item')
                        <div class="item_name mb-2">
                            <a href="{{ route('module.userpoint.manager',["id"=>3]) }}">{{ trans('lacategory.off_course') }}</a>
                        </div>
                    @endcan
                    @can('category-userpoint-item')
                        <div class="item_name mb-2">
                            <a href="{{ route('module.userpoint.manager',["id"=>4]) }}">{{ trans('lacategory.quiz') }}</a>
                        </div>
                    @endcan
                    @can('category-userpoint-item')
                        <div class="item_name mb-2">
                            <a href="{{ route('module.userpoint.manager',["id"=>6]) }}">{{ trans('lacategory.library') }}</a>
                        </div>
                    @endcan
                    @can('category-userpoint-item')
                        <div class="item_name mb-2">
                            <a href="{{ route('module.userpoint.manager',["id"=>7]) }}">{{ trans('lacategory.forum') }}</a>
                        </div>
                    @endcan
                    @can('category-userpoint-item')
                        <div class="item_name mb-2">
                            <a href="{{ route('module.userpoint.manager',["id"=>8]) }}">{{ trans('lamenu.training_video') }}</a>
                        </div>
                    @endcan
                    @can('category-userpoint-item')
                        <div class="item_name mb-2">
                            <a href="{{ route('module.userpoint.manager',["id"=>9]) }}">{{ trans('lamenu.news') }}</a>
                        </div>
                    @endcan
                    @can('category-userpoint-item')
                        <div class="item_name mb-2">
                            <a href="{{ route('module.userpoint.manager',["id"=>10]) }}">{{ trans('latraining.other') }}</a>
                        </div>
                    @endcan
                    {{-- @can('category-userpoint-item')
                        <div class="item_name mb-2">
                            <a href="{{ route('module.userpoint.manager',["id"=>11]) }}">Coaching</a>
                        </div>
                    @endcan --}}
                </div>
            </div>

            <div class="col-3 mb-4">
                <div class="wrapped_category">
                    <div class="title mb-3">
                        <h4 class="font-weight-bold">{{ trans('lacategory.competition_program') }}</h4>
                    </div>
                    @can('category-province')
                        <div class="item_name mb-2">
                            <a href="{{ route('module.usermedal.list') }}">{{ trans('lamenu.emulation_program') }}</a>
                        </div>
                    @endcan
                </div>
            </div>

            {{--  Khung năng lực  --}}
            <div class="col-3 mb-4">
                <div class="wrapped_category">
                    <div class="title mb-3">
                        <h4 class="font-weight-bold">Khung năng lực</h4>
                    </div>
                    <div class="item_name mb-2">
                        <a href="{{ route('module.capabilities.category') }}">Khung năng lực (A)</a>
                    </div>
                    <div class="item_name mb-2">
                        <a href="{{ route('module.capabilities') }}">Năng lực chuyên môn (C)</a>
                    </div>
                    <div class="item_name mb-2">
                        <a href="{{ route('module.capabilities.group_percent') }}">Nhóm phần trăm</a>
                    </div>
                    <div class="item_name mb-2">
                        <a href="{{ route('module.capabilities.group') }}">Phân Nhóm năng lực (ASK)</a>
                    </div>
                    <div class="item_name mb-2">
                        <a href="{{ route('module.capabilities.title') }}">Khung năng lực theo chức danh</a>
                    </div>
                </div>
            </div>
    </div>
@endsection
