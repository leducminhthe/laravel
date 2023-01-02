@extends('layouts.app')

@section('page_title', 'Mô hình Kirkpatrick')

@section('header')
    <link rel="stylesheet" href="{{ asset('styles/module/rating/css/rating.css') }}">
    <style>
        .sortable_type_sort li:hover{
            cursor: grabbing;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid sa4d25" id="rating_level">
        <ol class="breadcrumb" style="background: white;margin-bottom: 0;">
            <li>
                <a href="/"><i class="glyphicon glyphicon-home"></i> &nbsp;{{ trans('lamenu.home_page') }}</a>
            </li>
            <li style="padding-left: 5px; color: #717171; padding-right: 5px; font-weight: 700;">»</li>
            @if($view_type == 'rating_level')
                <li>
                    <a href="{{ route('module.rating_level') }}"> {{ trans('lamenu.kirkpatrick_model') }}</a>
                </li>
            @else
                <li>
                    <a href="{{ $course_type == 1 ? route('frontend.all_course', ['type' => 1]) : route('frontend.all_course', ['type' => 2]) }}">
                        {{ trans('backend.course') }} {{ $course_type == 1 ? 'Online' : trans("latraining.offline") }}
                    </a>
                </li>
                <li style="padding-left: 5px; color: #717171; padding-right: 5px; font-weight: 700;">»</li>
                <li>
                    <a href="{{ $course_type == 1 ? route('module.online.detail_online', ['id' => $item->id]) : route('module.offline.detail', ['id' => $item->id]) }}"> {{ $item->name }}</a>
                </li>
            @endif
            <li style="padding-left: 5px; color: #717171; padding-right: 5px; font-weight: 700;">»</li>
            <li>{{ trans('backend.assessments') }}</li>
        </ol>

        <form action="{{ route('module.rating_level.save_rating_course', [$item->id, $course_type, $rating_level->id, $rating_user]) }}" method="post" class="form-validate form-ajax" role="form" enctype="multipart/form-data">

            <input type="hidden" name="rating_user_id" value="">
            <input type="hidden" name="level" value="{{ $rating_level->level }}">
            <input type="hidden" name="view_type" value="{{ $view_type }}">
            <input type="hidden" name="rating_level_object_id" value="{{ $rating_level_object_id }}">
            <input type="hidden" name="course_rating_level_object_id" value="{{ $course_rating_level_object_id }}">
            <input type="hidden" name="template_id" value="{{ $template->id }}">

            <div class="card">
                <div class="card-header">
                    <table class="table tDefault table-bordered">
                        <tr>
                            <th colspan="2"><b>Tên đánh giá: </b> {{ $rating_level->rating_name }}</th>
                        </tr>
                        <tr>
                            @if($course_type == 3)
                                <th colspan="2"><b>Tên kỳ đánh giá: </b> {{ $item->name }}</th>
                            @else
                                <th colspan="2"><b>{{ trans('latraining.course_name') }}: </b> {{ $item->name .' ('. $item->code .')' }}</th>
                            @endif
                        </tr>
                        <tr>
                            <th colspan="2">
                                <b>{{ trans('latraining.time_rating') }}: </b>
                                {{ get_date($start_date_rating) . ($end_date_rating ? ' đến '. get_date($end_date_rating) : '') }}
                            </th>
                        </tr>
                        <tr>
                            <th class="w-50"><b>Người đánh giá: </b> {{ $profile->full_name .' ('. $profile->code .')' }}</th>
                            <th class="w-50"><b>Đối tượng đánh giá: </b>
                                {{ $rating_level->object_rating == 1 ? 'Lớp học' : ($rating_level->object_rating == 3 ? 'Giảng viên' : ($object_rating->full_name .' ('. $object_rating->code .')')) }}
                            </th>
                        </tr>
                        <tr>
                            <th class="w-50"><b>{{ trans('latraining.title') }}: </b> {{ $profile->title_name }}</th>
                            <th class="w-50"><b>{{ trans('latraining.title') }}: </b> {{ in_array($rating_level->object_rating, [1,3]) ? '' : $object_rating->title_name }}</th>
                        </tr>
                        <tr>
                            <th class="w-50"><b>{{ trans('lamenu.unit') }}: </b> {{ $profile->unit_name }}</th>
                            <th class="w-50"><b>{{ trans('lamenu.unit') }}: </b> {{ in_array($rating_level->object_rating, [1,3]) ? '' : $object_rating->unit_name }}</th>
                        </tr>
                    </table>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12" id="custom-template">
                            @php
                                $categories = $template->category->where('course_rating_level_id', $rating_level->id)->where('course_rating_level_object_id', $course_rating_level_object_id)->where('course_id', $item->id)->where('course_type', $course_type);
                            @endphp
                            @foreach($categories as $cate_key => $category)
                                <input type="hidden" name="user_category_id[]" value="">
                                <input type="hidden" name="category_id[]" value="{{ $category->id }}">
                                <input type="hidden" name="category_name[{{ $category->id }}]" value="{{ $category->name }}">

                                <div class="ques_item mb-3">
                                    <h3 class="mb-0">{!! Str::ucfirst(nl2br($category->name)) !!}</h3>
                                    <hr class="mt-1">
                                </div>
                                @php
                                    $questions = $category->questions->where('course_rating_level_id', $rating_level->id)->where('course_rating_level_object_id', $course_rating_level_object_id)->where('course_id', $item->id)->where('course_type', $course_type);
                                    $num_ques = 1;
                                @endphp
                                @foreach ($questions as $ques_key => $question)
                                    <input type="hidden" name="user_question_id[{{ $category->id }}][]" value="">
                                    <input type="hidden" name="question_id[{{ $category->id }}][]" value="{{ $question->id }}">
                                    <input type="hidden" name="question_code[{{ $category->id }}][{{ $question->id }}]" value="{{ $question->code }}">
                                    <input type="hidden" name="question_name[{{ $category->id }}][{{ $question->id }}]" value="{{ $question->name }}">
                                    <input type="hidden" name="type[{{ $category->id }}][{{ $question->id }}]" value="{{ $question->type }}">
                                    <input type="hidden" name="multiple[{{ $category->id }}][{{ $question->id }}]" value="{{ $question->multiple }}">

                                    <div class="ques_item mb-2">
                                        <div class="ques_title survey mb-1">
                                            <span>{!! $num_ques .'. '. Str::ucfirst(nl2br($question->name)) !!}</span>
                                        </div>
                                        @if ($question->type == "essay")
                                            <div class="ui search focus">
                                                <div class="ui form swdh30 survey">
                                                    <div class="field">
                                                        <textarea class="w-100" rows="3" name="answer_essay[{{ $category->id }}][{{ $question->id }}]" placeholder="{{ trans('backend.content') }}"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($question->type == 'dropdown')
                                            <div class="ui form survey ml-5">
                                                @php
                                                    $answers = $question->answers->where('course_rating_level_id', $rating_level->id)->where('course_rating_level_object_id', $course_rating_level_object_id)->where('course_id', $item->id)->where('course_type', $course_type);
                                                @endphp
                                                <div class="grouped fields item-answer">
                                                    <select name="answer_essay[{{ $category->id }}][{{ $question->id }}]" class="form-control select2" data-placeholder="Chọn đáp án">
                                                        <option value=""></option>
                                                        @foreach($answers as $ans_key => $answer)
                                                            <option value="{{ $answer->id }}">{{ $answer->name }}</option>
                                                        @endforeach
                                                    </select>

                                                    @foreach($answers as $ans_key => $answer)
                                                        <input type="hidden" name="user_answer_id[{{ $category->id }}][{{ $question->id }}][]" value="">
                                                        <input type="hidden" name="answer_id[{{ $category->id }}][{{ $question->id }}][]" value="{{ $answer->id }}">
                                                        <input type="hidden" name="answer_code[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->code }}">
                                                        <input type="hidden" name="answer_name[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->name }}">
                                                        <input type="hidden" name="is_text[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->is_text }}">
                                                        <input type="hidden" name="is_row[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->is_row }}">
                                                    @endforeach
                                                </div>
                                            </div>
                                        @elseif ($question->type == "time")
                                            <div class="ui form survey ml-5">
                                                <div class="grouped fields item-answer">
                                                    <input name="answer_essay[{{ $category->id }}][{{ $question->id }}]" class="form-control question-datepicker w-auto" type="text" placeholder="ngày/tháng/năm" autocomplete="off">
                                                </div>
                                            </div>
                                        @elseif (in_array($question->type, ['matrix','matrix_text']))
                                            <div class="ui form survey ml-5">
                                                <div class="grouped fields item-answer">
                                                    @php
                                                        $rows = $question->answers->where('is_row', '=', 1)->where('course_rating_level_id', $rating_level->id)->where('course_rating_level_object_id', $course_rating_level_object_id)->where('course_id', $item->id)->where('course_type', $course_type);

                                                        $cols = $question->answers->where('is_row', '=', 0)->where('course_rating_level_id', $rating_level->id)->where('course_rating_level_object_id', $course_rating_level_object_id)->where('course_id', $item->id)->where('course_type', $course_type);

                                                        $answer_row_col = $question->answers->where('is_row', '=', 10)->where('course_rating_level_id', $rating_level->id)->where('course_rating_level_object_id', $course_rating_level_object_id)->where('course_id', $item->id)->where('course_type', $course_type)->first();
                                                    @endphp
                                                    <table class="tDefault table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            @if(isset($answer_row_col))
                                                                <input type="hidden" name="user_answer_id[{{ $category->id }}][{{ $question->id }}][]" value="">
                                                                <input type="hidden" name="answer_id[{{ $category->id }}][{{ $question->id }}][]" value="{{ $answer_row_col->id }}">
                                                                <input type="hidden" name="answer_code[{{ $category->id }}][{{ $question->id }}][{{ $answer_row_col->id }}]" value="{{ $answer_row_col->code }}">
                                                                <input type="hidden" name="answer_name[{{ $category->id }}][{{ $question->id }}][{{ $answer_row_col->id }}]" value="{{ $answer_row_col->name }}">
                                                                <input type="hidden" name="is_text[{{ $category->id }}][{{ $question->id }}][{{ $answer_row_col->id }}]" value="{{ $answer_row_col->is_text }}">
                                                                <input type="hidden" name="is_row[{{ $category->id }}][{{ $question->id }}][{{ $answer_row_col->id }}]" value="{{ $answer_row_col->is_row }}">

                                                                <th>{{ $answer_row_col->name }}</th>
                                                            @else
                                                                <th>#</th>
                                                            @endif
                                                            @foreach($cols as $ans_key => $answer_col)
                                                                <input type="hidden" name="user_answer_id[{{ $category->id }}][{{ $question->id }}][]" value="">
                                                                <input type="hidden" name="answer_id[{{ $category->id }}][{{ $question->id }}][]" value="{{ $answer_col->id }}">
                                                                <input type="hidden" name="answer_code[{{ $category->id }}][{{ $question->id }}][{{ $answer_col->id }}]" value="{{ $answer_col->code }}">
                                                                <input type="hidden" name="answer_name[{{ $category->id }}][{{ $question->id }}][{{ $answer_col->id }}]" value="{{ $answer_col->name }}">
                                                                <input type="hidden" name="is_text[{{ $category->id }}][{{ $question->id }}][{{ $answer_col->id }}]" value="{{ $answer_col->is_text }}">
                                                                <input type="hidden" name="is_row[{{ $category->id }}][{{ $question->id }}][{{ $answer_col->id }}]" value="{{ $answer_col->is_row }}">

                                                                <th>{{ $answer_col->name }}</th>
                                                            @endforeach
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($rows as $ans_row_key => $answer_row)
                                                            <input type="hidden" name="user_answer_id[{{ $category->id }}][{{ $question->id }}][]" value="">
                                                            <input type="hidden" name="answer_id[{{ $category->id }}][{{ $question->id }}][]" value="{{ $answer_row->id }}">
                                                            <input type="hidden" name="answer_code[{{ $category->id }}][{{ $question->id }}][{{ $answer_row->id }}]" value="{{ $answer_row->code }}">
                                                            <input type="hidden" name="answer_name[{{ $category->id }}][{{ $question->id }}][{{ $answer_row->id }}]" value="{{ $answer_row->name }}">
                                                            <input type="hidden" name="is_text[{{ $category->id }}][{{ $question->id }}][{{ $answer_row->id }}]" value="{{ $answer_row->is_text }}">
                                                            <input type="hidden" name="is_row[{{ $category->id }}][{{ $question->id }}][{{ $answer_row->id }}]" value="{{ $answer_row->is_row }}">

                                                            <tr>
                                                                <th>{{ $answer_row->name }}</th>
                                                                @foreach($cols as $ans_key => $answer_col)
                                                                    @php
                                                                        $matrix_anser_code = $question->answers_matrix->where('answer_row_id', '=', $answer_row->id)->where('answer_col_id', '=', $answer_col->id)->where('course_rating_level_id', $rating_level->id)->where('course_rating_level_object_id', $course_rating_level_object_id)->where('course_id', $item->id)->where('course_type', $course_type)->first();
                                                                    @endphp
                                                                    @if(isset($matrix_anser_code))
                                                                        <input type="hidden" name="answer_matrix_code[{{ $category->id }}][{{ $question->id }}][{{ $answer_row->id }}][{{ $answer_col->id }}]" value="{{ $matrix_anser_code->code }}">
                                                                    @endif

                                                                    <th class="text-center">
                                                                        @if($question->type == 'matrix')
                                                                            <input type="{{ $question->multiple != 1 ? 'radio' : 'checkbox' }}" name="check_answer_matrix[{{ $category->id }}][{{ $question->id }}][{{ $answer_row->id }}][]" tabindex="0" class="hidden" value="{{ $answer_col->id }}">
                                                                        @else
                                                                            <textarea rows="1" name="answer_matrix[{{ $category->id }}][{{ $question->id }}][{{ $answer_row->id }}][]"  class="form-control w-100"></textarea>
                                                                        @endif
                                                                    </th>
                                                                @endforeach
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                        @elseif($question->type == 'sort')
                                            @php
                                                $num_sort = 1;
                                            @endphp
                                            <div class="ui form survey ml-5">
                                                <ul class="grouped fields item-answer sortable_type_sort">
                                                    @php
                                                        $answers = $question->answers->where('course_rating_level_id', $rating_level->id)->where('course_rating_level_object_id', $course_rating_level_object_id)->where('course_id', $item->id)->where('course_type', $course_type);
                                                    @endphp
                                                    @foreach($answers as $ans_key => $answer)
                                                        <input type="hidden" name="user_answer_id[{{ $category->id }}][{{ $question->id }}][]" value="">
                                                        <input type="hidden" name="answer_id[{{ $category->id }}][{{ $question->id }}][]" value="{{ $answer->id }}">
                                                        <input type="hidden" name="answer_code[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->code }}">
                                                        <input type="hidden" name="answer_name[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->name }}">
                                                        <input type="hidden" name="is_text[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->is_text }}">
                                                        <input type="hidden" name="is_row[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->is_row }}">

                                                        <li class="field fltr-radio m-0">
                                                            <div class="ui">
                                                                <div class="form-inline mb-1">
                                                                    <span class="mr-1">{{ $answer->name }}</span>
                                                                    <input type="text" name="text_answer[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" class="answer-item-sort form-control w-5" value="{{ $num_sort }}">
                                                                </div>
                                                            </div>
                                                        </li>

                                                        @php
                                                            $num_sort += 1;
                                                        @endphp
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @elseif ($question->type == 'rank')
                                            <div class="ui form survey ml-5">
                                                <div class="grouped fields item-answer">
                                                    @php
                                                        $answers = $question->answers->where('course_rating_level_id', $rating_level->id)->where('course_rating_level_object_id', $course_rating_level_object_id)->where('course_id', $item->id)->where('course_type', $course_type);
                                                    @endphp
                                                    <table class="tDefault table" id="ques_rank_{{ $question->id }}">
                                                        <tr>
                                                            @foreach($answers as $ans_key => $answer)
                                                                <input type="hidden" name="user_answer_id[{{ $category->id }}][{{ $question->id }}][]" value="">
                                                                <input type="hidden" name="answer_id[{{ $category->id }}][{{ $question->id }}][]" value="{{ $answer->id }}">
                                                                <input type="hidden" name="answer_code[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->code }}">
                                                                <input type="hidden" name="answer_name[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->name }}">
                                                                <input type="hidden" name="is_text[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->is_text }}">
                                                                <input type="hidden" name="is_row[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->is_row }}">

                                                                <th class="text-center border-top-0 w-auto">
                                                                    <input type="radio" name="answer_essay[{{ $category->id }}][{{ $question->id }}]" id="is_check{{$answer->id}}" hidden value="{{ $answer->id }}">

                                                                    <label for="is_check{{$answer->id}}" class="mb-0">
                                                                        <img src="/images/heart_1.png" class="image_choose img_{{ $ans_key }} w-20" onclick="checkRankQuestion({{ $ans_key }},{{ $question->id }})">
                                                                        <br>
                                                                        {{ $answer->name }}
                                                                    </label>
                                                                </th>
                                                            @endforeach
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        @elseif ($question->type == 'rank_icon')
                                            <div class="ui form survey ml-5">
                                                <div class="grouped fields item-answer">
                                                    @php
                                                        $answers = $question->answers->where('course_rating_level_id', $rating_level->id)->where('course_rating_level_object_id', $course_rating_level_object_id)->where('course_id', $item->id)->where('course_type', $course_type);
                                                    @endphp
                                                    <table class="tDefault table" id="ques_rank_{{ $question->id }}">
                                                        <tr>
                                                            @foreach($answers as $ans_key => $answer)
                                                                <input type="hidden" name="user_answer_id[{{ $category->id }}][{{ $question->id }}][]" value="">
                                                                <input type="hidden" name="answer_id[{{ $category->id }}][{{ $question->id }}][]" value="{{ $answer->id }}">
                                                                <input type="hidden" name="answer_code[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->code }}">
                                                                <input type="hidden" name="answer_name[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->name }}">
                                                                <input type="hidden" name="is_text[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->is_text }}">
                                                                <input type="hidden" name="is_row[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->is_row }}">
                                                                <input type="hidden" name="icon[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->icon }}">

                                                                <th class="text-center border-top-0 w-auto">
                                                                    <input type="radio" name="is_check[{{ $category->id }}][{{ $question->id }}]" id="is_check{{$answer->id}}" tabindex="0" class="hidden" value="{{ $answer->id }}">
                                                                    <label for="is_check{{$answer->id}}" class="mb-0">
                                                                        <span style="font-size: 3em">{{ $answer->icon }}</span>
                                                                        <br>
                                                                        {{ $answer->name }}
                                                                    </label>
                                                                </th>
                                                            @endforeach
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        @else
                                            <div class="ui form survey ml-5">
                                                <ul class="grouped fields item-answer">
                                                    @php
                                                        $answers = $question->answers->where('course_rating_level_id', $rating_level->id)->where('course_rating_level_object_id', $course_rating_level_object_id)->where('course_id', $item->id)->where('course_type', $course_type);
                                                    @endphp
                                                    @foreach($answers as $ans_key => $answer)
                                                        <input type="hidden" name="user_answer_id[{{ $category->id }}][{{ $question->id }}][]" value="">
                                                        <input type="hidden" name="answer_id[{{ $category->id }}][{{ $question->id }}][]" value="{{ $answer->id }}">
                                                        <input type="hidden" name="answer_code[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->code }}">
                                                        <input type="hidden" name="answer_name[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->name }}">
                                                        <input type="hidden" name="is_text[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->is_text }}">
                                                        <input type="hidden" name="is_row[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->is_row }}">

                                                        @if($question->type == 'text')
                                                            <div class="field fltr-radio m-0">
                                                                <div class="ui">
                                                                    <div class="input-group d-flex align-items-center mb-1">
                                                                        <span class="mr-1">{{ $answer->name }}</span>
                                                                        <textarea rows="1" name="text_answer[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" class="form-control w-100"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if(in_array($question->type, ['number', 'percent']))
                                                            <div class="field fltr-radio m-0">
                                                                <div class="ui">
                                                                    <div class="form-inline mb-1">
                                                                        <span class="mr-1">{{ $answer->name }}</span>
                                                                        <input type="number" name="text_answer[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" class="form-control w-5">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if($question->type == 'choice')
                                                            <div class="field fltr-radio m-0">
                                                                <div class="ui mb-2">
                                                                    @if($question->multiple != 1)
                                                                        <input type="radio" name="is_check[{{ $category->id }}][{{ $question->id }}]" id="is_check{{$answer->id}}" tabindex="0" class="hidden" value="{{ $answer->id }}">
                                                                    @else
                                                                        <input type="checkbox" name="is_check[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" id="is_check{{$answer->id}}" tabindex="0" class="hidden" value="{{ $answer->id }}">
                                                                    @endif
                                                                    <label for="is_check{{$answer->id}}" class="mb-0">{{ $answer->name }}</label>
                                                                    @if($answer->is_text == 1)
                                                                        <input type="text" name="text_answer[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" class="form-control">
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>

                                    @php
                                        $num_ques += 1;
                                    @endphp
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button type="submit" class="btn">{{trans('labutton.save')}}</button>
                    <button type="submit" id="send" class="btn"> {{trans("backend.sents")}} </button>
                    <input type="hidden" name="send" value="0">
                </div>
            </div>
            <p></p>
        </form>
    </div>
    <script>
        function checkRankQuestion(ans_key, ques_id){
            $('#ques_rank_'+ques_id).find('img').attr("src", "/images/heart_1.png");

            for(var i = 0; i <= ans_key; i++){
                $('#ques_rank_'+ques_id+ ' .img_'+i).attr("src", "/images/heart_check.png");
            }
        }

        function checkRankLesson(ans_key){
            $('#ques_rank_lesson').find('img').attr("src", "/images/heart_1.png");

            for(var i = 1; i <= ans_key; i++){
                $('#ques_rank_lesson .img_'+i).attr("src", "/images/heart_check.png");
            }

            $('#ques_rank_lesson input[name=num_star_lesson]').val(ans_key);
        }

        function checkRankOrganization(ans_key){
            $('#ques_rank_organization').find('img').attr("src", "/images/heart_1.png");

            for(var i = 1; i <= ans_key; i++){
                $('#ques_rank_organization .img_'+i).attr("src", "/images/heart_check.png");
            }

            $('#ques_rank_organization input[name=num_star_organization]').val(ans_key);
        }

        function checkRankTeacher(ans_key, teacher_id, class_id){
            $('#ques_rank_teacher_'+teacher_id).find('img').attr("src", "/images/heart_1.png");

            for(var i = 1; i <= ans_key; i++){
                $('#ques_rank_teacher_'+teacher_id+' .img_'+i).attr("src", "/images/heart_check.png");
            }

            $('#ques_rank_teacher_'+teacher_id+' input[name=num_star_teacher\\['+class_id+'\\]\\['+teacher_id+'\\]]').val(ans_key);
        }

        $('.question-datepicker').datetimepicker({
            locale:'vi',
            format: 'DD/MM/YYYY'
        });

        $(".sortable_type_sort").sortable({
            update : function () {
                $('input.answer-item-sort').each(function(idx) {
                    $(this).val(idx + 1);
                });
            }
        });

        $(".sortable_type_sort").disableSelection();

        $('#send').on('click', function () {
            $('input[name=send]').val(1);
        });
    </script>
@stop
