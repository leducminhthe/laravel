@extends('layouts.backend')

@section('page_title', $page_title)

@section('header')
    <style>
        #input-category .item-category .input-group .btn-remove,
        #input-category .item-question .input-group .btn-remove,
        #input-category .item-answer .input-group .btn-remove,
        #input-category th .btn-remove-row-matrix{
            display: flex;
        }

        textarea{
            height: calc(1.5em + 0.75rem + 2px);
        }
        textarea.form-control{
            height: calc(1.5em + 0.75rem + 5px) !important;
        }
        #form-question .btn {
            margin-left: 0px !important;
        }
        .custom-num-order{
            width: 40px;
        }
    </style>
@endsection

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.survey'),
                'url' => route('module.survey.index')
            ],
            [
                'name' => $survey->name,
                'url' => route('module.survey.edit', ['id' => $survey->id])
            ],
            [
                'name' => $page_title,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
<div role="main">
    <form method="post" action="{{ route('module.survey.template.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data" id="form-question">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <input type="hidden" name="survey_id" value="{{ $survey->id }}">
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['survey-template-create', 'survey-template-edit'])
                    <button type="submit" class="btn" id="save-template" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                    @endcanany
                    <a href="{{ route('module.survey.edit', ['id' => $survey->id]) }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <br>
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="active"><a href="#base" role="tab" data-toggle="tab">{{ trans('lasurvey.info') }}</a></li>
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    <div class="row">
                        <div class="col-md-12">
                            {{-- <div class="form-group row">
                                <div class="col-sm-1 control-label">
                                    <label>{{ trans('lasurvey.template_name') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="{{ $model->course ?? 0 }}" id="course" name="course" {{ $model->course == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="course">
                                            {{ trans('latraining.course') }}
                                        </label>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                        <div class="col-md-12" id="input-category">
                            @if(isset($categories))
                                @foreach($categories as $cate_key => $category)
                                    <div class="item-category mt-2" data-cate_key="{{ $cate_key }}">
                                        <div class="card">
                                            <div class="card-header bg-info">
                                                <input name="category_id[]" type="hidden" value="{{ $category->id }}">
                                                <div class="input-group">
                                                    <textarea name="category_name[]" class="form-control" placeholder="-- {{ trans('lasurvey.category') }} --" required>{{ $category->name }}</textarea>
                                                    <a href="javascript:void(0)" class="btn btn-remove align-items-center" id="del-category" data-cate_id="{{ $category->id }}"><i class="fa fa-trash"></i></a>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="input-question-{{ $cate_key }} list-question">
                                                    @php
                                                        $questions = $fquestions($category->id);
                                                    @endphp
                                                    @if(isset($questions))
                                                        @foreach($questions as $ques_key => $question)
                                                            <div class="item-question" data-ques_key="{{ $ques_key }}">
                                                                <div class="card">
                                                                    <div class="card-header">
                                                                        <div class="row px-0">
                                                                            <div class="col-9 pr-0">
                                                                                <input name="question_id[{{ $cate_key }}][]" type="hidden" value="{{ $question->id }}">

                                                                                <div class="input-group">
                                                                                    <input name="num_order[{{ $cate_key }}][{{ $ques_key }}]" class="p-1 custom-num-order" type="text" data-ques="{{ $question->id }}" value="{{ $question->num_order }}">

                                                                                    <textarea name="question_code[{{ $cate_key }}][]" class="p-1 w-5 question_code" placeholder="-- {{ trans('lasurvey.question_code') }}  {{ $ques_key + 1 }} --">{{ $question->code }}</textarea>
                                                                                    <textarea name="question_name[{{ $cate_key }}][]" class="form-control" placeholder="-- {{ trans('lasurvey.question') }}  {{ $ques_key + 1 }} --" required>{{ $question->name }}</textarea>
                                                                                    <a href="javascript:void(0)" class="btn btn-remove align-items-center" id="del-question" data-ques_id="{{ $question->id }}"><i class="fa fa-trash"></i></a>
                                                                                </div>
                                                                                <div class="obligatory mt-2">
                                                                                    <input type="checkbox" class="cursor_pointer" name="obligatory[{{ $cate_key }}][{{ $ques_key }}]" id="obligatory_{{ $cate_key }}_{{ $ques_key }}" {{ $question->obligatory == 1 ? 'checked' : '' }}>
                                                                                    <label class="mb-0 cursor_pointer" for="obligatory_{{ $cate_key }}_{{ $ques_key }}">{{ trans('latraining.obligatory') }}</label>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-3">
                                                                                <div class="d-flex align-items-center">
                                                                                    @if(isset($model->id))
                                                                                        <input type="hidden" name="type[{{ $cate_key }}][{{ $ques_key }}]" value="{{ $question->type }}">
                                                                                    @endif
                                                                                    <select @if(isset($model->id)) disabled @else name="type[{{ $cate_key }}][{{ $ques_key }}]" @endif class="form-control select2" data-placeholder="{{ trans('lasurvey.question_type') }}" id="ques_type_{{ $cate_key }}_{{ $ques_key }}" >
                                                                                        <option value=""></option>
                                                                                        <option value="choice" {{ $question->type == 'choice' ? 'selected' : '' }}> {{ trans('lasurvey.choice') }}</option>
                                                                                        <option value="essay" {{ $question->type == 'essay' ? 'selected' : '' }}> {{ trans('lasurvey.essay') }}</option>
                                                                                        <option value="text" {{ $question->type == 'text' ? 'selected' : '' }}> {{ trans('lasurvey.text') }}</option>
                                                                                        <option value="matrix" {{ $question->type == 'matrix' ? 'selected' : '' }}> {{ trans('lasurvey.matrix') }}</option>
                                                                                        <option value="matrix_text" {{ $question->type == 'matrix_text' ? 'selected' : '' }}> {{ trans('lasurvey.matrix_text') }}</option>
                                                                                        <option value="dropdown" {{ $question->type == 'dropdown' ? 'selected' : '' }}> {{ trans('lasurvey.dropdown') }}</option>
                                                                                        <option value="sort" {{ $question->type == 'sort' ? 'selected' : '' }}> {{ trans('lasurvey.sort') }}</option>
                                                                                        <option value="percent" {{ $question->type == 'percent' ? 'selected' : '' }}> {{ trans('lasurvey.percent') }}</option>
                                                                                        <option value="number" {{ $question->type == 'number' ? 'selected' : '' }}> {{ trans('lasurvey.number') }}</option>
                                                                                        <option value="time" {{ $question->type == 'time' ? 'selected' : '' }}> {{ trans('lasurvey.time') }}</option>
                                                                                        <option value="rank" {{ $question->type == 'rank' ? 'selected' : '' }}> Đánh giá mức độ</option>
                                                                                        <option value="rank_icon" {{ $question->type == 'rank_icon' ? 'selected' : '' }}> Đánh giá icon</option>
                                                                                    </select>
                                                                                    <span class="btn view_question" id="view_question_{{$cate_key}}_{{$ques_key}}"  data-ques_type="{{ $question->type }}" data-multi={{ $question->multiple }}>
                                                                                        <i class="fa fa-eye"></i>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        @if($question->type == 'essay')
                                                                            <textarea class="form-control" placeholder="{{ trans('lasurvey.content') }}" readonly></textarea>
                                                                        @endif
                                                                        @if($question->type == 'time')
                                                                            <div class="input-group mb-3">
                                                                                <input type="text" class="form-control" placeholder="{{ trans('lasurvey.date_format') }}" aria-describedby="basic-addon2" readonly>
                                                                                <div class="input-group-append">
                                                                                    <span class="input-group-text" id="basic-addon2"> <i class="fa fa-clock"></i></span>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                        @if(in_array($question->type, ['matrix', 'matrix_text']))
                                                                            @php
                                                                                $answers_row = $question->answers->where('is_row', '=', 1);
                                                                                $answers_col = $question->answers->where('is_row', '=', 0);
                                                                                $answer_row_col = $question->answers->where('is_row', '=', 10)->first();
                                                                            @endphp
                                                                                <div class="form-group row px-0">
                                                                                    <div class="col-6">
                                                                                        <a class="" id="btn-question-answer-row" data-cate_key="{{ $cate_key }}" data-ques_key="{{ $ques_key }}">
                                                                                            <i class="fa fa-plus"></i> {{ trans('lasurvey.add_row') }}
                                                                                        </a>
                                                                                    </div>
                                                                                    <div class="col-6 text-right">
                                                                                        <a class="" id="btn-question-answer-col" data-cate_key="{{ $cate_key }}" data-ques_key="{{ $ques_key }}">
                                                                                            <i class="fa fa-plus"></i> {{ trans('lasurvey.add_col') }}
                                                                                        </a>
                                                                                    </div>
                                                                                    <div class="col-12 mt-2">
                                                                                        <table class="table table-bordered" id="table-matrix-{{ $cate_key .'-'. $ques_key }}">
                                                                                            <tr class="matrix-row-title">
                                                                                                <th>
                                                                                                    <input name="is_row[{{ $cate_key }}][{{ $ques_key }}][]" type="hidden" value="10">
                                                                                                    <input name="answer_id[{{ $cate_key }}][{{ $ques_key }}][]" type="hidden" value="{{ isset($answer_row_col) ? $answer_row_col->id : '' }}">
                                                                                                    <div class="input-group mt-3">
                                                                                                        <textarea name="answer_code[{{ $cate_key }}][{{ $ques_key }}][]" class="form-control w-100 answer_code" data-cate_key="{{ $cate_key }}" data-ques_key="{{ $ques_key }}" placeholder="{{ trans('lasurvey.head_code') }}">{{ isset($answer_row_col) ? $answer_row_col->code : '' }}</textarea>
                                                                                                        <textarea name="answer_name[{{ $cate_key }}][{{ $ques_key }}][]" class="form-control w-100" placeholder="{{ trans('lasurvey.heading') }}">{{ isset($answer_row_col) ? $answer_row_col->name : '' }}</textarea>
                                                                                                    </div>
                                                                                                </th>
                                                                                                @if(isset($answers_col))
                                                                                                    @php
                                                                                                        $col_key = 0;
                                                                                                    @endphp
                                                                                                    @foreach($answers_col as $answer)
                                                                                                        <th class="matrix-col-item-{{ $cate_key .'-'. $ques_key }} col-item-{{ $col_key }}" data-ans_key="{{ $col_key }}">
                                                                                                            <input name="is_row[{{ $cate_key }}][{{ $ques_key }}][]" type="hidden" value="0">
                                                                                                            <input name="answer_id[{{ $cate_key }}][{{ $ques_key }}][]" type="hidden" value="{{ $answer->id }}">
                                                                                                            <div class="input-group">
                                                                                                                <textarea name="answer_code[{{ $cate_key }}][{{ $ques_key }}][]" class="form-control w-100 answer_code" data-cate_key="{{ $cate_key }}" data-ques_key="{{ $ques_key }}" placeholder="{{ trans('lasurvey.answer_code') }}">{{ $answer->code }}</textarea>

                                                                                                                <textarea name="answer_name[{{ $cate_key }}][{{ $ques_key }}][]" class="form-control w-100" placeholder="{{ trans('lasurvey.answer_name') }}">{{ $answer->name }}</textarea>

                                                                                                                <a href="javascript:void(0)" class="btn btn-remove-col-matrix text-center w-100" id="del-answer-col" data-ans_id="{{ $answer->id }}" data-ans_key="{{ $col_key }}"> <i class="fa fa-trash"></i> </a>
                                                                                                            </div>
                                                                                                        </th>

                                                                                                        @php
                                                                                                            $col_key += 1;
                                                                                                        @endphp
                                                                                                    @endforeach
                                                                                                    @php
                                                                                                        $col_key = 0;
                                                                                                    @endphp
                                                                                                @endif
                                                                                            </tr>
                                                                                            @if(isset($answers_row))
                                                                                                @php
                                                                                                    $row_key = 0;
                                                                                                @endphp
                                                                                                @foreach($answers_row as $answer)
                                                                                                    <tr class="matrix-row-content" data-ans_key="{{ $row_key }}">
                                                                                                        <th>
                                                                                                            <input name="is_row[{{ $cate_key }}][{{ $ques_key }}][]" type="hidden" value="1">
                                                                                                            <input name="answer_id[{{ $cate_key }}][{{ $ques_key }}][]" type="hidden" value="{{ $answer->id }}">
                                                                                                            <div class="input-group">
                                                                                                                <textarea name="answer_code[{{ $cate_key }}][{{ $ques_key }}][]" class="form-control w-100 answer_code" data-cate_key="{{ $cate_key }}" data-ques_key="{{ $ques_key }}" placeholder="{{ trans('lasurvey.answer_code') }}">{{ $answer->code }}</textarea>

                                                                                                                <textarea name="answer_name[{{ $cate_key }}][{{ $ques_key }}][]" class="form-control w-100" placeholder="{{ trans('lasurvey.answer_name') }}">{{ $answer->name }}</textarea>

                                                                                                                <a href="javascript:void(0)" class="btn btn-remove w-100 justify-content-center" id="del-answer-row" data-ans_id="{{ $answer->id }}"> <i class="fa fa-trash"></i> </a>
                                                                                                            </div>
                                                                                                        </th>
                                                                                                        @if(isset($answers_col))
                                                                                                            @php
                                                                                                                $col_key = 0;
                                                                                                            @endphp
                                                                                                            @foreach($answers_col as $answer_col)
                                                                                                                @php
                                                                                                                    $matrix_anser_code = $question->answers_matrix->where('answer_row_id', '=', $answer->id)->where('answer_col_id', '=', $answer_col->id)->first();
                                                                                                                @endphp
                                                                                                                <th class="col-item-{{ $col_key }}">
                                                                                                                    <textarea name="answer_matrix_code[{{ $cate_key }}][{{ $ques_key }}][{{ $row_key }}][{{ $col_key }}]" class="form-control" placeholder="">{{ isset($matrix_anser_code) ? $matrix_anser_code->code : '' }}</textarea>
                                                                                                                </th>
                                                                                                                @php
                                                                                                                    $col_key += 1;
                                                                                                                @endphp
                                                                                                            @endforeach
                                                                                                            @php
                                                                                                                $col_key = 0;
                                                                                                            @endphp
                                                                                                        @endif
                                                                                                    </tr>

                                                                                                    @php
                                                                                                        $row_key += 1;
                                                                                                    @endphp
                                                                                                @endforeach

                                                                                                @php
                                                                                                    $row_key = 0;
                                                                                                @endphp
                                                                                            @endif
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                        @else
                                                                            @php
                                                                                $answers = $question->answers->where('is_row', '=', 1);
                                                                            @endphp
                                                                            <div class="input-question-{{ $cate_key }}-answer-{{ $ques_key }}">
                                                                                @if(isset($answers))
                                                                                    @foreach($answers as $ans_key => $answer)
                                                                                        <div class="item-answer" data-ans_key="{{ $ans_key }}">
                                                                                            <div class="form-group row px-0">
                                                                                                <div class="{{ $question->type == 'choice' ? 'col-11' : 'col-12' }}">
                                                                                                    <input name="is_row[{{ $cate_key }}][{{ $ques_key }}][]" type="hidden" value="1">
                                                                                                    <input name="answer_id[{{ $cate_key }}][{{ $ques_key }}][]" type="hidden" value="{{ $answer->id }}">

                                                                                                    <div class="input-group">
                                                                                                        <textarea name="answer_code[{{ $cate_key }}][{{ $ques_key }}][]" class="p-1 w-5 answer_code" placeholder="-- {{ trans('lasurvey.answer_code') }} --">{{ $answer->code }}</textarea>

                                                                                                        <textarea name="answer_name[{{ $cate_key }}][{{ $ques_key }}][]" class="form-control" placeholder="-- {{ trans('lasurvey.answer_name') }} {{ $ans_key + 1 }} --">{{ $answer->name }}</textarea>

                                                                                                        @if($question->type == 'rank_icon')
                                                                                                            <textarea name="answer_icon[{{ $cate_key }}][{{ $ques_key }}][{{ $ans_key }}]" class="input_emoji input_emoji_{{ $cate_key }}{{ $ques_key }}{{ $ans_key }} w-5" placeholder="-- Icon --">{{ $answer->icon }}</textarea>
                                                                                                        @endif

                                                                                                        <a href="javascript:void(0)" class="btn btn-remove align-items-center" id="del-answer" data-ans_id="{{ $answer->id }}"> <i class="fa fa-trash"></i></a>
                                                                                                    </div>
                                                                                                </div>
                                                                                                @if ($question->type == 'choice')
                                                                                                <div class="col-1 p-0 d-flex align-items-center">
                                                                                                    <div class="form-check">
                                                                                                        <input name="is_text[{{ $cate_key }}][{{ $ques_key }}][{{ $ans_key }}]" value="{{ $answer->is_text }}" {{ $answer->is_text == 1 ? 'checked' : '' }} id="check-answer{{ $cate_key }}{{ $ques_key }}{{ $ans_key }}" type="checkbox" class="form-check-input check-answer">
                                                                                                        <label class="form-check-label" for="check-answer{{ $cate_key }}{{ $ques_key }}{{ $ans_key }}">
                                                                                                            {{ trans('lasurvey.enter_text') }}
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </div>
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                    @endforeach
                                                                                @endif
                                                                            </div>
                                                                        @endif

                                                                        <hr>
                                                                        <div>
                                                                            @if ($question->type == 'choice' || $question->type == 'matrix')
                                                                            <span class="ml-3" id="multi_choose{{ $cate_key }}{{ $ques_key }}">
                                                                                <input class="form-check-input check-multiples" name="multiple[{{ $cate_key }}][{{ $ques_key }}]" value="{{ $question->multiple }}" {{ $question->multiple == 1 ? 'checked' : '' }} type="checkbox" id="check-multiples{{ $cate_key }}{{ $ques_key }}" data-cate_key="{{ $cate_key }}" data-ques_key="{{ $ques_key }}">
                                                                                <label class="form-check-label" for="check-multiples{{ $cate_key }}{{ $ques_key }}">
                                                                                    {{ trans('lasurvey.multi_choose') }}
                                                                                </label>
                                                                            </span>
                                                                            @endif
                                                                            @if(!in_array($question->type, ['essay', 'time']))
                                                                                <a class="float-right" id="btn-question-answer" data-cate_key="{{ $cate_key }}" data-ques_key="{{ $ques_key }}">
                                                                                    <i class="fa fa-plus"></i> {{ trans('lasurvey.add_answer') }}
                                                                                </a>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <a class="btn mt-2" id="btn-question" data-cate_key="{{ $cate_key }}"><i class="fa fa-plus-circle"></i> {{ trans('lasurvey.add_question') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="col-md-12 mt-2">
                            <a class="btn" id="btn-category"><i class="fa fa-plus-circle"></i> {{ trans('lasurvey.category') }} </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <template id="category-template">
        <div class="item-category mt-2" data-cate_key="{cate_key}">
            <div class="card">
                <div class="card-header bg-info">
                    <input name="category_id[]" type="hidden" value="">

                    <div class="input-group">
                        <textarea name="category_name[]" class="form-control" placeholder="-- {{ trans('lasurvey.category') }} --" required></textarea>
                        <a href="javascript:void(0)" class="btn btn-remove align-items-center" id="del-category" data-cate_id=""><i class="fa fa-trash"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="input-question-{cate_key}"></div>
                </div>
                <div class="card-footer">
                    <a class="btn mt-2" id="btn-question" data-cate_key="{cate_key}"><i class="fa fa-plus-circle"></i> {{ trans('lasurvey.add_question') }}</a>
                </div>
            </div>
        </div>
    </template>

    <template id="question-template">
        <div class="item-question" data-ques_key="{ques_key}">
            <div class="card">
                <div class="card-header">
                    <div class="row px-0">
                        <div class="col-9 pr-0">
                            <input name="question_id[{cate_key}][]" type="hidden" value="">

                            <div class="input-group">
                                <input name="num_order[{cate_key}][{ques_key}]" class="p-1 custom-num-order" type="text" value="{index_question}">

                                <textarea name="question_code[{cate_key}][]" class="p-1 w-5 question_code" data-cate_key="{cate_key}" placeholder="{{ trans('lasurvey.question_code') }} {index_question}"></textarea>
                                <textarea name="question_name[{cate_key}][]" class="form-control" placeholder="-- {{ trans('lasurvey.question') }} {index_question} --" required></textarea>
                                <a href="javascript:void(0)" class="btn btn-remove align-items-center" id="del-question" data-ques_id=""><i class="fa fa-trash"></i></a>
                            </div>
                            <div class="obligatory mt-2">
                                <input type="checkbox" class="cursor_pointer" name="obligatory[{cate_key}][{ques_key}]" id="obligatory_{cate_key}_{ques_key}">
                                <label class="mb-0 cursor_pointer" for="obligatory_{cate_key}_{ques_key}">{{ trans('latraining.obligatory') }}</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="d-flex align-items-center">
                                <select name="type[{cate_key}][{ques_key}]" id="ques_type_{cate_key}_{ques_key}" class="form-control select2 ques_type" data-placeholder="" data-cate_key="{cate_key}" data-ques_key="{ques_key}">
                                    <option value="" readonly>{{ trans('lasurvey.question_type') }}</option>
                                    <option value="choice" selected>{{ trans('lasurvey.choice') }}</option>
                                    <option value="essay">{{ trans('lasurvey.essay') }}</option>
                                    <option value="text">{{ trans('lasurvey.text') }}</option>
                                    <option value="matrix">{{ trans('lasurvey.matrix') }}</option>
                                    <option value="matrix_text">{{ trans('lasurvey.matrix_text') }}</option>
                                    <option value="dropdown">{{ trans('lasurvey.dropdown') }}</option>
                                    <option value="sort">{{ trans('lasurvey.sort') }}</option>
                                    <option value="percent">{{ trans('lasurvey.percent') }}</option>
                                    <option value="number">{{ trans('lasurvey.number') }}</option>
                                    <option value="time">{{ trans('lasurvey.time') }}</option>
                                    <option value="rank">Đánh giá mức độ</option>
                                    <option value="rank_icon"> Đánh giá icon</option>
                                </select>
                                <span class="btn view_question" id="view_question_{cate_key}_{ques_key}" data-ques_type="choice" data-multi="0">
                                    <i class="fa fa-eye"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="input-question-{cate_key}-answer-{ques_key}">
                        <div class="item-answer" data-ans_key="0">
                            <div class="form-group row px-0">
                                <div class="col-11">
                                    <input name="is_row[{cate_key}][{ques_key}][]" type="hidden" value="1">
                                    <input name="answer_id[{cate_key}][{ques_key}][]" type="hidden" value="">

                                    <div class="input-group">
                                        <textarea name="answer_code[{cate_key}][{ques_key}][]" class="p-1 w-5 answer_code" data-cate_key="{cate_key}" data-ques_key="{ques_key}" placeholder="{{ trans('lasurvey.answer_code') }} 1"></textarea>
                                        <textarea name="answer_name[{cate_key}][{ques_key}][]" class="form-control" placeholder="-- {{ trans('lasurvey.answer_name') }} 1 --"></textarea>
                                        <a href="javascript:void(0)" class="btn btn-remove align-items-center" id="del-answer" data-ans_id=""> <i class="fa fa-trash"></i></a>
                                    </div>
                                </div>
                                <div class="col-1 p-0 d-flex align-items-center">
                                    <div class="form-check">
                                        <input name="is_text[{cate_key}][{ques_key}][0]" value="0" id="check-answer{cate_key}{ques_key}0" type="checkbox" class="form-check-input check-answer">
                                        <label class="form-check-label" for="check-answer{cate_key}{ques_key}0">
                                            {{ trans('lasurvey.enter_text') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="btn-question-answer-matrix">
                        <div class="form-group row px-0">
                            <div class="col-6">
                                <a class="" id="btn-question-answer-row" data-cate_key="{cate_key}" data-ques_key="{ques_key}">
                                    <i class="fa fa-plus"></i> {{ trans('lasurvey.add_row') }}
                                </a>
                            </div>
                            <div class="col-6 text-right">
                                <a class="" id="btn-question-answer-col" data-cate_key="{cate_key}" data-ques_key="{ques_key}">
                                    <i class="fa fa-plus"></i> {{ trans('lasurvey.add_col') }}
                                </a>
                            </div>
                        </div>
                        <p class="table-matrix-{cate_key}-{ques_key}">

                        </p>
                    </div>
                    <hr>
                    <div>
                        <span class="ml-3" id="multi_choose{cate_key}{ques_key}">
                            <input class="form-check-input check-multiples" name="multiple[{cate_key}][{ques_key}]" value="0" type="checkbox" id="check-multiples{cate_key}{ques_key}" data-cate_key="{cate_key}" data-ques_key="{ques_key}">
                            <label class="form-check-label" for="check-multiples{cate_key}{ques_key}">
                                {{ trans('lasurvey.multi_choose') }}
                            </label>
                        </span>
                        <a class="float-right" id="btn-question-answer" data-cate_key="{cate_key}" data-ques_key="{ques_key}">
                            <i class="fa fa-plus"></i> {{ trans('lasurvey.add_answer') }}
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </template>

    <template id="answer-template">
        <div class="item-answer" data-ans_key="{ans_key}">
            <div class="form-group row px-0">
                <div class="col-11 d-flex align-items-center">
                    <input name="is_row[{cate_key}][{ques_key}][]" type="hidden" value="1">
                    <input name="answer_id[{cate_key}][{ques_key}][]" type="hidden" value="">

                    <div class="input-group">
                        <textarea name="answer_code[{cate_key}][{ques_key}][]" class="p-1 w-5 answer_code" data-cate_key="{cate_key}" data-ques_key="{ques_key}" placeholder="{{ trans('lasurvey.answer_code') }} {index_answer}"></textarea>
                        <textarea name="answer_name[{cate_key}][{ques_key}][]" class="form-control" placeholder="-- {{ trans('lasurvey.answer_name') }} {index_answer} --"></textarea>
                        <textarea name="answer_icon[{cate_key}][{ques_key}][{ans_key}]" id="input_emoji_{cate_key}{ques_key}{ans_key}" class="input_emoji_{cate_key}{ques_key}{ans_key} w-5" placeholder="Icon"></textarea>
                        <a href="javascript:void(0)" class="btn btn-remove align-items-center" id="del-answer" data-ans_id=""> <i class="fa fa-trash"></i></a>
                    </div>
                </div>
                <div class="col-1 p-0 d-flex align-items-center">
                    <div class="form-check">
                        <input name="is_text[{cate_key}][{ques_key}][{ans_key}]" value="0" id="check-answer{cate_key}{ques_key}{ans_key}" type="checkbox" class="form-check-input check-answer">
                        <label class="form-check-label" for="check-answer{cate_key}{ques_key}{ans_key}">
                            {{ trans('lasurvey.enter_text') }}
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="answer-other-template">
        <div class="item-answer" data-ans_key="{ans_key}">
            <div class="form-group row px-0">
                <div class="col-12 d-flex align-items-center">
                    <input name="is_row[{cate_key}][{ques_key}][]" type="hidden" value="1">
                    <input name="answer_id[{cate_key}][{ques_key}][]" type="hidden" value="">
                    <input name="is_text[{cate_key}][{ques_key}][{ans_key}]" type="hidden" value="0">

                    <div class="input-group">
                        <textarea name="answer_code[{cate_key}][{ques_key}][]" class="p-1 w-5 answer_code" data-cate_key="{cate_key}" data-ques_key="{ques_key}" placeholder="{{ trans('lasurvey.answer_code') }} {index_answer}"></textarea>
                        <textarea name="answer_name[{cate_key}][{ques_key}][]" class="form-control" placeholder="-- {{ trans('lasurvey.answer_name') }} {index_answer} --"></textarea>
                        <textarea name="answer_icon[{cate_key}][{ques_key}][{ans_key}]" id="input_emoji_{cate_key}{ques_key}{ans_key}" class="input_emoji_{cate_key}{ques_key}{ans_key} w-5" placeholder="Icon"></textarea>
                        <a href="javascript:void(0)" class="btn btn-remove align-items-center" id="del-answer" data-ans_id=""> <i class="fa fa-trash"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="table-matrix-template">
        <table class="table table-bordered" id="table-matrix-{cate_key}-{ques_key}">
            <tr class="matrix-row-title">
                <th>
                    <input name="is_row[{cate_key}][{ques_key}][]" type="hidden" value="10">
                    <input name="answer_id[{cate_key}][{ques_key}][]" type="hidden" value="">
                    <div class="input-group mt-3">
                        <textarea name="answer_code[{cate_key}][{ques_key}][]" class="form-control w-100 answer_code" data-cate_key="{cate_key}" data-ques_key="{ques_key}" placeholder="{{ trans('lasurvey.head_code') }}"></textarea>
                        <textarea name="answer_name[{cate_key}][{ques_key}][]" class="form-control w-100" placeholder="{{ trans('lasurvey.heading') }}"></textarea>
                    </div>
                </th>
                @for($i = 0; $i < 4; $i++)
                    <th class="matrix-col-item-{cate_key}-{ques_key} col-item-{{ $i }}" data-ans_key="{{ $i }}">
                        <input name="is_row[{cate_key}][{ques_key}][]" type="hidden" value="0">
                        <input name="answer_id[{cate_key}][{ques_key}][]" type="hidden" value="">
                        <div class="input-group">
                            <textarea name="answer_code[{cate_key}][{ques_key}][]" class="form-control w-100 answer_code" data-cate_key="{cate_key}" data-ques_key="{ques_key}" placeholder="{{ trans('lasurvey.answer_code') }}"></textarea>
                            <textarea name="answer_name[{cate_key}][{ques_key}][]" class="form-control w-100" placeholder="{{ trans('lasurvey.answer_name') }}"></textarea>
                            <a href="javascript:void(0)" class="btn btn-remove-col-matrix text-center w-100" id="del-answer-col" data-ans_id="" data-ans_key="{{ $i }}"> <i class="fa fa-trash"></i> </a>
                        </div>
                    </th>
                @endfor
            </tr>
            <tr class="matrix-row-content" data-ans_key="0">
                <th>
                    <input name="is_row[{cate_key}][{ques_key}][]" type="hidden" value="1">
                    <input name="answer_id[{cate_key}][{ques_key}][]" type="hidden" value="">
                    <div class="input-group">
                        <textarea name="answer_code[{cate_key}][{ques_key}][]" class="form-control w-100 answer_code" data-cate_key="{cate_key}" data-ques_key="{ques_key}" placeholder="{{ trans('lasurvey.answer_code') }}"></textarea>
                        <textarea name="answer_name[{cate_key}][{ques_key}][]" class="form-control w-100" placeholder="{{ trans('lasurvey.answer_name') }}"></textarea>
                        <a href="javascript:void(0)" class="btn btn-remove w-100 text-center justify-content-center" id="del-answer-row" data-ans_id=""> <i class="fa fa-trash"></i> </a>
                    </div>
                </th>
                @for($i = 0; $i < 4; $i++)
                    <th class="col-item-{{ $i }}">
                        <textarea name="answer_matrix_code[{cate_key}][{ques_key}][0][{{ $i }}]" class="form-control" placeholder=""></textarea>
                    </th>
                @endfor
            </tr>
        </table>
    </template>
</div>

<script>
    $('#course').on('click', function(){
        if($(this).is(':checked')){
            $(this).val(1);
        }else{
            $(this).val(0);
        }
    });

    var remove_category = '{{ route('module.survey.template.remove_category') }}';
    var remove_question = '{{ route('module.survey.template.remove_question') }}';
    var remove_answer = '{{ route('module.survey.template.remove_answer') }}';
    var modal_view_question = '{{ route('module.survey.template.modal_view_question') }}';
    var update_num_order = '{{ route('module.survey.template.update_num_order', ['id' => $model->id??0]) }}';

    var answer_code_lang = '{{ trans('lasurvey.answer_code') }}';
    var answer_name_lang = '{{ trans('lasurvey.answer_name') }}';
    var content_lang = '{{ trans('lasurvey.content') }}';
    var date_format_lang = '{{ trans('lasurvey.date_format') }}';

    $(".input_emoji").emojioneArea({
        pickerPosition: "bottom",
        hidePickerOnBlur: false,
        search: false,
    });
</script>
<script src="{{ asset('styles/module/survey/js/template.js')}}"></script>
@stop
