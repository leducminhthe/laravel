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
                            <div class="ques_item mb-3">
                                <h5 class="mb-0">{!! Str::ucfirst(nl2br($category->name)) !!}</h5>
                                <hr class="mt-1">
                            </div>
                            @if ($category->rating_teacher == 1)
                                @foreach ($teachers as $teacher_key => $teacher)
                                    <h6 class="">{{ 'GV: '. $teacher->name .' ('. $teacher->code .')' }}</h6>

                                    @php
                                        $questions = $fquestions($category->id);
                                        $num_ques = 1;
                                    @endphp
                                    @foreach ($questions as $ques_key => $question)
                                        <div class="ques_item mb-2">
                                            <div class="ques_title survey mb-1">
                                                <span>{!! $num_ques .'. '. Str::ucfirst(nl2br($question->name)) !!}</span>
                                            </div>
                                            @if ($question->type == 'rank')
                                                <div class="ui form survey ml-5">
                                                    <div class="grouped fields item-answer">
                                                        @php
                                                            $answers = $fanswer($question->id);
                                                        @endphp
                                                        <table class="tDefault table" id="ques_rank_{{ $question->id }}_teacher_{{ $teacher->id }}">
                                                            <tr>
                                                                @foreach($answers as $ans_key => $answer)
                                                                    <th class="text-center border-top-0 w-auto">
                                                                        <label for="is_check{{$answer->id}}_teacher{{ $teacher->id }}" class="mb-0">
                                                                            <img src="/images/heart_1.png" class="image_choose img_{{ $ans_key }} w-20" onclick="checkRankQuestionTeacher({{ $ans_key }},{{ $question->id }}, {{ $teacher->id }})">
                                                                            <br>
                                                                            {{ $answer->name }}
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
                                    <div class="ques_item mb-2">
                                        <div class="ques_title survey mb-1">
                                            <span>{!! $num_ques .'. '. Str::ucfirst(nl2br($question->name)) !!}</span>
                                        </div>
                                        @if ($question->type == "essay")
                                            <div class="ui search focus">
                                                <div class="ui form swdh30 survey">
                                                    <div class="field">
                                                        <textarea class="w-100 p-2" rows="3" name="answer_essay[{{ $category->id }}][{{ $question->id }}]" placeholder="{{ trans('backend.content') }}"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($question->type == 'dropdown')
                                            <div class="ui form survey ml-5">
                                                @php
                                                    $answers = $fanswer($question->id);
                                                @endphp
                                                <div class="grouped fields item-answer">
                                                    <select name="answer_essay[{{ $category->id }}][{{ $question->id }}]" class="form-control select2" data-placeholder="Chọn đáp án">
                                                        <option value=""></option>
                                                        @foreach($answers as $ans_key => $answer)
                                                            <option value="{{ $answer->id }}">{{ $answer->name }}</option>
                                                        @endforeach
                                                    </select>
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
                                                        $rows = $fanswer($question->id);
                                                        $cols = $fanswer($question->id, 0);
                                                        $answer_row_col = $fanswer($question->id, 10);
                                                    @endphp
                                                    <table class="tDefault table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            @if(isset($answer_row_col))
                                                                <th>{{ $answer_row_col->name }}</th>
                                                            @else
                                                                <th>#</th>
                                                            @endif
                                                            @foreach($cols as $ans_key => $answer_col)
                                                                <th>{{ $answer_col->name }}</th>
                                                            @endforeach
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($rows as $ans_row_key => $answer_row)
                                                            <tr>
                                                                <th>{{ $answer_row->name }}</th>
                                                                @foreach($cols as $ans_key => $answer_col)
                                                                    <th class="text-center">
                                                                        @if($question->type == 'matrix')
                                                                            <input type="{{ $question->multiple != 1 ? 'radio' : 'checkbox' }}" name="check_answer_matrix[{{ $category->id }}][{{ $question->id }}][{{ $answer_row->id }}][]" tabindex="0" class="hidden" value="{{ $answer_col->id }}">
                                                                        @else
                                                                            <textarea rows="1" name="answer_matrix[{{ $category->id }}][{{ $question->id }}][{{ $answer_row->id }}][]"  class="form-control w-100 p-2"></textarea>
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
                                                        $answers = $fanswer($question->id);
                                                    @endphp
                                                    <table class="tDefault table" id="ques_rank_{{ $question->id }}">
                                                        <tr>
                                                            @foreach($answers as $ans_key => $answer)
                                                                <th class="text-center border-top-0 w-auto">
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
                                                        $answers = $fanswer($question->id);
                                                    @endphp
                                                    <table class="tDefault table" id="ques_rank_{{ $question->id }}">
                                                        <tr>
                                                            @foreach($answers as $ans_key => $answer)
                                                                <th class="text-center border-top-0 w-auto">
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
                                                        $answers = $fanswer($question->id);
                                                    @endphp
                                                    @foreach($answers as $ans_key => $answer)
                                                        @if($question->type == 'text')
                                                            <div class="field fltr-radio m-0">
                                                                <div class="ui">
                                                                    <div class="input-group d-flex align-items-center mb-1">
                                                                        <span class="mr-1">{{ $answer->name }}</span>
                                                                        <textarea rows="1" name="text_answer[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" class="form-control w-100 p-2"></textarea>
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
    </script>
@stop
