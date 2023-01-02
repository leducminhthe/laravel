@extends('layouts.backend')

@section('page_title', trans('latraining.teaching_organization'))

@section('breadcrumb')
    @php
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
                'name' => $item->name,
                'url' => route('module.offline.edit', ['id' => $item->id])
            ],
            [
                'name' => trans('latraining.teaching_organization'),
                'url' => route('module.offline.teaching_organization.index', [$item->id])
            ],
            [
                'name' => $profile->full_name,
                'url' => ''
            ]
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

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
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12" id="custom-template">
                            @foreach($categories as $cate_key => $category)
                                <input type="hidden" name="user_category_id[]" value="{{ $category->id }}">
                                <input type="hidden" name="category_id[]" value="{{ $category->category_id }}">
                                <input type="hidden" name="category_name[{{ $category->category_id }}]" value="{{ $category->category_name }}">
                                <input type="hidden" name="rating_teacher[{{ $category->category_id }}]" value="{{ $category->rating_teacher }}">

                                <div class="ques_item mb-3">
                                    <h5 class="mb-0">{!! Str::ucfirst(nl2br($category->category_name)) !!}</h5>
                                    <hr class="mt-1">
                                </div>
                                @if ($category->rating_teacher == 1)
                                    @foreach ($teachers as $teacher_key => $teacher)
                                        <input type="hidden" name="teacher_id[{{ $category->category_id }}][]" value="{{ $teacher->id }}">
                                        <h6 class="">{{ 'GV: '. $teacher->name .' ('. $teacher->code .')' }}</h6>

                                        @php
                                            $questions = $fquestions($category->id, $teacher->id);
                                            $num_ques = 1;
                                        @endphp
                                        @foreach ($questions as $ques_key => $question)
                                            <input type="hidden" name="user_question_id[{{ $category->category_id }}][{{ $teacher->id }}][]" value="{{ $question->id }}">
                                            <input type="hidden" name="question_id[{{ $category->category_id }}][{{ $teacher->id }}][]" value="{{ $question->question_id }}">
                                            <input type="hidden" name="question_code[{{ $category->category_id }}][{{ $teacher->id }}][{{ $question->question_id }}]" value="{{ $question->question_code }}">
                                            <input type="hidden" name="question_name[{{ $category->category_id }}][{{ $teacher->id }}][{{ $question->question_id }}]" value="{{ $question->question_name }}">
                                            <input type="hidden" name="type[{{ $category->category_id }}][{{ $teacher->id }}][{{ $question->question_id }}]" value="{{ $question->type }}">
                                            <input type="hidden" name="multiple[{{ $category->category_id }}][{{ $teacher->id }}][{{ $question->question_id }}]" value="{{ $question->multiple }}">

                                            <div class="ques_item mb-2">
                                                <div class="ques_title survey mb-1">
                                                    <span>{!! $num_ques .'. '. Str::ucfirst(nl2br($question->question_name)) !!}</span>
                                                </div>
                                                @if ($question->type == 'rank')
                                                    <div class="ui form survey ml-5">
                                                        <div class="grouped fields item-answer">
                                                            @php
                                                                $answers = $fanswer($question->id);
                                                            @endphp
                                                            <table class="tDefault table" id="ques_rank_{{ $question->question_id }}_teacher_{{ $teacher->id }}">
                                                                <tr>
                                                                    @foreach($answers as $ans_key => $answer)
                                                                        <input type="hidden" name="user_answer_id[{{ $category->category_id }}][{{ $teacher->id }}][{{ $question->question_id }}][]" value="{{ $answer->id }}">
                                                                        <input type="hidden" name="answer_id[{{ $category->category_id }}][{{ $teacher->id }}][{{ $question->question_id }}][]" value="{{ $answer->answer_id }}">
                                                                        <input type="hidden" name="answer_code[{{ $category->category_id }}][{{ $teacher->id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->answer_code }}">
                                                                        <input type="hidden" name="answer_name[{{ $category->category_id }}][{{ $teacher->id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->answer_name }}">
                                                                        <input type="hidden" name="is_text[{{ $category->category_id }}][{{ $teacher->id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->is_text }}">
                                                                        <input type="hidden" name="is_row[{{ $category->category_id }}][{{ $teacher->id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->is_row }}">

                                                                        <th class="text-center border-top-0 w-auto">
                                                                            <input type="radio" name="answer_essay[{{ $category->category_id }}][{{ $teacher->id }}][{{ $question->question_id }}]" id="is_check{{$answer->answer_id}}_teacher{{ $teacher->id }}" hidden value="{{ $answer->answer_id }}" {{ $answer->answer_id == $question->answer_essay ? 'checked' : '' }}>

                                                                            <label for="is_check{{$answer->answer_id}}_teacher{{ $teacher->id }}" class="mb-0">
                                                                                <img src="/images/{{ $answer->answer_id <= $question->answer_essay ? 'heart_check.png' : 'heart_1.png' }}" class="image_choose img_{{ $ans_key }} w-20" onclick="checkRankQuestionTeacher({{ $ans_key }},{{ $question->question_id }}, {{ $teacher->id }})">
                                                                                <br>
                                                                                {{ $answer->answer_name }}
                                                                            </label>
                                                                        </th>
                                                                    @endforeach
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            @php
                                                $num_ques += 1;
                                            @endphp
                                        @endforeach
                                    @endforeach
                                @else
                                    @php
                                        $questions = $fquestions($category->id);
                                        $num_ques = 1;
                                    @endphp
                                    @foreach ($questions as $ques_key => $question)
                                        <input type="hidden" name="user_question_id[{{ $category->category_id }}][]" value="{{ $question->id }}">
                                        <input type="hidden" name="question_id[{{ $category->category_id }}][]" value="{{ $question->question_id }}">
                                        <input type="hidden" name="question_code[{{ $category->category_id }}][{{ $question->question_id }}]" value="{{ $question->question_code }}">
                                        <input type="hidden" name="question_name[{{ $category->category_id }}][{{ $question->question_id }}]" value="{{ $question->question_name }}">
                                        <input type="hidden" name="type[{{ $category->category_id }}][{{ $question->question_id }}]" value="{{ $question->type }}">
                                        <input type="hidden" name="multiple[{{ $category->category_id }}][{{ $question->question_id }}]" value="{{ $question->multiple }}">

                                        <div class="ques_item mb-2">
                                            <div class="ques_title survey mb-1">
                                                <span>{!! $num_ques .'. '. Str::ucfirst(nl2br($question->question_name)) !!}</span>
                                            </div>
                                            @if ($question->type == "essay")
                                                <div class="ui search focus">
                                                    <div class="ui form swdh30 survey">
                                                        <div class="field">
                                                            <textarea class="w-100" rows="3" name="answer_essay[{{ $category->category_id }}][{{ $question->question_id }}]" placeholder="{{ trans('backend.content') }}">{{ $question->answer_essay }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($question->type == 'dropdown')
                                                <div class="ui form survey ml-5">
                                                    @php
                                                        $answers = $fanswer($question->id);
                                                    @endphp
                                                    <div class="grouped fields item-answer">
                                                        <select name="answer_essay[{{ $category->category_id }}][{{ $question->question_id }}]" class="form-control select2" data-placeholder="Chọn đáp án">
                                                            <option value=""></option>
                                                            @foreach($answers as $ans_key => $answer)
                                                                <option value="{{ $answer->answer_id }}" {{ $question->answer_essay == $answer->answer_id ? 'selected' : '' }}>
                                                                    {{ $answer->answer_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        @foreach($answers as $ans_key => $answer)
                                                            <input type="hidden" name="user_answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer->id }}">
                                                            <input type="hidden" name="answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer->answer_id }}">
                                                            <input type="hidden" name="answer_code[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->answer_code }}">
                                                            <input type="hidden" name="answer_name[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->answer_name }}">
                                                            <input type="hidden" name="is_text[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->is_text }}">
                                                            <input type="hidden" name="is_row[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->is_row }}">
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @elseif ($question->type == "time")
                                                <div class="ui form survey ml-5">
                                                    <div class="grouped fields item-answer">
                                                        <input name="answer_essay[{{ $category->category_id }}][{{ $question->question_id }}]" class="form-control question-datepicker w-auto" type="text" placeholder="ngày/tháng/năm" autocomplete="off" value="{{ $question->answer_essay }}">
                                                    </div>
                                                </div>
                                            @elseif (in_array($question->type, ['matrix','matrix_text']))
                                                <div class="ui form survey ml-5">
                                                    <div class="grouped fields item-answer">
                                                        @php
                                                            $rows = $fanswer($question->id);
                                                            $cols = $fanswer($question->id, 0);
                                                            $answer_row_col = $fanswer($question->id, 10);
                                                        @endphp
                                                        <table class="tDefault table table-bordered">
                                                            <thead>
                                                            <tr>
                                                                @if(isset($answer_row_col))
                                                                    <input type="hidden" name="user_answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer_row_col->id }}">
                                                                    <input type="hidden" name="answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer_row_col->answer_id }}">
                                                                    <input type="hidden" name="answer_code[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_row_col->answer_id }}]" value="{{ $answer_row_col->answer_code }}">
                                                                    <input type="hidden" name="answer_name[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_row_col->answer_id }}]" value="{{ $answer_row_col->answer_name }}">
                                                                    <input type="hidden" name="is_text[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_row_col->answer_id }}]" value="{{ $answer_row_col->is_text }}">
                                                                    <input type="hidden" name="is_row[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_row_col->answer_id }}]" value="{{ $answer_row_col->is_row }}">

                                                                    <th>{{ $answer_row_col->answer_name }}</th>
                                                                @else
                                                                    <th>#</th>
                                                                @endif
                                                                @foreach($cols as $ans_key => $answer_col)
                                                                    <input type="hidden" name="user_answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer_col->id }}">
                                                                    <input type="hidden" name="answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer_col->answer_id }}">
                                                                    <input type="hidden" name="answer_code[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_col->answer_id }}]" value="{{ $answer_col->answer_code }}">
                                                                    <input type="hidden" name="answer_name[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_col->answer_id }}]" value="{{ $answer_col->answer_name }}">
                                                                    <input type="hidden" name="is_text[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_col->answer_id }}]" value="{{ $answer_col->is_text }}">
                                                                    <input type="hidden" name="is_row[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_col->answer_id }}]" value="{{ $answer_col->is_row }}">

                                                                    <th>{{ $answer_col->answer_name }}</th>
                                                                @endforeach
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($rows as $ans_row_key => $answer_row)
                                                                <input type="hidden" name="user_answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer_row->id }}">
                                                                <input type="hidden" name="answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer_row->answer_id }}">
                                                                <input type="hidden" name="answer_code[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_row->answer_id }}]" value="{{ $answer_row->answer_code }}">
                                                                <input type="hidden" name="answer_name[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_row->answer_id }}]" value="{{ $answer_row->answer_name }}">
                                                                <input type="hidden" name="is_text[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_row->answer_id }}]" value="{{ $answer_row->is_text }}">
                                                                <input type="hidden" name="is_row[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_row->answer_id }}]" value="{{ $answer_row->is_row }}">

                                                                @php
                                                                    $check_answer_matrix = $answer_row->check_answer_matrix ? json_decode($answer_row->check_answer_matrix) : [];
                                                                    $answer_matrix = json_decode($answer_row->answer_matrix);
                                                                @endphp
                                                                <tr>
                                                                    <th>{{ $answer_row->answer_name }}</th>
                                                                    @foreach($cols as $ans_key => $answer_col)
                                                                        @php
                                                                            $matrix_anser_code =  $fanswer_matrix($question->id, $answer_row->answer_id, $answer_col->answer_id);
                                                                        @endphp
                                                                        @if(isset($matrix_anser_code))
                                                                            <input type="hidden" name="answer_matrix_code[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_row->answer_id }}][{{ $answer_col->answer_id }}]" value="{{ $matrix_anser_code->answer_code }}">
                                                                        @endif

                                                                        <th class="text-center">
                                                                            @if($question->type == 'matrix')
                                                                                <input type="{{ $question->multiple != 1 ? 'radio' : 'checkbox' }}" name="check_answer_matrix[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_row->answer_id }}][]" tabindex="0" class="hidden" value="{{ $answer_col->answer_id }}" {{ in_array($answer_col->answer_id, $check_answer_matrix) ? 'checked' : '' }}>
                                                                            @else
                                                                                <textarea rows="1" name="answer_matrix[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_row->answer_id }}][]"  class="form-control w-100">{{ isset($answer_matrix) ? $answer_matrix[$ans_key-1] : '' }}</textarea>
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
                                                            $answers = $fanswer($question->id);
                                                        @endphp
                                                        @foreach($answers as $ans_key => $answer)
                                                            <input type="hidden" name="user_answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer->id }}">
                                                            <input type="hidden" name="answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer->answer_id }}">
                                                            <input type="hidden" name="answer_code[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->answer_code }}">
                                                            <input type="hidden" name="answer_name[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->answer_name }}">
                                                            <input type="hidden" name="is_text[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->is_text }}">
                                                            <input type="hidden" name="is_row[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->is_row }}">

                                                            <li class="field fltr-radio m-0">
                                                                <div class="ui">
                                                                    <div class="form-inline mb-1">
                                                                        <span class="mr-1">{{ $answer->answer_name }}</span>
                                                                        <input type="text" name="text_answer[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" class="answer-item-sort form-control w-5" value="{{ $num_sort }}">
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
                                                            $answers = $fanswer($question->id);
                                                        @endphp
                                                        <table class="tDefault table" id="ques_rank_{{ $question->question_id }}">
                                                            <tr>
                                                                @foreach($answers as $ans_key => $answer)
                                                                    <input type="hidden" name="user_answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer->id }}">
                                                                    <input type="hidden" name="answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer->answer_id }}">
                                                                    <input type="hidden" name="answer_code[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->answer_code }}">
                                                                    <input type="hidden" name="answer_name[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->answer_name }}">
                                                                    <input type="hidden" name="is_text[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->is_text }}">
                                                                    <input type="hidden" name="is_row[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->is_row }}">

                                                                    <th class="text-center border-top-0 w-auto">
                                                                        <input type="radio" name="answer_essay[{{ $category->category_id }}][{{ $question->question_id }}]" id="is_check{{$answer->answer_id}}" hidden value="{{ $answer->answer_id }}" {{ $answer->answer_id == $question->answer_essay ? 'checked' : '' }}>

                                                                        <label for="is_check{{$answer->answer_id}}" class="mb-0">
                                                                            <img src="/images/{{ $answer->answer_id <= $question->answer_essay ? 'heart_check.png' : 'heart_1.png' }}" class="image_choose img_{{ $ans_key }} w-20" onclick="checkRankQuestion({{ $ans_key }},{{ $question->question_id }})">
                                                                            <br>
                                                                            {{ $answer->answer_name }}
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
                                                            $answers = $fanswer($question->id);
                                                        @endphp
                                                        <table class="tDefault table" id="ques_rank_{{ $question->question_id }}">
                                                            <tr>
                                                                @foreach($answers as $ans_key => $answer)
                                                                    <input type="hidden" name="user_answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer->id }}">
                                                                    <input type="hidden" name="answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer->answer_id }}">
                                                                    <input type="hidden" name="answer_code[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->answer_code }}">
                                                                    <input type="hidden" name="answer_name[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->answer_name }}">
                                                                    <input type="hidden" name="is_text[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->is_text }}">
                                                                    <input type="hidden" name="is_row[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->is_row }}">
                                                                    <input type="hidden" name="icon[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->icon }}">

                                                                    <th class="text-center border-top-0 w-auto">
                                                                        <input type="radio" name="is_check[{{ $category->category_id }}][{{ $question->question_id }}]" id="is_check{{$answer->answer_id}}" tabindex="0" class="hidden" value="{{ $answer->answer_id }}" {{ $answer->is_check ? 'checked' : '' }}>

                                                                        <label for="is_check{{$answer->answer_id}}" class="mb-0">
                                                                            <span style="font-size: 3em">{{ $answer->icon }}</span>
                                                                            <br>
                                                                            {{ $answer->answer_name }}
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
                                                            $answers = $fanswer($question->id);
                                                        @endphp
                                                        @foreach($answers as $ans_key => $answer)
                                                            <input type="hidden" name="user_answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer->id }}">
                                                            <input type="hidden" name="answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer->answer_id }}">
                                                            <input type="hidden" name="answer_code[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->answer_code }}">
                                                            <input type="hidden" name="answer_name[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->answer_name }}">
                                                            <input type="hidden" name="is_text[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->is_text }}">
                                                            <input type="hidden" name="is_row[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->is_row }}">

                                                            @if($question->type == 'text')
                                                                <div class="field fltr-radio m-0">
                                                                    <div class="ui">
                                                                        <div class="input-group d-flex align-items-center mb-1">
                                                                            <span class="mr-1">{{ $answer->answer_name }}</span>
                                                                            <textarea rows="1" name="text_answer[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" class="form-control w-100">{{ $answer->text_answer }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if(in_array($question->type, ['number', 'percent']))
                                                                <div class="field fltr-radio m-0">
                                                                    <div class="ui">
                                                                        <div class="form-inline mb-1">
                                                                            <span class="mr-1">{{ $answer->answer_name }}</span>
                                                                            <input type="number" name="text_answer[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" class="form-control w-5" value="{{ $answer->text_answer }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if($question->type == 'choice')
                                                                <div class="field fltr-radio m-0">
                                                                    <div class="ui mb-2">
                                                                        @if($question->multiple != 1)
                                                                            <input type="radio" name="is_check[{{ $category->category_id }}][{{ $question->question_id }}]" id="is_check{{$answer->answer_id}}" tabindex="0" class="hidden" value="{{ $answer->answer_id }}" {{ $answer->is_check ? 'checked' : '' }}>
                                                                        @else
                                                                            <input type="checkbox" name="is_check[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" id="is_check{{$answer->answer_id}}" tabindex="0" class="hidden" value="{{ $answer->answer_id }}" {{ $answer->is_check ? 'checked' : '' }}>
                                                                        @endif
                                                                        <label for="is_check{{$answer->answer_id}}" class="mb-0">{{ $answer->answer_name }}</label>
                                                                        @if($answer->is_text == 1)
                                                                            <input type="text" name="text_answer[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" class="form-control" value="{{ $answer->text_answer }}">
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
                                @endif

                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

    </div>
    <script>
        function checkRankQuestionTeacher(ans_key, ques_id, teacher_id){
            $('#ques_rank_'+ques_id+'_teacher_'+teacher_id).find('img').attr("src", "/images/heart_1.png");

            for(var i = 0; i <= ans_key; i++){
                $('#ques_rank_'+ques_id+'_teacher_'+teacher_id+' .img_'+i).attr("src", "/images/heart_check.png");
            }
        }

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
