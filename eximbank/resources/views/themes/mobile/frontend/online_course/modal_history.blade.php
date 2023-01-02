@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.onl_course'))

@section('content')
    <div class="container mt-2" id="detail-online">
        @foreach ($get_activity_courses as $key_history => $get_activity_quiz_scorm)
            <div class="row m-0 py-2">
                <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.online.modal_history_detail_course',[$course_id, $get_activity_quiz_scorm->id]) }}', 1, 1)" class="d_flex_align w-100">
                    <div class="col-10 p-0 d_flex_align">
                        <h6 class="">{{ $get_activity_quiz_scorm->name }}</h6>
                    </div>
                    <div class="col-2 text-right pr-0">
                        <i class="material-icons">navigate_next</i>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endsection
