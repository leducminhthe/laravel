@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.survey'))

@section('header')
    <style>
        .input-group, .input-group > .form-control:last-child{
            border-radius: unset;
            border: none;
        }

        .sortable_type_sort li:hover{
            cursor: grabbing;
        }

        .w-20{
            width: 20px !important;
        }
    </style>
@endsection

@section('content')
    <div class="container wrraped_edit_survey">
        <div class="card shadow border-0 mb-3 mt-2 bg_social">
            <div class="card-body">
                <div class="row align-items-center text-white">
                    <div class="col align-self-center border-right">
                        <h6 class="font-weight-normal mb-1">{{ $item->name }}</h6>
                    </div>
                    <div class="col-3 align-self-center text-right">
                        <strong>{{ $item->countQuestion() }}</strong> <img src="{{ asset('themes/mobile/img/faq.png') }}" alt="" class="avatar-40">
                    </div>
                </div>
            </div>
        </div>
        @if($question_errors)
            <div class="col-12">
                @foreach($question_errors as $item_error)
                    <div class="alert alert-danger"> {!! $item_error[0] !!}</div>
                @endforeach
            </div>
            <p></p>
        @endif
        <form action="{{ route('themes.mobile.survey.user.save') }}" method="post" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="survey_user_id" value="{{ $survey_user->id }}">
            <input type="hidden" name="survey_id" value="{{ $item->id }}">
            <input type="hidden" name="template_id" value="{{ $item->template_id }}">
            @foreach($survey_user_categories as $cate_key => $category)
                <input type="hidden" name="user_category_id[]" value="{{ $category->id }}">
                <input type="hidden" name="category_id[]" value="{{ $category->category_id }}">
                <input type="hidden" name="category_name[{{ $category->category_id }}]" value="{{ $category->category_name }}">

                <div class="ques_item mb-3">
                    <h6 class="mb-0">{{ Str::ucfirst($category->category_name) }}</h6>
                    <hr class="mt-1">
                </div>
                @foreach ($category->questions as $ques_key => $question)
                    <input type="hidden" name="user_question_id[{{ $category->category_id }}][]" value="{{ $question->id }}">
                    <input type="hidden" name="question_id[{{ $category->category_id }}][]" value="{{ $question->question_id }}">
                    <input type="hidden" name="question_code[{{ $category->category_id }}][{{ $question->question_id }}]" value="{{ $question->question_code }}">
                    <input type="hidden" name="question_name[{{ $category->category_id }}][{{ $question->question_id }}]" value="{{ $question->question_name }}">
                    <input type="hidden" name="type[{{ $category->category_id }}][{{ $question->question_id }}]" value="{{ $question->type }}">
                    <input type="hidden" name="multiple[{{ $category->category_id }}][{{ $question->question_id }}]" value="{{ $question->multiple }}">

                    <div class="ques_item mb-2">
                        <div class="ques_title survey mb-1">
                            <span>{{ ($ques_key + 1) .'. '. Str::ucfirst($question->question_name) }}</span>
                        </div>
                        @if ($question->type == "essay")
                            <div class="ui focus">
                                <div class="ui form swdh30 survey">
                                    <div class="field">
                                        <textarea rows="3" class="w-100" name="answer_essay[{{ $category->category_id }}][{{ $question->question_id }}]" placeholder="{{ trans('backend.content') }}">{{ $question->answer_essay }}</textarea>
                                    </div>
                                </div>
                            </div>
                        @elseif($question->type == 'dropdown')
                            <div class="ui form survey">
                                <div class="grouped fields item-answer">
                                    <select name="answer_essay[{{ $category->category_id }}][{{ $question->question_id }}]" class="form-control select2" data-placeholder="Chọn đáp án">
                                        <option value=""></option>
                                        @foreach($question->answers as $ans_key => $answer)
                                            <option value="{{ $answer->answer_id }}" {{ $question->answer_essay == $answer->answer_id ? 'selected' : '' }}>
                                                {{ $answer->answer_name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @foreach($question->answers as $ans_key => $answer)
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
                            <div class="ui form survey">
                                <div class="grouped fields item-answer">
                                    <input name="answer_essay[{{ $category->category_id }}][{{ $question->question_id }}]" class="form-control question-datepicker w-auto" type="text" placeholder="ngày/tháng/năm" autocomplete="off" value="{{ $question->answer_essay }}">
                                </div>
                            </div>
                        @elseif (in_array($question->type, ['matrix','matrix_text']))
                            <div class="ui form survey">
                                <div class="grouped fields item-answer">
                                    @php
                                        $answer_row_col = $question->answers->where('is_row', '=', 10)->first();
                                        $rows = $question->answers->where('is_row', '=', 1);
                                        $cols = $question->answers->where('is_row', '=', 0);
                                    @endphp
                                    @if(isset($answer_row_col))
                                        <input type="hidden" name="user_answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer_row_col->id }}">
                                        <input type="hidden" name="answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer_row_col->answer_id }}">
                                        <input type="hidden" name="answer_code[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_row_col->answer_id }}]" value="{{ $answer_row_col->answer_code }}">
                                        <input type="hidden" name="answer_name[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_row_col->answer_id }}]" value="{{ $answer_row_col->answer_name }}">
                                        <input type="hidden" name="is_text[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_row_col->answer_id }}]" value="{{ $answer_row_col->is_text }}">
                                        <input type="hidden" name="is_row[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_row_col->answer_id }}]" value="{{ $answer_row_col->is_row }}">
                                    @endif
                                    @foreach($cols as $ans_key => $answer_col)
                                        <input type="hidden" name="user_answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer_col->id }}">
                                        <input type="hidden" name="answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer_col->answer_id }}">
                                        <input type="hidden" name="answer_code[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_col->answer_id }}]" value="{{ $answer_col->answer_code }}">
                                        <input type="hidden" name="answer_name[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_col->answer_id }}]" value="{{ $answer_col->answer_name }}">
                                        <input type="hidden" name="is_text[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_col->answer_id }}]" value="{{ $answer_col->is_text }}">
                                        <input type="hidden" name="is_row[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_col->answer_id }}]" value="{{ $answer_col->is_row }}">
                                    @endforeach

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
                                        <div>{{ $answer_row->answer_name }}</div>
                                        <div class="ml-1 border-bottom">
                                            @foreach($cols as $ans_key => $answer_col)
                                                @php
                                                    $matrix_anser_code = $question->answers_matrix->where('answer_row_id', '=', $answer_row->answer_id)->where('answer_col_id', '=', $answer_col->answer_id)->first();
                                                @endphp
                                                @if(isset($matrix_anser_code))
                                                    <input type="hidden" name="answer_matrix_code[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_row->answer_id }}][{{ $answer_col->answer_id }}]" value="{{ $matrix_anser_code->answer_code }}">
                                                @endif

                                                @if($question->type == 'matrix')
                                                    <div class="field fltr-radio m-0">
                                                        <div class="ui mb-2">
                                                            <input type="{{ $question->multiple != 1 ? 'radio' : 'checkbox' }}" name="check_answer_matrix[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_row->answer_id }}][]" id="is_check{{$answer_row->answer_id}}" tabindex="0" class="hidden mr-1" value="{{ $answer_col->answer_id }}" {{ in_array($answer_col->answer_id, $check_answer_matrix) ? 'checked' : '' }}> {{ $answer_col->answer_name }}
                                                        </div>
                                                    </div>
                                                @else
                                                    <div>{{ $answer_col->answer_name }}</div>
                                                    <textarea rows="1" name="answer_matrix[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_row->answer_id }}][]"  class="form-control w-100">{{ isset($answer_matrix) ? $answer_matrix[$ans_key-1] : '' }}</textarea>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @elseif($question->type == 'sort')
                            <div class="ui form survey">
                                <div class="grouped fields item-answer sortable_type_sort">
                                    @foreach($question->answers()->orderBy('text_answer')->get() as $ans_key => $answer)
                                        <input type="hidden" name="user_answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer->id }}">
                                        <input type="hidden" name="answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer->answer_id }}">
                                        <input type="hidden" name="answer_code[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->answer_code }}">
                                        <input type="hidden" name="answer_name[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->answer_name }}">
                                        <input type="hidden" name="is_text[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->is_text }}">
                                        <input type="hidden" name="is_row[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->is_row }}">

                                        <div class="field fltr-radio m-0">
                                            <div class="ui">
                                                <div class="form-inline mb-1">
                                                    <span class="mr-1">{{ $answer->answer_name }}</span>
                                                    <input type="text" name="text_answer[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" class="answer-item-sort form-control w-5" value="{{ $answer->text_answer }}">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @elseif ($question->type == 'rank')
                            <div class="ui form survey">
                                <div class="grouped fields item-answer">
                                    <table class="tDefault table" id="ques_rank_{{ $question->question_id }}">
                                        <tr>
                                            @foreach($question->answers as $ans_key => $answer)
                                                <input type="hidden" name="user_answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer->id }}">
                                                <input type="hidden" name="answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer->answer_id }}">
                                                <input type="hidden" name="answer_code[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->answer_code }}">
                                                <input type="hidden" name="answer_name[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->answer_name }}">
                                                <input type="hidden" name="is_text[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->is_text }}">
                                                <input type="hidden" name="is_row[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->is_row }}">

                                                <th class="text-center border-top-0 w-auto p-0">
                                                    <input type="radio" name="answer_essay[{{ $category->category_id }}][{{ $question->question_id }}]" id="is_check{{$answer->answer_id}}" hidden value="{{ $answer->answer_id }}" {{ $answer->answer_id == $question->answer_essay ? 'checked' : '' }}>

                                                    <label for="is_check{{$answer->answer_id}}" class="mb-0">
                                                        <img src="/images/{{ $answer->answer_id <= $question->answer_essay ? 'heart_check.png' : 'heart_1.png' }}" class="img_{{ $ans_key }}" width="25px" height="25px" onclick="checkRankQuestion({{ $ans_key }},{{ $question->question_id }})">
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
                            <div class="ui form survey">
                                <div class="grouped fields item-answer">
                                    <table class="tDefault table" id="ques_rank_{{ $question->question_id }}">
                                        <tr>
                                            @foreach($question->answers as $ans_key => $answer)
                                                <input type="hidden" name="user_answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer->id }}">
                                                <input type="hidden" name="answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer->answer_id }}">
                                                <input type="hidden" name="answer_code[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->answer_code }}">
                                                <input type="hidden" name="answer_name[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->answer_name }}">
                                                <input type="hidden" name="is_text[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->is_text }}">
                                                <input type="hidden" name="is_row[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->is_row }}">
                                                <input type="hidden" name="icon[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->icon }}">

                                                <th class="text-center border-top-0 w-auto p-0">
                                                    <input type="radio" name="is_check[{{ $category->category_id }}][{{ $question->question_id }}]" id="is_check{{$answer->answer_id}}" tabindex="0" class="hidden" value="{{ $answer->answer_id }}" {{ $answer->is_check ? 'checked' : '' }}>
                                                    <label for="is_check{{$answer->answer_id}}" class="mb-0">
                                                        <span style="font-size: 1em">{{ $answer->icon }}</span>
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
                            <div class="ui form survey">
                                <div class="grouped fields item-answer">
                                    @foreach($question->answers as $ans_key => $answer)
                                        <input type="hidden" name="user_answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer->id }}">
                                        <input type="hidden" name="answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer->answer_id }}">
                                        <input type="hidden" name="answer_code[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->answer_code }}">
                                        <input type="hidden" name="answer_name[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->answer_name }}">
                                        <input type="hidden" name="is_text[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->is_text }}">
                                        <input type="hidden" name="is_row[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->is_row }}">

                                        @if($question->type == 'text')
                                            <div class="field fltr-radio m-0">
                                                <div class="ui">
                                                    <div class="input-group mb-1 d-flex align-items-center">
                                                        <span class="mr-1">{{ $answer->answer_name }}</span>
                                                        <textarea rows="1" name="text_answer[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" class="form-control w-auto">{{ $answer->text_answer }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if(in_array($question->type, ['number', 'percent']))
                                            <div class="field fltr-radio m-0">
                                                <div class="ui">
                                                    <div class="form-inline mb-1">
                                                        <span class="mr-1">{{ $answer->answer_name }}</span>
                                                        <input type="text" name="text_answer[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" class="form-control w-5" value="{{ $answer->text_answer }}">
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
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endforeach
            <hr>
            @if($item->more_suggestions)
                {{ trans('app.other_suggest') }}
                <div class="row">
                    <div class="col-sm-12">
                        <textarea class="w-100 form-control" name="more_suggestions" rows="5" placeholder="{{ trans('app.content') }}">{{ $survey_user->more_suggestions }}</textarea>
                    </div>
                </div>
            @endif
            <div class="card-footer text-center" {{ $survey_user->send == 1 ? 'hidden' : '' }}>
                <div class="row">
                    <div class="col-4">
                        <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.survey') }}')" class="btn w-100 p-2">{{ trans('app.close') }}</a>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn w-100 p-2"> <i class="fa fa-save"></i> {{ trans('app.save') }}</button>
                    </div>
                    <div class="col-4">
                        <button type="submit" id="send" class="btn w-100 p-2"> <i class="fa fa-location-arrow"></i> {{ trans('labutton.send') }}</button>
                    </div>
                </div>

                <input type="hidden" name="send" value="{{ $survey_user->send }}">
            </div>
        </form>
    </div>
@endsection

@section('footer')
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

        $('#send').on('click', function(){
            $('input[name=send]').val(1);
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
@endsection
