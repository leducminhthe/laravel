@extends('online::survey.app')

@section('page_title', $template->name)

@section('header')
    <style>
        .sortable_type_sort li:hover{
            cursor: grabbing;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid sa4d25">
    <div class="fcrse_2">
        <div class="_14d25">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <form id="form_save_survey">
                        <input type="hidden" name="survey_user_id" value="">
                        <input type="hidden" name="template_id" value="{{ $template->id }}">
                        <input type="hidden" name="course_id" value="{{ $course_id }}">
                        <input type="hidden" name="course_activity_id" value="{{ $course_activity_id }}">

                        <div class="certi_form mt-3">
                            <div class="all_ques_lest">
                                @php
                                    $categories = \Modules\Online\Entities\OnlineSurveyCategory::where(['course_id' => $course_id, 'course_activity_id' => $course_activity_id,'template_id' => $template->id])->get();
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
                                        $questions = \Modules\Online\Entities\OnlineSurveyQuestion::where(['course_id' => $course_id, 'course_activity_id' => $course_activity_id,'category_id' => $category->id])->get();

                                        $num_ques = 1;
                                    @endphp
                                    @foreach ($questions as $ques_key => $question)
                                        <input type="hidden" name="user_question_id[{{ $category->id }}][]" value="">
                                        <input type="hidden" name="question_id[{{ $category->id }}][]" value="{{ $question->id }}">
                                        <input type="hidden" name="question_code[{{ $category->id }}][{{ $question->id }}]" value="{{ $question->code }}">
                                        <input type="hidden" name="question_name[{{ $category->id }}][{{ $question->id }}]" value="{{ $question->name }}">
                                        <input type="hidden" name="type[{{ $category->id }}][{{ $question->id }}]" value="{{ $question->type }}">
                                        <input type="hidden" name="multiple[{{ $category->id }}][{{ $question->id }}]" value="{{ $question->multiple }}">
                                        @php
                                            $answers = \Modules\Online\Entities\OnlineSurveyAnswer::where(['course_id' => $course_id, 'course_activity_id' => $course_activity_id,'question_id' => $question->id])->get();
                                        @endphp

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
                                                            $rows = \Modules\Online\Entities\OnlineSurveyAnswer::where(['course_id' => $course_id, 'course_activity_id' => $course_activity_id,'question_id' => $question->id, 'is_row'=>1])->get();

                                                            $cols = \Modules\Online\Entities\OnlineSurveyAnswer::where(['course_id' => $course_id, 'course_activity_id' => $course_activity_id,'question_id' => $question->id, 'is_row'=>0])->get();

                                                            $answer_row_col = \Modules\Online\Entities\OnlineSurveyAnswer::where(['course_id' => $course_id, 'course_activity_id' => $course_activity_id,'question_id' => $question->id, 'is_row'=>10])->first();
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
                                                                            $matrix_anser_code = \Modules\Online\Entities\OnlineSurveyAnswerMatrix::where(['course_id' => $course_id, 'course_activity_id' => $course_activity_id,'question_id' => $question->id, 'answer_row_id' => $answer_row->id,'answer_col_id'=> $answer_col->id])->first();
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
                                                                            <img src="/images/heart_1.png" class="img_{{ $ans_key }} w-20" onclick="checkRankQuestion({{ $ans_key }},{{ $question->id }})">
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
                            <hr>
                            <div class="card-footer text-center">
                                <button type="button" id="send" class="btn"><i class="fa fa-location-arrow"></i> {{ trans('labutton.send') }}</button>
                                <input type="hidden" name="send" value="1">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function checkRankQuestion(ans_key, ques_id){
        $('#ques_rank_'+ques_id).find('img').attr("src", "/images/heart_1.png");

        for(var i = 0; i <= ans_key; i++){
            $('#ques_rank_'+ques_id+ ' .img_'+i).attr("src", "/images/heart_check.png");
        }
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

    $( document ).ready(function() {
        $('#send').on('click',function(event){
            let item = $(this);
            let oldtext = item.html();
            item.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> {{ trans("labutton.send") }}');

            $.ajax({
                type: "POST",
                url: "{{ route('module.online.survey.user.save') }}",
                dataType: 'json',
                data: $('#form_save_survey').serialize(),
                success: function (result) {
                    item.attr('disabled', false).html(oldtext);
                    if (result.status == "success") {
                        top.location.href = "{{ route('module.online.detail_new', [$course_id]) }}";
                    }
                    show_message(result.message, result.status);
                    return false;
                }
            });
        });
    });
</script>
@stop
