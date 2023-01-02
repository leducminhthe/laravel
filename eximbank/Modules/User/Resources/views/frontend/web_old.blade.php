<a class="nav-item nav-link nav_menu_user @if ($tabs == 'info')
    active
    @endif" id="nav-about-tab" href="{{ route('module.frontend.user.info') }}" >@lang('laprofile.info')
</a>

<a class="nav-item nav-link nav_menu_user @if ($tabs == 'roadmap')
    active
    @endif" id="nav-reviews-tab" href="{{ route('module.frontend.user.roadmap') }}" >@lang('laprofile.roadmap')
</a>

<a class="nav-item nav-link nav_menu_user @if ($tabs == 'training-by-title')
    active
    @endif" id="nav-reviews-tab" href="{{ route('module.frontend.user.training_by_title') }}">{{ trans('laprofile.training_path') }}
</a>

<a class="nav-item nav-link nav_menu_user @if ($tabs == 'trainingprocess')
    active
    @endif" id="nav-courses-tab" href="{{ route('module.frontend.user.trainingprocess') }}">@lang('laprofile.training_process')
</a>

<a class="nav-item nav-link nav_menu_user @if ($tabs == 'quizresult')
    active
    @endif" id="nav-purchased-tab" href="{{ route('module.frontend.user.quizresult') }}">@lang('laprofile.quiz_result')
</a>

<a class="nav-item nav-link nav_menu_user @if ($tabs == 'my-career-roadmap')
    active
    @endif" id="nav-reviews-tab" href="{{ route('module.frontend.user.my_career_roadmap') }}" >@lang('laprofile.career_roadmap')
</a>

<a class="nav-item nav-link nav_menu_user @if ($tabs == 'subjectregister')
    active
    @endif" id="nav-reviews-tab" href="{{ route('module.frontend.user.subjectregister') }}" >@lang('laprofile.subject_registered')</a>

<a class="nav-item nav-link nav_menu_user @if ($tabs == 'student-cost')
    active
@endif" id="nav-reviews-tab" href="{{ route('module.frontend.user.student_cost') }}" >{{ trans('laprofile.student_cost') }}</a>

<a class="nav-item nav-link nav_menu_user @if ($tabs == 'violate-rules')
    active
@endif" id="nav-reviews-tab" href="{{ route('module.frontend.user.violate_rules') }}" >{{ trans('laprofile.violate_rules') }}</a>

<a class="nav-item nav-link nav_menu_user @if ($tabs == 'my-promotion')
    active
@endif" id="nav-reviews-tab" href="{{ route('module.frontend.user.my_promotion') }}" >{{ trans('laprofile.promotion') }}</a>

<a class="nav-item nav-link nav_menu_user @if ($tabs == 'point-hist')
    active
@endif" id="nav-reviews-tab" href="{{ route('module.frontend.user.point_hist') }}" >{{ trans('laprofile.history_point') }}</a>
