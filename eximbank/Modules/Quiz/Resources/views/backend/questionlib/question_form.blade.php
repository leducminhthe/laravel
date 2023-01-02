@extends('layouts.backend')

@section('page_title', trans('latraining.question').': '.$category->name )

@section('header')
    <script src="{{ asset('styles/ckeditor/ckeditor.js') }}"></script>
    <style>
        #area_image_drag_drop .coordinates{
            padding: 5px 20px;
            margin: 5px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }
    </style>
@endsection

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.questionlib'),
                'url' => route('module.quiz.questionlib')
            ],
            [
                'name' => trans('latraining.question').': '. $category->name,
                'url' => route('module.quiz.questionlib.question', ['id'=> $category->id])
            ],
            [
                'name' => $page_title .'...',
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="tPanel">
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    <form action="{{ route('module.quiz.questionlib.save_question', ['id' => $category->id]) }}" method="post" class="form-ajax">
                        <input type="hidden" name="id" value="{{ $model->id }}">
                        <input type="hidden" name="type" value="{{ $model->type }}">
                        <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-4 text-right">
                                <div class="btn-group act-btns">
                                    @canany(['quiz-question-create', 'quiz-question-edit'])
                                    <button type="submit" class="btn" data-must-checked="false">
                                        <i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}
                                    </button>
                                    @endcanany
                                    <a href="{{ route('module.quiz.questionlib.question', ['id'=> $category->id]) }}" class="btn">
                                        <i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-sm-2 control-label">
                                        <label>{{ trans('latraining.question') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" name="code" id="code" class="form-control mb-1" value="{{ $model->code }}" required placeholder="Nhập mã">
                                        <textarea name="name" id="name" type="text" class="form-control">{!! $model->name !!}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2 control-label">
                                        <label>Mức độ <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-10">
                                        <select name="difficulty" id="difficulty" class="form-control select2" data-placeholder="">
                                            <option value="D" {{ $model->difficulty == 'D' ? 'selected' : '' }}>
                                                Dễ
                                            </option>
                                            <option value="TB" {{ $model->difficulty == 'TB' ? 'selected' : '' }}>
                                                Trung bình
                                            </option>
                                            <option value="K" {{ $model->difficulty == 'K' ? 'selected' : '' }}>
                                                Khó
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2 control-label">
                                        <label>{{trans("backend.kind")}} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-10">
                                        <select id="type" class="form-control select2" data-placeholder="Chọn loại câu hỏi" {{ $model->type ? 'disabled' : '' }}>
                                            <option value=""></option>
                                            <option value="essay" {{ $model->type == 'essay' ? 'selected' : '' }}>
                                                {{ trans("backend.essay") }}
                                            </option>
                                            <option value="multiple-choise" {{ $model->type == 'multiple-choise' ? 'selected' : '' }}>
                                                {{ trans("backend.multiple_choice") }}
                                            </option>
                                            <option value="matching" {{ $model->type == 'matching' ? 'selected' : '' }}>
                                                {{ trans("backend.matching_sentences") }}
                                            </option>
                                            {{--  <option value="fill_in" {{ $model->type == 'fill_in' ? 'selected' : '' }}>
                                                {{ trans("backend.fill_in") }}
                                            </option>  --}}
                                            <option value="fill_in_correct" {{ $model->type == 'fill_in_correct' ? 'selected' : '' }}>
                                                {{ trans('latraining.fill_correct_answer') }}
                                            </option>
                                            <option value="select_word_correct" {{ $model->type == 'select_word_correct' ? 'selected' : '' }}>
                                                {{ trans('latraining.choose_missing_word') }}
                                            </option>
                                            <option value="drag_drop_marker" {{ $model->type == 'drag_drop_marker' ? 'selected' : '' }}>
                                                {{ trans('latraining.drag_marker') }}
                                            </option>
                                            <option value="drag_drop_image" {{ $model->type == 'drag_drop_image' ? 'selected' : '' }}>
                                                {{ trans('latraining.drag_image') }}
                                            </option>
                                            <option value="drag_drop_document" {{ $model->type == 'drag_drop_document' ? 'selected' : '' }}>
                                                {{ trans('latraining.drag_text') }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row" id="image_drag_drop">
                                    <div class="col-sm-2 control-label">
                                        <label>{{ trans('latraining.wallpaper') }} </label>
                                    </div>
                                    <div class="col-md-10">
                                        <a href="javascript:void(0)" id="select_image_drag_drop">
                                            {{ trans('latraining.choose_picture') }}
                                        </a>
                                        <input name="image_drag_drop" id="image_drag_drop_select" type="text" class="d-none" value="{{ $model->image_drag_drop }}">
                                        <p class="mt-1">Accepted file types (.jpe, .jpeg, .jpg, .png)</p>
                                    </div>
                                </div>
                                <div class="form-group row" id="area_image_drag_drop">
                                    <div class="col-2"></div>
                                    <div class="col-10 w-100" id="image-area">
                                        @if ($model->image_drag_drop)
                                        <img src="{{ image_file($model->image_drag_drop) }}" class="border" onmousemove="getCoor(event)" onmouseout="clearCoor()" >
                                        @endif

                                        @if (isset($answers))
                                        <div class="mt-2" id="list_mark_text">
                                            @foreach ($answers as $ans_key => $answer)
                                                <span id="mark_text_{{ $ans_key }}"
                                                    @if ($answer->marker_answer)
                                                        @php
                                                            $left = explode(',', $answer->marker_answer)[0] . 'px';
                                                            $top = explode(',', $answer->marker_answer)[1] . 'px';
                                                        @endphp
                                                        style="position: absolute; top: {{ $top }}; left: {{ $left }};"
                                                    @endif
                                                >
                                                    @if ($answer->image_answer)
                                                        <img src="{{ image_file($answer->image_answer) }}" class="answer_image border" style="max-width: 150px;">
                                                    @else
                                                        <span class="m-1 p-2 border">{{ $answer->title }}</span>
                                                    @endif
                                                </span>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-12 text-center mt-2">
                                        <span class="coordinates">X = <span id="coordinates_x">0</span></span>
                                        <span class="coordinates">Y = <span id="coordinates_y">0</span></span>
                                    </div>
                                </div>

                                <div class="form-group row" id="choice_multiple">
                                    <div class="col-sm-2 control-label">
                                        <label>{{ trans("backend.choose") }} </label>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-check form-check-inline check-multi">
                                            <input class="form-check-input" type="checkbox" id="multiple" {{ $model->multiple == '1' ? 'checked' : '' }} {{ ($model->type == 'essay' || $model->type == 'matching' || $model->type == 'fill_in' || $model->type == 'fill_in_correct') ? 'disabled' : '' }}>
                                            <label class="form-check-label" for="multiple">{{trans("backend.select_all")}}</label>
                                            <input type="hidden" name="multiple" class="check-multiple" value="{{ $model->multiple ? $model->multiple : '0' }}">

                                            <input class="form-check-input ml-2" type="checkbox" id="multiple_full_score" {{ $model->multiple_full_score == '1' ? 'checked' : '' }} {{ ($model->type == 'essay' || $model->type == 'matching' || $model->type == 'fill_in' || $model->type == 'fill_in_correct') ? 'disabled' : '' }}>
                                            <label class="form-check-label" for="multiple_full_score">{{trans("latraining.multiple_full_score")}}</label>
                                            <input type="hidden" name="multiple_full_score" class="check-multiple-full-score" value="{{ $model->multiple_full_score ? $model->multiple_full_score : '0' }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row" id="answer_horizontal">
                                    <div class="col-sm-2 control-label">
                                        <label>{{ trans('laquestion_lib.answers_horizontal') }}</label>
                                    </div>
                                    <div class="col-md-10">
                                        <select name="answer_horizontal" class="form-control select2">
                                            <option value="0" {{ $model->answer_horizontal == 0 ? 'selected' : '' }}> 0</option>
                                            <option value="2" {{ $model->answer_horizontal == 2 ? 'selected' : '' }}> 2</option>
                                            <option value="3" {{ $model->answer_horizontal == 3 ? 'selected' : '' }}> 3</option>
                                            <option value="4" {{ $model->answer_horizontal == 4 ? 'selected' : '' }}> 4</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row" id="shuffle_answers">
                                    <div class="col-sm-2 control-label">
                                        <label>{{trans('backend.shuffle_answer')}}</label>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-check form-check-inline">
                                            <input required class="form-check-input" type="radio" name="shuffle_answers" id="shuffle_answers1" value="1" {{ ($model->shuffle_answers == 1) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="shuffle_answers1">{{trans("backend.enable")}}</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input required class="form-check-input" type="radio" name="shuffle_answers" id="shuffle_answers0" value="0" {{ ($model->shuffle_answers == 0) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="shuffle_answers0">{{trans("backend.disable")}}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row" id="answer_question">
                                    <div class="col-sm-2 control-label">
                                        <label>{{trans("backend.answer_question")}}</label>
                                    </div>
                                    <div class="col-md-10" id="anwser-list">
                                        @if(isset($answers))
                                            @foreach($answers as $key => $answer)
                                            <div class="anwser-item" data-ans_key="{{ $key }}">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <input type="hidden" name="ans_id[]" class="ans-id" value="{{ $answer->id }}">
                                                            @if(in_array($model->type , ['select_word_correct', 'drag_drop_document']))
                                                                <div class="form-group form-inline">
                                                                    <input type="text" size="60" class="form-control mr-2" value="{{ $answer->title }}" id="answer{{ $key }}" name="answer[]" placeholder="{{trans('backend.answer_question')}}" />
                                                                    <label class="mr-2">Nhóm</label>
                                                                    <select class="form-control" name="select_word_correct[]" placeholder="{{trans('backend.answer_question')}}">
                                                                        @for($i=1;$i<=5;$i++)
                                                                        <option value="{{$i}}" {{$i==$answer->select_word_correct?'selected':''}}>{{$i}}</option>
                                                                        @endfor
                                                                    </select>
                                                                    @if ($model->type == 'select_word_correct')
                                                                    <span class="ml-2 check-answer">
                                                                        <input type="checkbox" class="correct-answer" {{ $answer->correct_answer == 1 ? 'checked' : '' }}> {{ trans('backend.correct_answer') }}
                                                                        <input type="hidden" name="correct_answer[]" class="check-correct-answer" value="{{ $answer->correct_answer }}">
                                                                    </span>
                                                                    @endif
                                                                </div>
                                                            @elseif($model->type=='drag_drop_marker')
                                                                <div class="form-inline">
                                                                    <input type="text" size="60" class="form-control mr-2 mark_text" name="answer[]" value="{{ $answer->title }}" placeholder="{{ trans('backend.answer_question') }}" />
                                                                    <input type="text" size="60" class="form-control mr-2 marker_answer" name="marker_answer[]" value="{{ $answer->marker_answer }}" placeholder="x,y" />
                                                                </div>
                                                            @elseif($model->type=='drag_drop_image')
                                                                <div class="form-group form-inline">
                                                                    <input type="hidden" name="image_answer[]" class="" id="val_image_answer{{ $key }}" value="{{ $answer->image_answer }}">
                                                                    <a href="javascript:void(0)" id="select_image_drag_drop_image{{ $key }}" class="mr-1">
                                                                        {{ trans('latraining.choose_picture') }}
                                                                    </a>
                                                                    <input type="text" size="60" class="form-control mr-2 mark_text" id="answer{{ $key }}" name="answer[]" placeholder="{{ trans('backend.answer_question') }}" value="{{ $answer->title }}"/>
                                                                    <input type="text" size="10" class="form-control mr-2 marker_answer" name="marker_answer[]" placeholder="x,y" value="{{ $answer->marker_answer }}" />
                                                                    <label class="mr-2">Nhóm</label>
                                                                    <select class="form-control" name="select_word_correct[]" placeholder="{{ trans('backend.answer_question') }}">
                                                                        @for($i = 1; $i <= 5; $i++)
                                                                        <option value="{{ $i }}" {{ $i == $answer->select_word_correct ? 'selected' : '' }}> {{ $i }}</option>
                                                                        @endfor
                                                                    </select>
                                                                </div>
                                                            @else
                                                                <div class="col-sm-11">
                                                                    <textarea type="text" class="form-control" name="answer[]" id="answer{{ $answer->id }}" placeholder="{{trans('backend.answer_question')}}">
                                                                        {{ $answer->title }}
                                                                    </textarea>

                                                                    @if($model->type == 'matching')
                                                                        <input name="matching_answer[]" type="text" class="form-control" placeholder="Đáp án" value="{{ $answer->matching_answer }}">
                                                                    @elseif($model->type == 'fill_in_correct')
                                                                        <input name="fill_in_correct_answer[]" type="text" class="form-control" placeholder="Đáp án" value="{{ $answer->fill_in_correct_answer }}">
                                                                    @elseif($model->type == 'multiple-choise')
                                                                        <span class="check-answer">
                                                                            <input type="checkbox" class="correct-answer" {{ $answer->correct_answer == 1 ? 'checked' : '' }}> {{ trans('backend.correct_answer') }}
                                                                            <input type="hidden" name="correct_answer[]" class="check-correct-answer" value="{{ $answer->correct_answer }}">
                                                                        </span>
                                                                        <p></p>
                                                                        <span class="percent-answer">
                                                                            <input name="percent_answer[]" class="form-control is-number w-25 percent" placeholder="Nhập %" value="{{ $answer->percent_answer }}">
                                                                        </span>
                                                                        <p></p>
                                                                        <textarea name="feedback_answer[]" type="text" class="form-control" placeholder="Phản hồi cụ thể">{{$answer->feedback_answer }}</textarea>
                                                                    @endif
                                                                </div>
                                                                <div class="col-sm-1">
                                                                    <a href="javascript:void(0)" class="btn remove-anwser" data-ans="{{ $answer->id }}"> <i class="fa fa-trash"></i></a>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <p></p>
                                            </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="col-md-12 mt-1 text-right">
                                        <a href="javascript:void(0)" class="btn" id="add-answer" {{ $model->type === 'essay' ? 'hidden' : '' }}> {{ trans('backend.add_answer_question') }}</a>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-2 control-label">
                                        <label>
                                            {{ trans('backend.general_feedback') }}
                                            {{-- <a href="javascript:void(0)" class="btn" id="add-feedback"> <i class="fa fa-plus-circle"></i></a> --}}
                                        </label>
                                    </div>
                                    <div class="col-md-10" id="feedback-list">
                                        @if ($feedbacks)
                                            @foreach ($feedbacks as $feedback)
                                                <div class="feedback-item">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <input name="feedback[]" type="text" class="form-control" value="{{ $feedback }}">
                                                                </div>
                                                                {{-- <div class="col-sm-1">
                                                                    <a href="javascript:void(0)" class="btn text-danger remove-feedback">{{ trans('labutton.delete') }}</a>
                                                                </div> --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="feedback-item">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <input name="feedback[]" type="text" class="form-control" value="">
                                                            </div>
                                                            {{-- <div class="col-sm-1">
                                                                <a href="javascript:void(0)" class="btn text-danger remove-feedback">{{ trans('labutton.delete') }}</a>
                                                            </div> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-2 control-label">
                                        <label>{{ trans('backend.comment_question') }}</label>
                                    </div>
                                    <div class="col-md-10">
                                        <textarea name="note" type="text" class="form-control" value="" rows="3">{{ $model->note }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            var remove_answer = "{{ route('module.quiz.questionlib.remove_question_answer', ['id' => $category->id]) }}";
        </script>
    </div>

    {{-- Trắc nghiệm --}}
    <template id="anwser-template">
        <div class="anwser-item" data-ans_key="{ans_key}">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <input type="hidden" name="ans_id[]" class="ans-id" value="">
                        <div class="col-sm-11">
                            <textarea type="text" class="form-control" id="add_answer{ans_key}" name="answer[]" placeholder="{{trans('backend.answer_question')}}"></textarea>
                            <span class="check-answer">
                                <input type="checkbox" class="correct-answer"> {{ trans('backend.correct_answer') }}
                                <input type="hidden" name="correct_answer[]" class="check-correct-answer" value="0">
                            </span>
                            <p></p>
                            <span class="percent-answer">
                                <input name="percent_answer[]" class="form-control is-number w-25 percent" placeholder="Nhập %" value="">
                            </span>
                            <p></p>
                            <textarea name="feedback_answer[]" type="text" class="form-control" placeholder="Phản hồi cụ thể"></textarea>
                        </div>
                        <div class="col-sm-1">
                            <a href="javascript:void(0)" class="btn remove-anwser"><i class="fa fa-trash"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <p></p>
        </div>
    </template>

    {{-- Nối câu --}}
    <template id="matching-template">
        <div class="anwser-item" data-ans_key="{ans_key}">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <input type="hidden" name="ans_id[]" class="ans-id" value="">
                        <div class="col-sm-11">
                            <textarea type="text" class="form-control" id="add_answer{ans_key}" name="answer[]" placeholder="{{trans('backend.answer_question')}}"></textarea>
                            <input name="matching_answer[]" type="text" class="form-control" placeholder="Đáp án">
                        </div>
                        <div class="col-sm-1">
                            <a href="javascript:void(0)" class="btn remove-anwser"><i class="fa fa-trash"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <p></p>
        </div>
    </template>

    {{-- Điền vào --}}
    <template id="fill-in-template">
        <div class="anwser-item" data-ans_key="{ans_key}">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <input type="hidden" name="ans_id[]" class="ans-id" value="">
                        <div class="col-sm-11">
                            <textarea type="text" class="form-control" id="add_answer{ans_key}" name="answer[]" placeholder="{{trans('backend.answer_question')}}"></textarea>
                        </div>
                        <div class="col-sm-1">
                            <a href="javascript:void(0)" class="btn remove-anwser"><i class="fa fa-trash"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <p></p>
        </div>
    </template>

    {{-- Điền từ chính xác --}}
    <template id="fill-in-correct-template">
        <div class="anwser-item" data-ans_key="{ans_key}">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <input type="hidden" name="ans_id[]" class="ans-id" value="">
                        <div class="col-sm-11">
                            <textarea type="text" class="form-control" id="add_answer{ans_key}" name="answer[]" placeholder="{{trans('backend.answer_question')}}"></textarea>
                            <input name="fill_in_correct_answer[]" type="text" class="form-control" placeholder="Đáp án">
                        </div>
                        <div class="col-sm-1">
                            <a href="javascript:void(0)" class="btn remove-anwser"><i class="fa fa-trash"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <p></p>
        </div>
    </template>

    {{-- Phản hồi chung --}}
    <template id="feedback-template">
        <div class="feedback-item">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-11">
                            <input name="feedback[]" type="text" class="form-control" value="">
                        </div>
                        <div class="col-sm-1">
                            <a href="javascript:void(0)" class="btn remove-feedback"><i class="fa fa-trash"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    {{-- Chọn từ còn thiếu --}}
    <template id="select-word-correct-template">
        <div class="anwser-item" data-ans_key="{ans_key}">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group form-inline">
                            <input type="hidden" name="ans_id[]" class="ans-id" value="">
                            <input type="text" size="60" class="form-control mr-2" id="answer{ans_key}" name="answer[]" placeholder="{{trans('backend.answer_question')}}" />
                            <label class="mr-2">Nhóm</label>
                            <select class="form-control" name="select_word_correct[]" placeholder="{{trans('backend.answer_question')}}">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                            </select>
                            <span class="ml-2 check-answer">
                                <input type="checkbox" class="correct-answer"> {{ trans('backend.correct_answer') }}
                                <input type="hidden" name="correct_answer[]" class="check-correct-answer" value="0">
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <p></p>
        </div>
    </template>

    {{-- kéo thả điểm đánh dấu --}}
    <template id="drag-drop-marker-template">
        <div class="anwser-item" data-ans_key="{ans_key}">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group form-inline">
                            <input type="hidden" name="ans_id[]" class="ans-id" value="">
                            <input type="text" size="60" class="form-control mr-2 mark_text" name="answer[]" placeholder="{{ trans('backend.answer_question') }}" />
                            <input type="text" size="60" class="form-control mr-2 marker_answer" name="marker_answer[]" placeholder="x,y" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    {{-- Kéo thả hình ảnh --}}
    <template id="drag-drop-image-template">
        <div class="anwser-item" data-ans_key="{ans_key}">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group form-inline">
                            <input type="hidden" name="ans_id[]" class="ans-id" value="">
                            <input type="hidden" name="image_answer[]" class="" id="val_image_answer{ans_key}" value="">
                            <a href="javascript:void(0)" id="select_image_drag_drop_image{ans_key}" class="mr-1">
                                {{ trans('latraining.choose_picture') }}
                            </a>
                            <input type="text" size="60" class="form-control mr-2 mark_text" id="answer{ans_key}" name="answer[]" placeholder="{{ trans('backend.answer_question') }}" />
                            <input type="text" size="10" class="form-control mr-2 marker_answer" name="marker_answer[]" placeholder="x,y" />
                            <label class="mr-2">Nhóm</label>
                            <select class="form-control" name="select_word_correct[]" placeholder="{{ trans('backend.answer_question') }}">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <p></p>
        </div>
    </template>

    {{-- Kéo thả văn bản --}}
    <template id="drag-drop-document-template">
        <div class="anwser-item" data-ans_key="{ans_key}">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group form-inline">
                            <input type="hidden" name="ans_id[]" class="ans-id" value="">
                            <input type="text" size="60" class="form-control mr-2" id="answer{ans_key}" name="answer[]" placeholder="{{ trans('backend.answer_question') }}" />
                            <label class="mr-2">Nhóm</label>
                            <select class="form-control" name="select_word_correct[]" placeholder="{{ trans('backend.answer_question') }}">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <p></p>
        </div>
    </template>

    <script type="text/javascript">
        var anwser_template = document.getElementById('anwser-template').innerHTML;
        var matching_template = document.getElementById('matching-template').innerHTML;
        var feedback_template = document.getElementById('feedback-template').innerHTML;
        var fill_in_template = document.getElementById('fill-in-template').innerHTML;
        var fill_in_correct_template = document.getElementById('fill-in-correct-template').innerHTML;
        var select_word_correct_template = document.getElementById('select-word-correct-template').innerHTML;
        var drag_drop_marker_template = document.getElementById('drag-drop-marker-template').innerHTML;
        var drag_drop_document_template = document.getElementById('drag-drop-document-template').innerHTML;
        var drag_drop_image_template = document.getElementById('drag-drop-image-template').innerHTML;

        var check_shuffle_answers = "{{ $model->shuffle_answers }}";
        if(!check_shuffle_answers) {
            $("#shuffle_answers0").prop("checked", true);
        }

        let multi = $("input[name=multiple]").val();
        if (multi == '0'){
            $("#anwser-list").find('.percent-answer').hide();
            $('#anwser-list').find('.check-answer').show();
        } else {
            $("#anwser-list").find('.percent-answer').show();
            $('#anwser-list').find('.check-answer').hide();
        }

        var question_type = $("input[name=type]").val();
        if(question_type == 'multiple-choise'){
            $('#shuffle_answers').show();
            $('#answer_horizontal').show();
            $('#choice_multiple').show();
        }else{
            $('#shuffle_answers').hide();
            $('#answer_horizontal').hide();
            $('#choice_multiple').hide();

            if(question_type == 'essay'){
                $('#answer_question').hide();
            }
        }
        if (question_type == 'drag_drop_marker' || question_type == 'drag_drop_image') {
            $('#image_drag_drop').show();
            $('#area_image_drag_drop').show();
        }else{
            $('#image_drag_drop').hide();
            $('#area_image_drag_drop').hide();
        }

        let type = question_type ? question_type : '';
        $('#type').on('change', function(){
            $("#anwser-list").html('');
            $("#feedback-list").html('');
            type = $('#type option:selected').val();
            $("input[name=type]").val(type);

            if(type == 'essay'){
                $('#answer_question').hide();
            }else{
                $('#answer_question').show();
            }

            if(type == "multiple-choise"){
                $('.check-multi').show();
                $('#shuffle_answers').show();
                $('#answer_horizontal').show();
                $('#choice_multiple').show();
            }else{
                $('.check-multi').hide();

                $('#shuffle_answers').hide();
                $('#answer_horizontal').hide();
                $('#choice_multiple').hide();
            }

            if (type == 'drag_drop_marker' || type == 'drag_drop_image') {
                $('#image_drag_drop').show();
                $('#area_image_drag_drop').show();
            }else{
                $('#image_drag_drop').hide();
                $('#area_image_drag_drop').hide();
            }

        });

        $("#add-answer").on('click', function () {
            if (type == ''){
                show_message('Chưa chọn loại', 'error');
                return false;
            }

            var ans_key = parseInt($('.anwser-item').last().data('ans_key'), 10) + 1;
            if (isNaN(ans_key)) {
                ans_key = 0;
            }

            let anwser = '';
            if (type == 'multiple-choise'){
                anwser = replacement_template(anwser_template, {
                    'ans_key' : ans_key
                });
            }
            if (type == 'matching') {
                anwser = replacement_template(matching_template, {
                    'ans_key' : ans_key
                });
            }
            if (type == 'fill_in') {
                anwser = replacement_template(fill_in_template, {
                    'ans_key' : ans_key
                });
            }
            if (type == 'fill_in_correct') {
                anwser = replacement_template(fill_in_correct_template, {
                    'ans_key' : ans_key
                });
            }
            if (type == 'select_word_correct') {
                anwser = replacement_template(select_word_correct_template, {
                    'ans_key' : ans_key
                });
            }
            if (type == 'drag_drop_marker') {
                anwser = replacement_template(drag_drop_marker_template, {
                    'ans_key' : ans_key
                });
            }
            if (type == 'drag_drop_image') {
                anwser = replacement_template(drag_drop_image_template, {
                    'ans_key' : ans_key
                });
            }
            if (type == 'drag_drop_document') {
                anwser = replacement_template(drag_drop_document_template, {
                    'ans_key' : ans_key
                });
            }

            if(anwser != ''){
                $("#anwser-list").append(anwser);
            }

            let multi = $("input[name=multiple]").val();
            if (multi == '0'){
                $("#anwser-list").find('.percent-answer').hide();
                $('#anwser-list').find('.check-answer').show();
            } else {
                $("#anwser-list").find('.percent-answer').show();
                $('#anwser-list').find('.check-answer').hide();
            }

            if ($('#anwser-list #add_answer'+ans_key).length) {
                CKEDITOR.replace('add_answer'+ans_key+'', {
                    filebrowserImageBrowseUrl: '/filemanager?type=image',
                    filebrowserBrowseUrl: '/filemanager?type=file',
                    filebrowserUploadUrl : null, //disable upload tab
                    filebrowserImageUploadUrl : null, //disable upload tab
                    filebrowserFlashUploadUrl : null, //disable upload tab
                });
            }

            $("#select_image_drag_drop_image"+ans_key).on('click', function () {
                var lfm = function (options, cb) {
                    var route_prefix = '/filemanager';
                    window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
                    window.SetUrl = cb;
                };

                lfm({type: 'image'}, function (url, path) {
                    var path_name = path ? path.split('\/')[6] : '';

                    if($('#list_mark_text #mark_text_'+ans_key).length){
                        $('#list_mark_text #mark_text_'+ans_key).html('<img src="'+ path +'" class="answer_image border" style="max-width: 150px;">');
                    }else{
                        $("#list_mark_text").append('<span id="mark_text_'+ ans_key +'"><img src="'+ path +'" class="answer_image border" style="max-width: 150px;"></span>');
                    }

                    $("#val_image_answer"+ans_key).val(path);
                    $('#answer'+ans_key).val(path_name);
                });
            });
        });

        $("#add-feedback").on('click', function () {
            $("#feedback-list").append(feedback_template);
        });

        $("#feedback-list").on('click', '.remove-feedback', function () {
            $(this).closest('.feedback-item').remove();
        });

        $('#anwser-list').on('click', '.remove-anwser', function(){
            $(this).closest('.anwser-item').remove();

            var ans_id = $(this).data('ans');
            $.ajax({
                url: remove_answer,
                type: 'post',
                data: {
                    ans_id: ans_id,
                },
            }).done(function(data) {

                return false;
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        });

        $('#anwser-list').on('click', '.is-text', function(){

            if($(this).is(':checked')){
                $(this).closest('.anwser-item').find('.check-is-text').val(1);
            }else{
                $(this).closest('.anwser-item').find('.check-is-text').val(0);
            }
        });

        $('#anwser-list').on('click', '.correct-answer', function(){

            if($(this).is(':checked')){
                $(this).closest('.anwser-item').find('.check-correct-answer').val(1);
            }else{
                $(this).closest('.anwser-item').find('.check-correct-answer').val(0);
            }
        });

        //Gán giá trị đáp án vào chỗ chọn tọa độ
        $('#anwser-list').on('change', '.mark_text', function(){
            var ans_key = $(this).closest('.anwser-item').data('ans_key');
            var mark_text = $(this).val();

            if(!$('#list_mark_text #mark_text_'+ans_key+' .answer_image').length){
                if($('#list_mark_text #mark_text_'+ans_key).length){
                    $('#list_mark_text #mark_text_'+ans_key).html('<span class="m-1 p-2 border">'+mark_text+'</span>');
                }else{
                    var html_mark_text = '<span id="mark_text_'+ans_key+'" ><span class="m-1 p-2 border">'+mark_text+'</span></span>';

                    $('#list_mark_text').append(html_mark_text);
                }
            }
        })

        //Gán tọa độ đáp án vào hình
        $('#anwser-list').on('change', '.marker_answer', function(){
            var ans_key = $(this).closest('.anwser-item').data('ans_key');
            var marker_answer_text = $(this).val();

            var position = marker_answer_text ? 'absolute' : 'unset';
            var left = marker_answer_text ? marker_answer_text.split(",")[0] : 0;
            var top = marker_answer_text ? marker_answer_text.split(",")[1] : 0;

            if ($('#mark_text_'+ans_key).length) {
                $('#mark_text_'+ans_key).css({
                    'position': position,
                    'top': top+'px',
                    'left': left+'px',
                });
            }
            if ($('#image_answer'+ans_key).length) {
                $('#image_answer'+ans_key).css({
                    'position': position,
                    'top': top+'px',
                    'left': left+'px',
                });
            }
        });

        $('#multiple').on('click', function(){
            if($(this).is(':checked')){
                $(this).closest('.form-check').find('.check-multiple').val(1);
                $('#anwser-list').find('.check-answer').hide();
                $('#anwser-list').find('.percent-answer').show();
                $('#anwser-list').find('.check-correct-answer').val(0);
            }else{
                $(this).closest('.form-check').find('.check-multiple').val(0);
                $('#anwser-list').find('.check-answer').show();
                $('#anwser-list').find('.percent-answer').hide();
                $('#anwser-list').find('.percent').val('');
            }
        });

        $('#multiple_full_score').on('click', function(){
            if($(this).is(':checked')){
                $(this).closest('.form-check').find('.check-multiple-full-score').val(1);
            }else{
                $(this).closest('.form-check').find('.check-multiple-full-score').val(0);
            }
        });

        function replacement_template( template, data ){
            return template.replace(
                /{(\w*)}/g,
                function( m, key ){
                    return data.hasOwnProperty( key ) ? data[ key ] : "";
                }
            );
        }

        $("#select_image_drag_drop").on('click', function () {
            var lfm = function (options, cb) {
                var route_prefix = '/filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
                window.SetUrl = cb;
            };

            lfm({type: 'image'}, function (url, path) {
                $("#image-area").html('<img src="'+ path +'" class="border" onmousemove="getCoor(event)" onmouseout="clearCoor()"> <div class="mt-2" id="list_mark_text"></div>');
                $("#image_drag_drop_select").val(path);
            });
        });

        //Lấy tọa độ
        function getCoor(e) {
            $('#coordinates_x').html(e.offsetX);
            $('#coordinates_y').html(e.offsetY);
        }

        //Xóa tọa độ
        function clearCoor() {
            $('#coordinates_x').html('0');
            $('#coordinates_y').html('0');
        }
    </script>
    <script type="text/javascript">
        CKEDITOR.replace('name', {
            filebrowserImageBrowseUrl: '/filemanager?type=image',
            filebrowserBrowseUrl: '/filemanager?type=file',
            filebrowserUploadUrl : null, //disable upload tab
            filebrowserImageUploadUrl : null, //disable upload tab
            filebrowserFlashUploadUrl : null, //disable upload tab
        });

        var _answer_id = $('input[name=ans_id\\[\\]]').map(function(){return $(this).val();}).get();
        $.each(_answer_id, function (i, area) {
            if ($('#anwser-list #answer'+area).length) {
                CKEDITOR.replace('answer'+area+'', {
                    filebrowserImageBrowseUrl: '/filemanager?type=image',
                    filebrowserBrowseUrl: '/filemanager?type=file',
                    filebrowserUploadUrl : null, //disable upload tab
                    filebrowserImageUploadUrl : null, //disable upload tab
                    filebrowserFlashUploadUrl : null, //disable upload tab
                });
            }
        });
    </script>
@stop
