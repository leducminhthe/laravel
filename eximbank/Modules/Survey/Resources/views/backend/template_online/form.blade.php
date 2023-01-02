@extends('layouts.backend')

@section('page_title', $page_title)

@section('header')
    <style>
        /*#input-category .form-control{
            border: unset;
        }*/
        #input-category .btn-remove,
        #input-category th .btn-remove-col-matrix,
        #input-category th .btn-remove-row-matrix{
            display: none;
        }

        #input-category th:hover .btn-remove-col-matrix{
            display: block;
        }

        #input-category .item-category .input-group:hover .btn-remove,
        #input-category .item-question .input-group:hover .btn-remove,
        #input-category .item-answer .input-group:hover .btn-remove,
        #input-category th:hover .btn-remove-row-matrix{
            display: flex;
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
                'name' => trans('lamenu.survey_form_online'),
                'url' => route('module.survey.template_online')
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
<div role="main" id="survey_template_online">
    <form method="post" action="{{ route('module.survey.template_online.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['survey-template-create', 'survey-template-edit'])
                    <button type="submit" class="btn" id="save-template" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                    @endcanany
                    <a href="{{ route('module.survey.template') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                </div>
            </div>
        </div>
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="active"><a href="#base" role="tab" data-toggle="tab">{{ trans('lasurvey.info') }}</a></li>
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-sm-2 control-label">
                                    <label>{{ trans('lasurvey.template_name') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-8">
                                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 all_question">
                            @if ($model->id)
                                @foreach ($questions as $key => $question)
                                    <div class="row wrapped_question mb-3 question_id_{{ $question->id }}">
                                        <div class="col-10">
                                            <div class="form-group row">
                                                <div class="col-sm-2">
                                                    <div>
                                                        <label>{{ trans('latraining.question') }}</label>
                                                    </div>
                                                    <div class="d_flex_align">
                                                        <input type="hidden" name="question_id[]" value="{{ $question->id }}">
                                                        <input type="checkbox" name="multiple_answer_{{ ($key + 1) }}" value="1" {{ $question->multiple == 1 ? 'checked' : '' }}>
                                                        <span class="ml-1">{{ trans('lasurvey.multi_choose') }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-10">
                                                    <textarea name="question[]" id="" class="w-100 p-3 form-control" cols="2" rows="2" required>{{ $question->question }}</textarea>
                                                </div>
                                            </div>

                                            <div class="all_answer_{{ ($key + 1) }}">
                                                @foreach ($question->answer as $key_answer => $answer)
                                                    <div class="form-group row answer answer_id_{{ $answer->id }}">
                                                        <div class="col-sm-2">
                                                            <label>{{ trans('latraining.answer') .' '. ($key_answer + 1) }}</label>
                                                        </div>
                                                        <div class="col-md-10 wrapped_answer">
                                                            @if ($key_answer == 0)
                                                                <input type="hidden" name="answer_id_{{ ($key + 1) }}[]" value="{{ $answer->id }}">
                                                                <input name="answer_{{ ($key + 1) }}[]" type="text" class="form-control" value="{{ $answer->answer }}" required>
                                                            @else
                                                                <div class="input-group">
                                                                    <input type="hidden" name="answer_id_{{ ($key + 1) }}[]" value="{{ $answer->id }}">
                                                                    <input name="answer_{{ ($key + 1) }}[]" type="text" class="form-control" value="{{ $answer->answer }}" required>
                                                                    <div class="input-group-append">
                                                                        <button class="btn btn-outline-secondary" onclick="deleteAnswerAjax({{ $answer->id }})" type="button">
                                                                            <i class="fas fa-trash cursor_pointer"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <button type="button" class="btn" onclick="addAnswer({{ ($key + 1) }})">
                                                        <i class="fas fa-plus-circle"></i> {{ trans('latraining.add_answer') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($key != 0)
                                            <div class="col-2 text-right">
                                                <div class="add_answer">
                                                    <button type="button" onclick="deleteQuestionAjax({{ $question->id }})" class="btn">
                                                        <i class="fas fa-trash cursor_pointer"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="row wrapped_question mb-3">
                                    <div class="col-10">
                                        <div class="form-group row">
                                            <div class="col-sm-2">
                                                <div>
                                                    <label>{{ trans('latraining.question') }}</label>
                                                </div>
                                                <div class="d_flex_align">
                                                    <input type="checkbox" name="multiple_answer_1" id="" value="1"><span class="ml-1">{{ trans('lasurvey.multi_choose') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <textarea name="question[]" id="" class="w-100 p-3 form-control" cols="2" rows="2" required></textarea>
                                            </div>
                                        </div>

                                        <div class="all_answer_1">
                                            <div class="form-group row answer">
                                                <div class="col-sm-2">
                                                    <label>{{ trans('latraining.answer') }} 1</label>
                                                </div>
                                                <div class="col-md-10">
                                                    <input name="answer_1[]" type="text" class="form-control" value="" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <button type="button" class="btn" onclick="addAnswer(1)"><i class="fas fa-plus-circle"></i> {{ trans('latraining.add_answer') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-12 mt-3">
                            <button type="button" onclick="addQuestion()" class="btn" id="add_question"><i class="fa fa-plus-circle"></i> {{ trans('lasurvey.add_question') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function addQuestion() {
        var numItems = $('.wrapped_question').length;
        var html = `<div class="row wrapped_question wrapped_question_`+ (numItems + 1) +` mb-3">
                        <div class="col-10">
                            <div class="form-group row">
                                <div class="col-sm-2">
                                    <div>
                                        <label>{{ trans('latraining.question') }}</label>
                                    </div>
                                    <div class="d_flex_align">
                                        <input type="checkbox" name="multiple_answer_`+ (numItems + 1) +`" id="" value="1"><span class="ml-1">{{ trans('lasurvey.multi_choose') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <textarea name="question[]" id="" class="w-100 p-3 form-control" cols="2" rows="2" required></textarea>
                                </div>
                            </div>

                            <div class="all_answer_`+ (numItems + 1) +`">
                                <div class="form-group row answer">
                                    <div class="col-sm-2">
                                        <label>{{ trans('latraining.answer') }} 1</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input name="answer_`+ (numItems + 1) +`[]" name="name" type="text" class="form-control" value="" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <button type="button" class="btn" onclick="addAnswer(`+ (numItems + 1) +`)"><i class="fas fa-plus-circle"></i> {{ trans('latraining.add_answer') }}</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-2 text-right">
                            <div class="add_answer">
                                <button type="button" onclick="deleteQuestion(`+ (numItems + 1) +`)" class="btn">
                                    <i class="fas fa-trash cursor_pointer"></i>
                                </button>
                            </div>
                        </div>
                    </div>`;
        $('.all_question').append(html);
    }

    function deleteQuestion(id) {
        $('.wrapped_question_' + id).remove();
    }

    function addAnswer(id) {
        var numItems = $('.all_answer_' + id).find('.answer').length;
        var html = `<div class="form-group row answer answer_`+ (numItems + 1) +`">
                        <div class="col-sm-2">
                            <label>{{ trans('latraining.answer') }} `+ (numItems + 1) +`</label>
                        </div> 
                        <div class="col-10 wrapped_answer">
                            <div class="input-group">
                                <input name="answer_`+ id +`[]" type="text" class="form-control" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" onclick="deleteAnswer(`+ id +`,`+ (numItems + 1) +`)" type="button">
                                        <i class="fas fa-trash cursor_pointer"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>`;
        $('.all_answer_' + id).append(html);
    }

    function deleteAnswer(id, numItem) {
        $('.all_answer_' + id).find('.answer_' + numItem).remove();
    }

    function deleteAnswerAjax(answer_id) {
        $.ajax({
            type: 'POST',
            url : '{{ route('module.survey.template_online.remove_answer') }}',
            data : {
                answer_id: answer_id
            }
        }).done(function(data) {
            $('.answer_id_' + answer_id).remove();
            return false;
        }).fail(function(data) {
            return false;
        });
    }

    function deleteQuestionAjax(question_id) {
        $.ajax({
            type: 'POST',
            url : '{{ route('module.survey.template_online.remove_question') }}',
            data : {
                question_id: question_id
            }
        }).done(function(data) {
            $('.question_id_' + question_id).remove();
            return false;
        }).fail(function(data) {
            return false;
        });
    }
</script>
@stop
