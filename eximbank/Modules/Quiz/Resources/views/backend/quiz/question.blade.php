@extends('layouts.backend')

@section('page_title', trans('latraining.question'))

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
                    'name' => trans('latraining.question'),
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
                    'name' => trans('latraining.question'),
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
                    'name' => trans('latraining.question'),
                    'url' => ''
                ],
            ];
        }
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('styles/module/quiz/css/question.css') }}">
    <style>
        a.disabled {
            pointer-events: none;
            cursor: default;
        }
    </style>
@endsection

@section('content')
    <div class="main" id="quiz-question">
        <form method="post" action="" class="form-validate form-ajax" id="form-question" role="form"
              enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8">
                    <a href="{{ route('module.quiz.question.review_quiz', ['id' => $quiz->id]) }}" class="btn"> <i
                            class="fa fa-eye"></i> {{ trans('labutton.review_quiz') }}</a>
                    <a href="javascript:void(0)" class="btn" id="update-template-quiz"> <i class="fa fa-exchange"></i>
                        Cập nhật đề thi</a>
                </div>
                <div class="col-md-4 text-right">
                    <div class="dropdown">
                        <button type="button" class="btn dropdown-toggle" {{ $disabled }} data-toggle="dropdown"><i
                                class="fa fa-plus-circle"></i> {{trans('labutton.add_new')}} </button>
                        <div class="dropdown-menu">
                            <a href="javascript:void(0)" class="dropdown-item load-modal"
                               data-url="{{ route('module.quiz.question.get_modal_quiz_question', ['id' => $quiz->id]) }}">{{trans('labutton.add_questionlib')}}</a>
                            <a href="javascript:void(0)" class="dropdown-item load-modal"
                               data-url="{{ route('module.quiz.question.get_modal_question_category', ['id' => $quiz->id]) }}">{{trans('labutton.add_random_question')}}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <ul class="list-quiz-question" @if(!$disabled) id="sortable" @endif>
                        @php
                            $page = 1;
                            $index = 1;
                            $qqcategory = 0;
                        @endphp

                        @foreach($quiz_questions as $quiz_question)
                            @php
                                $category_random = $categories($quiz_question->qcategory_id);
                                $question = $questions($quiz_question->question_id);
                                $category = $categories($question->category_id);
                                $qcat = $qqc($quiz->id, $index-1);
                            @endphp

                            @if ($index % $quiz->questions_perpage == 1)
                                <li class="page-name" data-index="{{ $index-1 }}"> Trang {{ $page }} </li>
                            @endif

                            @if($qcat)
                                <li class="item-quiz-question category-question">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <span class="custom-question"> {!! $qcat->name !!}</span>
                                        </div>
                                        <div class="col-md-2 text-right">
                                            <span><b>{{ $qcat->percent_group }} %</b></span>
                                            <a href="javascript:void(0)" class="edit-category {{ $disabled }}"
                                               tabindex="-1" data-category="{{ $qcat->id }}"
                                               data-num_order="{{ $quiz_question->num_order }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="remove-category {{ $disabled }}"
                                               data-category="{{ $qcat->id }}">
                                                <i class="fa fa-trash text-danger"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <input type="hidden" name="question[]" value="c_{{ $qcat->id }}">
                                </li>
                            @endif

                            <li class="item-quiz-question">
                                <div class="row">
                                    <div class="col-md-10">
                                        <a type="button"
                                           @if(!$quiz_question->random) onclick="editQuestionHandle({{ $question['id'] }}, {{ $question['category_id'] }})" @endif ><i
                                                class="fa fa-cog" style=" font-size: 18px;"></i></a>
                                        <i class="{{($quiz_question->difficulty ?  ' - ' .( $quiz_question->difficulty == 'D' ? 'far fa-smile' : ($quiz_question->difficulty == 'TB' ? 'far fa-meh' : 'far fa-frown' ))  : 'fa fa-random')}}"
                                           style="font-size: 18px;"></i>
                                        <input type="hidden" name="question[]" value="{{ $quiz_question->id }}">
                                        <input class="custom-num-order" type="text" data-ques="{{ $quiz_question->id }}"
                                               value="{{ $quiz_question->num_order }}" readonly>
                                        <div class="wrapped_text_question d-inline"
                                        >
                                        <span class="custom-question">
                                            {!! $quiz_question->random ? trans('latraining.random') . ($quiz_question->difficulty ?  ' - ' .( $quiz_question->difficulty == 'D' ? 'Dễ' : ($quiz_question->difficulty == 'TB' ? 'Trung bình' : 'Khó' ))  : '') : strip_tags(trim(html_entity_decode($question['name'], ENT_QUOTES, 'UTF-8'), "\xc2\xa0"))  !!}
                                        </span>
                                            @if ($quiz_question->random)
                                                <span><strong>( {{ strip_tags(trim(html_entity_decode($category_random['name'], ENT_QUOTES, 'UTF-8'), "\xc2\xa0")) }} )</strong></span>
                                            @else
                                                <span><strong>( {{ strip_tags(trim(html_entity_decode($category['name'], ENT_QUOTES, 'UTF-8'), "\xc2\xa0")) }} )</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <a href="javascript:void(0)" class="add-qqcategory {{ $disabled }}"
                                           data-num_order="{{ $quiz_question->num_order }}">
                                            <i class="fa fa-plus"></i>
                                        </a>
                                        <input class="custom-score" {{ $disabled }} type="text"
                                               data-ques="{{ $quiz_question->id }}"
                                               value="{{ $quiz_question->max_score }}">
                                        <a href="javascript:void(0)" class="remove-question {{ $disabled }}"
                                           data-ques="{{ $quiz_question->id }}">
                                            <i class="fa fa-trash text-danger"></i>
                                        </a>
                                    </div>
                                </div>
                            </li>

                            @php
                                if ($index % $quiz->questions_perpage == 0) { $page ++; }
                                $index += 1;
                            @endphp
                        @endforeach
                    </ul>
                </div>
            </div>
        </form>

        <script type="text/javascript">
            $(function () {
                $("#sortable").sortable({
                    update: function (event, ui) {
                        $.each($(".custom-num-order"), function (index, item) {
                            $(this).val(index + 1);
                        });

                        update_question();
                    }
                });

                $("#sortable").disableSelection();

                $("#sortable .page-name").bind('click.sortable mousedown.sortable', function (e) {
                    e.stopImmediatePropagation();
                });

                $(".remove-question").on('click', function () {
                    let item = $(this);
                    let quiz_ques_id = item.data('ques');
                    item.find('i').attr('class', 'fa fa-spinner fa-spin text-danger');

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('module.quiz.question.remove_quiz_question', ['id' => $quiz->id]) }}",
                        data: {
                            quiz_ques_id: quiz_ques_id,
                        },
                    }).done(function (data) {
                        item.closest('.item-quiz-question').remove();
                        update_question();
                        return false;
                    }).fail(function (data) {
                        show_message('Không thể xóa câu hỏi', 'error');
                        return false;
                    });
                });

                $(".custom-score").on('change', function () {
                    var max_score = $(this).closest('.item-quiz-question').find('.custom-score').val();
                    var quiz_ques_id = $(this).data('ques');

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('module.quiz.question.update_max_score', ['id' => $quiz->id]) }}",
                        data: {
                            quiz_ques_id: quiz_ques_id,
                            max_score: max_score,
                        },
                    }).done(function (data) {
                        update_question();
                        return false;
                    }).fail(function (data) {
                        return false;
                    });
                });

                $("#sortable").on('click', '.add-qqcategory', function () {
                    let item = $(this);
                    let num_order = item.data('num_order');
                    let icon = item.find('i').attr('class');
                    item.find('i').attr('class', 'fa fa-spinner fa-spin');

                    $.ajax({
                        type: 'POST',
                        url: '{{ route('module.quiz.question.modal_qqcategory', ['id' => $quiz->id]) }}',
                        dataType: 'html',
                        data: {
                            'num_order': num_order
                        }
                    }).done(function (data) {

                        item.find('i').attr('class', icon);
                        $("#app-modal").html(data);
                        $("#app-modal #myModal").modal();

                        return false;
                    }).fail(function (data) {

                        return false;
                    });
                });

                $("#sortable").on('click', '.edit-category', function () {
                    let item = $(this);
                    let num_order = item.data('num_order');
                    let icon = item.find('i').attr('class');
                    let category_id = item.data('category');

                    item.find('i').attr('class', 'fa fa-spinner fa-spin');

                    $.ajax({
                        type: 'POST',
                        url: '{{ route('module.quiz.question.modal_qqcategory', ['id' => $quiz->id]) }}',
                        dataType: 'html',
                        data: {
                            'num_order': num_order,
                            'category_id': category_id
                        }
                    }).done(function (data) {
                        item.find('i').attr('class', icon);
                        $("#app-modal").html(data);
                        $("#app-modal #myModal").modal();
                        return false;
                    }).fail(function (data) {
                        return false;
                    });
                });

                $("#sortable").on('click', '.remove-category', function () {
                    let item = $(this);
                    let num_order = item.data('num_order');
                    let category_id = item.data('category');

                    item.find('i').attr('class', 'fa fa-spinner fa-spin text-danger');

                    $.ajax({
                        type: 'POST',
                        url: '{{ route('module.quiz.question.remove_qqcategory', ['id' => $quiz->id]) }}',
                        dataType: 'html',
                        data: {
                            'num_order': num_order,
                            'category_id': category_id
                        }
                    }).done(function (data) {
                        item.closest('.item-quiz-question').remove();
                        update_question();
                        return false;
                    }).fail(function (data) {
                        return false;
                    });
                });

                function update_question() {
                    let qcount = $("input[name='question[]']").length;
                    if (qcount <= 0) {
                        return false;
                    }

                    $.ajax({
                        type: 'POST',
                        url: '{{ route('module.quiz.question.update_num_order', ['id' => $quiz->id]) }}',
                        dataType: 'json',
                        data: $("#form-question").serialize(),
                    }).done(function (data) {
                        if (data.status !== "success") {
                            show_message('Không thể cập nhật thứ tự câu hỏi', 'error');
                            return false;
                        }
                        return false;
                    }).fail(function (data) {
                        return false;
                    });
                }
            })

            $('#update-template-quiz').on('click', function () {
                let item = $(this);
                let icon = item.find('i').attr('class');

                item.find('i').attr('class', 'fa fa-spinner fa-spin');

                $.ajax({
                    type: 'POST',
                    url: '{{ route('module.quiz.question.update_template_quiz', ['id' => $quiz->id]) }}',
                    dataType: 'html',
                    data: {}
                }).done(function (data) {
                    item.find('i').attr('class', icon);

                    show_message('Đã cập nhật bộ đề');

                    return false;
                }).fail(function (data) {
                    return false;
                });

            });

            function editQuestionHandle(id, cateId) {
                var url = '{{ route("module.quiz.questionlib.question.edit", ["id" => ":id", "qid" => ":qid"]) }}';
                url = url.replace(':id', cateId);
                url = url.replace(':qid', id);
                window.location.href = url;
            }
        </script>
    </div>

@stop
