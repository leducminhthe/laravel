<div class="modal fade modal-rating-level" id="myModal" data-backdrop="static" data-keyboard="false">

    <link rel="stylesheet" href="{{ asset('styles/module/rating/css/rating.css') }}">

    <style>
        .sortable_type_sort li:hover{
            cursor: grabbing;
        }
    </style>

    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">ĐÁNH GIÁ HIỆU QUẢ ĐÀO TẠO </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="form-rating" action="{{ route('module.offline.rating_level.save_rating_course', [$item->id, $rating_level_course->course_rating_level_id, $user_id, $rating_user]) }}" method="post" class="form-validate form-ajax" role="form" enctype="multipart/form-data">

                    <input type="hidden" name="rating_user_id" value="{{ $rating_level_course->id }}">
                    <input type="hidden" name="level" value="{{ $rating_level_course->level }}">
                    <input type="hidden" name="rating_level_object_id" value="{{ $rating_level_object_id }}">

                    <div class="card">
                        <div class="card-header">
                            <table class="table tDefault table-bordered">
                                <tr>
                                    <th><b>Tên đánh giá: </b> {{ $rating_level->rating_name }}</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th><b>{{ trans('latraining.course_name') }}: </b> {{ $item->name .' ('. $item->code .')' }}</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th><b>{{ trans('latraining.time_rating') }}: </b> {{ get_date($start_date_rating) . ($end_date_rating ? ' đến '. get_date($end_date_rating) : '') }}</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th><b>Người đánh giá: </b> {{ $profile->full_name .' ('. $profile->code .')' }}</th>
                                    <th><b>Đối tượng đánh giá: </b> {{ $rating_level->object_rating == 1 ? 'Lớp học' : ($object_rating->full_name .' ('. $object_rating->code .')') }}</th>
                                </tr>
                                <tr>
                                    <th><b>{{ trans('latraining.title') }}: </b> {{ $profile->title_name }}</th>
                                    <th><b>{{ trans('latraining.title') }}: </b> {{ $rating_level->object_rating == 1 ? '' : $object_rating->title_name }}</th>
                                </tr>
                                <tr>
                                    <th><b>{{ trans('lamenu.unit') }}: </b> {{ $profile->unit_name }}</th>
                                    <th> <b>{{ trans('lamenu.unit') }}: </b> {{ $rating_level->object_rating == 1 ? '' : $object_rating->unit_name }}</th>
                                </tr>
                            </table>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12" id="custom-template">
                                    @foreach($rating_course_categories as $cate_key => $category)
                                        <input type="hidden" name="user_category_id[]" value="{{ $category->id }}">
                                        <input type="hidden" name="category_id[]" value="{{ $category->category_id }}">
                                        <input type="hidden" name="category_name[{{ $category->category_id }}]" value="{{ $category->category_name }}">

                                        <div class="ques_item mb-3">
                                            <h3 class="mb-0">{{ Str::ucfirst($category->category_name) }}</h3>
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
                                                    <div class="ui search focus">
                                                        <div class="ui form swdh30 survey">
                                                            <div class="field">
                                                                <textarea class="w-100" rows="3" name="answer_essay[{{ $category->category_id }}][{{ $question->question_id }}]" placeholder="{{ trans('backend.content') }}">{{ $question->answer_essay }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif($question->type == 'dropdown')
                                                    <div class="ui form survey ml-5">
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
                                                    <div class="ui form survey ml-5">
                                                        <div class="grouped fields item-answer">
                                                            <input name="answer_essay[{{ $category->category_id }}][{{ $question->question_id }}]" class="form-control question-datepicker w-auto" type="text" placeholder="ngày/tháng/năm" autocomplete="off" value="{{ $question->answer_essay }}">
                                                        </div>
                                                    </div>
                                                @elseif (in_array($question->type, ['matrix','matrix_text']))
                                                    <div class="ui form survey ml-5">
                                                        <div class="grouped fields item-answer">
                                                            @php
                                                                $answer_row_col = $question->answers->where('is_row', '=', 10)->first();
                                                                $rows = $question->answers->where('is_row', '=', 1);
                                                                $cols = $question->answers->where('is_row', '=', 0);
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
                                                                                $matrix_anser_code = $question->answers_matrix->where('answer_row_id', '=', $answer_row->answer_id)->where('answer_col_id', '=', $answer_col->answer_id)->first();
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
                                                    <div class="ui form survey ml-5">
                                                        <ul class="grouped fields item-answer sortable_type_sort">
                                                            @foreach($question->answers()->orderBy('text_answer')->get() as $ans_key => $answer)
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
                                                                            <input type="text" name="text_answer[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" class="answer-item-sort form-control w-5" value="{{ $answer->text_answer }}">
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @else
                                                    <div class="ui form survey ml-5">
                                                        <ul class="grouped fields item-answer">
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
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <button type="submit" class="btn" {{ isset($rating_level_course) && $rating_level_course->send == 1 ? 'hidden' : '' }} > {{ trans('labutton.save') }} </button>
                            <button type="submit" id="send" class="btn" {{ isset($rating_level_course) && $rating_level_course->send == 1 ? 'disabled' : '' }}> {{ isset($rating_level_course) && $rating_level_course->send == 1 ? trans('labutton.sent') : trans('labutton.send') }}</button>
                            <input type="hidden" name="send" value="0">
                        </div>
                    </div>
                    <p></p>
                </form>
            </div>
        </div>
        <script>
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

            $('#send').on('click', function(){
                $('input[name=send]').val(1);
            })
        </script>
    </div>
</div>
