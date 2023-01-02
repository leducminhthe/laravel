@extends('layouts.backend')

@section('page_title', $quiz->name)

@section('breadcrumb')
    @php
        if($quiz->course_type == 1){
            $route_edit = route('module.online.edit', ['id' => $course_id]);
            $route_quiz = route('module.online.quiz', ['course_id' => $course_id]);

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
                    'name' => $course->name,
                    'url' => $route_edit
                ],
                [
                    'name' => trans('latraining.quiz_list'),
                    'url' => $route_quiz
                ],
                [
                    'name' => $quiz->name,
                    'url' => route('module.online.quiz.edit', ['course_id' => $course_id, 'id' => $quiz->id])
                ],
                [
                    'name' => trans('latraining.result'),
                    'url' => route('module.quiz.result', ['id' => $quiz->id])
                ],
                [
                    'name' => trans('laquiz.exam_test') . ' ' . $full_name,
                    'url' => ''
                ],
            ];
        }elseif($quiz->course_type == 2){
            $route_edit = route('module.offline.edit', ['id' => $course_id]);
            $route_quiz = route('module.offline.quiz', ['course_id' => $course_id]);

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
                    'name' => $course->name,
                    'url' => $route_edit
                ],
                [
                    'name' => trans('latraining.quiz_list'),
                    'url' => $route_quiz
                ],
                [
                    'name' => $quiz->name,
                    'url' => route('module.offline.quiz.edit', ['course_id' => $course_id, 'id' => $quiz->id])
                ],
                [
                    'name' => trans('latraining.result'),
                    'url' => route('module.quiz.result', ['id' => $quiz->id])
                ],
                [
                    'name' => trans('laquiz.exam_test') . ' ' . $full_name,
                    'url' => ''
                ],
            ];
        }else{
            $breadcum= [
                [
                    'name' => trans('latraining.quiz_list'),
                    'url' => route('module.quiz.manager')
                ],
                [
                    'name' => $quiz->name,
                    'url' => route('module.quiz.edit', ['id' => $quiz->id])
                ],
                [
                    'name' => trans('latraining.result'),
                    'url' => route('module.quiz.result', ['id' => $quiz->id])
                ],
                [
                    'name' => trans('laquiz.exam_test') . ' ' . $full_name,
                    'url' => ''
                ],
            ];
        }
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <link rel="stylesheet" href="{{ asset('styles/module/quiz/css/doquiz.css') }}">

    <style>
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <div class="row pb-2">
        <div class="col-12">
            Số lần làm bài:
            @foreach ($list_attempt as $item)
                <span class="">
                    <a href="{{ route('module.quiz.result.user.view', ['id' => $quiz->id, 'part_id' => $item->part_id, 'type' => $type, 'user_id' => $user_id, 'attempt_id' => $item->id]) }}" class="btn text-white {{ $attempt->id == $item->id ? 'bg-active' : '' }} ">
                        {{ $item->attempt }}
                    </a>
                </span>
            @endforeach
        </div>
    </div>
    <div class="row" id="quiz-content">
        <div class="col-md-3">
            @include('quiz::backend.result.component.sidebar')
        </div>
        <div class="col-md-9 quiz-{{ $quiz->id }}">

            <form method="post" action="" id="form-question">
                <div class="card">
                    <div class="card-header">
                        <div class="text-center mb-1 button-page">
                            <button type="button" class="btn button-back"><i class="fa fa-mail-reply"></i> {{ trans('backend.back') }}</button> |
                            <button type="button" class="btn button-next">{{ trans('backend.next') }} <i class="fa fa-mail-forward"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="questions"></div>
                    </div>
                    <div class="card-footer">
                        <div class="text-center mt-1 button-page">
                            <button type="button" class="btn button-back"><i class="fa fa-mail-reply"></i> {{ trans('backend.back') }}</button> |
                            <button type="button" class="btn button-next">{{ trans('backend.next') }} <i class="fa fa-mail-forward"></i></button>
                        </div>
                    </div>
                    <div id="loading"></div>
                </div>
            </form>
        </div>
    </div>

    <template id="question-template">
        <div class="question-item" id="q{qid}" data-qid="{qid}">
            <input type="hidden" name="q[]" value="{qid}">
            <div class="row">
                <div class="col-md-2">
                    <div class="info">
                        <h3 class="no">{{ trans('latraining.question') }} <span class="qno">{index}</span></h3>
                        <div class="grade">{{ trans('backend.score') }}: {max_score}</div>
                        <div class="questionflag editable"></div>
                    </div>
                </div>

                <div class="col-md-10">
                    <div class="content">
                        <div class="formulation clearfix">
                            <div class="qtext">
                                <b><span lang="DE">{name}</span></b>

                                <div id="image-area w-100" style="position: relative;">
                                    {image_drag_drop}
                                    {drop_image}
                                </div>
                            </div>
                            <div class="ablock">
                                <div class="prompt">{prompt}</div>
                                <div class="answer">
                                    {answers}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="answer-template-chosen">
        <div class="r{index}">
            <label for="q{qindex}:choice{index}" class="ml-1 d-flex align-items-center">
                {correct} <input type="{input_type}" name="q_{qid}[]" value="{id}" id="q{qindex}:choice{index}" class="selected-answer mr-2" data-answer="{id}" {checked} @if($disabled) disabled @endif>
                {{--  <span class="answernumber">{index_text}. </span>  --}}
                {title}
                {image_answer}
            </label>
            {feedback}
        </div>
    </template>

    <template id="correct-answer-template-chosen">
        <p></p>
        <div class="card">
            <div class="card-header bg-info text-white">
                Câu trả lời đúng
            </div>
            <div class="card-body">
                {correct_answer}
            </div>
        </div>
    </template>

    <template id="answer-template-essay">
        <textarea class="form-control selected-answer" name="q_{qid}[]" rows="5" data-answer="{id}" @if($disabled) disabled @endif>{text_essay}</textarea>
        <div class="form-grading mt-1">
            <div class="row form-comment d-none">
                <div class="col-md-12">
                    <textarea name="comment_q{qid}" class="form-control change-comment" placeholder="Đánh giá câu trả lời" data-id="{qid}">{grading_comment}</textarea>
                </div>
            </div>
        </div>
    </template>

    <template id="answer-template-matching">
        <div class="r{index}">
            <input type="hidden" name="q_{qid}[]" value="{id}">
            <label class="m-l-1">
                <span class="answernumber">{index_text}. </span>
                <span lang="VN">{title}</span>
            </label>
            <select name="matching_{qid}[{id}]" class="selected-answer" data-answer="{id}" @if($disabled) disabled @endif>
                <option value="{matching}">{matching}</option>
            </select>
            {correct}
        </div>
    </template>

    <template id="matching-feedback-template">
        <p></p>
        {feedback}
        <p></p>
        <div class="card">
            <div class="card-header bg-info text-white">
                Câu trả lời đúng
            </div>
            <textarea type="text" class="form-control" @if($disabled) disabled @endif>{correct_answer}</textarea>
        </div>
    </template>

    <template id="qqcategory-template">
        <h3 class="question-title">
            <div class="row">
                <div class="col-md-10">
                    {name}
                </div>
                <div class="col-md-2 text-right">
                    {percent} %
                </div>
            </div>
        </h3>
    </template>

    <template id="fill-in-template">
        <div class="r{index}">
            <label for="q{qindex}:choice{index}" class="m-l-1">
                <span class="answernumber">{index_text}. </span>
                <span lang="VN">{title}</span>
            </label>
            <textarea class="form-control selected-answer" name="q_{qid}[]" rows="5" id="q{qindex}:choice{index}" data-answer="{id}" @if($disabled) disabled @endif>{text_essay}</textarea>
            {feedback}
        </div>
    </template>

    <template id="fill-in-correct-template">
        <div class="r{index}">
            <label for="q{qindex}:choice{index}" class="m-l-1">
                <span class="answernumber">{index_text}. </span>
                <span lang="VN">{title}</span>
            </label>
            <textarea class="form-control selected-answer" name="q_{qid}[]" rows="5" id="q{qindex}:choice{index}" data-answer="{id}" @if($disabled) disabled @endif>{text_essay}</textarea>
        </div>
    </template>

    <template id="select_word_correct">
        <div class="r{index}">
            <div class="qtext">
                {name}
            </div>
        </div>
    </template>

    <template id="drag_drop_marker">
        {name}
    </template>

    <template id="drag_drop_image">
        {name}
    </template>

    <template id="drag_drop_document">
        {name}
    </template>

    <script type="text/javascript">
        var quiz_id = '{{ $quiz->id }}';
        var quiz_url = '{{ route('module.quiz.result.user.question', [
            'id' => $quiz->id,
            'type' => $type,
            'user_id' => $user_id,
            'attempt_id'=> $attempt->id
        ]) }}';
        var qqcategory = jQuery.parseJSON('{!! json_encode($qqcategory) !!}');
        var questions_perpage = '{{ $quiz->questions_perpage }}';
    </script>
    <script type="text/javascript" src="{{ asset('styles/module/quiz/js/view_quiz.js') }}"></script>
@stop
