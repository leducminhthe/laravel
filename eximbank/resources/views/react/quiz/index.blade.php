@extends('react.layouts.app')
@section('page_title', trans('laquiz.quiz'))

@section('content')
    <div id="languages"
        data-enter_quiz="{{ trans('laquiz.enter_quiz') }}"
        data-start_date="{{ trans('laother.start_date') }}"
        data-end_date="{{ trans('laother.end_date') }}"
        data-choose_type_quiz="{{ trans('laquiz.choose_type_quiz') }}"
        data-start="{{ trans('laquiz.start') }}"
        data-end="{{ trans('laquiz.end') }}"
        data-status="{{ trans('laquiz.status') }}"
        data-student="{{ trans('laquiz.student') }}"
        data-home_page="{{ trans('lamenu.home_page') }}"
    >
    </div>
    <div id="react" class="sa4d25">
                    
    </div>
@endsection
