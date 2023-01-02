@extends('react.layouts.app')
@section('page_title', trans('lasurvey.survey'))

@section('content')
    <div id="languages"
        data-survey="{{ trans('lasurvey.survey') }}"
        data-do_not="{{ trans('lasurvey.do_not') }}"
        data-did="{{ trans('lasurvey.did') }}"
        data-completed="{{ trans('lasurvey.completed') }}"
        data-done="{{ trans('lasurvey.done') }}"
        data-student="{{ trans('lasurvey.student') }}"
        data-time="{{ trans('lasurvey.time') }}"
        data-to="{{ trans('lasurvey.to') }}"
        data-take_survey="{{ trans('lasurvey.take_survey') }}"
        data-survey_end="{{ trans('lasurvey.survey_end') }}"
        data-view_survey="{{ trans('lasurvey.view_survey') }}"
        data-survey_not_start_yet="{{ trans('lasurvey.survey_not_start_yet') }}"
        data-edit_survey="{{ trans('lasurvey.edit_survey') }}"
        data-start_date="{{ trans('lasurvey.start_date') }}"
        data-end_date="{{ trans('lasurvey.end_date') }}"
        data-status="{{ trans('lasurvey.status') }}"
        data-content="{{ trans('lasurvey.content') }}"
        data-choose_answer="{{ trans('lasurvey.choose_answer') }}"
        data-other_suggest="{{ trans('lasurvey.another_suggestion') }}"
        data-save="{{ trans('labutton.save') }}"
        data-send="{{ trans('labutton.send') }}"
        data-close="{{ trans('labutton.close') }}"
        data-unit="{{ trans('latraining.unit') }}"
        data-title="{{ trans('latraining.title') }}"
        data-home_page="{{ trans('lamenu.home_page') }}"
        data-user_code="{{ trans('laprofile.employee_code') }}"
        data-welcome_to_survey="{{ trans('lasurvey.welcome_to_survey') }}"
    >
    </div>
    <div id="survey" class="sa4d25">

    </div>
@endsection
