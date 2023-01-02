<form method="post" action="{{ route('module.offline.quiz.save', ['course_id' => $course_id]) }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{ $model->id }}">
    <input type="hidden" name="quiz_type_by_offline" value="{{ $quiz_type_by_offline }}">

    <div class="row">
        <div class="col-md-9">
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('latraining.set_exam_questions') }}</label>
                </div>
                <div class="col-md-9">
                    <select name="quiz_template_id" id="quiz_template_id" class="form-control select2" data-placeholder="-- {{ trans('latraining.choose_set_exam_questions') }} --" {{ $result ? 'disabled' : '' }}>
                        <option value=""></option>
                        @foreach($quiz_template as $template)
                            <option value="{{ $template->id }}" {{ $model->quiz_template_id == $template->id ? 'selected' : '' }}> {{ $template->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.quiz_code')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <input name="code" type="text" class="form-control" value="{{ $model->code }}" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.quiz_name')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="type_id">{{ trans('latraining.quiz_type') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <select name="type_id" id="type_id" class="form-control select2" data-placeholder="-- {{ trans('latraining.quiz_type') }} --" required>
                        <option value=""></option>
                        @foreach($quiz_type as $type)
                            <option value="{{ $type->id }}" {{ $type->id == $model->type_id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('latraining.content') }} </label>
                </div>
                <div class="col-md-9">
                    <textarea name="description" type="text" class="form-control" rows="4">{{ $model->description }}</textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.time_quiz')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="limit_time" required type="text" class="is-number w-75 form-control d-inline-block date-custom" autocomplete="off" value="{{ $model->limit_time }}"> {{trans('latraining.minutes')}}
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.benchmarks')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="pass_score" required type="text" class="is-number w-75 form-control d-inline-block date-custom" autocomplete="off" value="{{ $model->pass_score }}"> {{trans('latraining.score')}}
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.maximum_score')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="max_score" required type="text" class="is-number form-control w-75 d-inline-block date-custom" autocomplete="off" value="{{ $model->max_score }}"> {{trans('latraining.score')}}
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.number_question_page')}}<span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="questions_perpage" required type="text" class="is-number form-control w-75 d-inline-block date-custom" autocomplete="off" value="{{ $model->questions_perpage }}">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.required_webcam')}}</label>
                </div>
                <div class="col-md-6">
                    <input name="webcam_require" type="checkbox" value="{{ $model->webcam_require }}" @if($model->webcam_require == 1) checked @endif>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.number_shots')}}</label>
                </div>
                <div class="col-md-6">
                    <input name="times_shooting_webcam" type="text" value="{{ $model->times_shooting_webcam }}" class="form-control is-number w-75" @if($model->webcam_require != 1) disabled @endif>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.require_answer')}}</label>
                </div>
                <div class="col-md-6">
                    <input name="question_require" type="checkbox" value="{{ $model->question_require }}" @if($model->question_require == 1) checked @endif>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.number_replies')}}</label>
                </div>
                <div class="col-md-6">
                    <input name="times_shooting_question" type="text" value="{{ $model->times_shooting_question }}" class="form-control is-number w-75" @if($model->question_require != 1) disabled @endif>
                </div>
            </div>

        </div>
        <div class="col-md-3">
            <div class="form-group text-right">
                <div class="btn-group act-btns">
                    @if(!$result)
                        <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                    @endif
                        <a href="{{ route('module.offline.quiz', ['course_id' => $course_id]) }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                </div>
            </div>
            <p></p>
            <div class="form-group">
                <label class="w-100">{{trans('latraining.picture')}} (300 x 200)
                    <a href="javascript:void(0)" id="select-image" class="float-right">{{trans('latraining.choose_picture')}}</a>
                </label>

                <div id="image-review">
                    @if($model->img)
                        <img src="{{ image_file($model->img) }}" alt="" class="w-50">
                    @endif
                </div>
                <input name="img" id="image-select" type="text" class="d-none" value="{{ $model->img }}">
            </div>

            <div class="form-group">
                <label for="shuffle_question">{{trans('latraining.shuffled_question')}}</label>
                <select name="shuffle_question" id="shuffle_question" class="form-control select2-default">
                    <option value="0" {{ ($model->shuffle_question == 0) ? 'selected' : '' }}> {{trans("latraining.disable")}}</option>
                    <option value="1" {{ ($model->shuffle_question == 1) ? 'selected' : '' }}> {{trans("latraining.enable")}}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="shuffle_answers">{{trans('latraining.shuffle_answer')}}</label>
                <select name="shuffle_answers" id="shuffle_answers" class="form-control select2-default">
                    <option value="0" {{ ($model->shuffle_answers == 0) ? 'selected' : '' }}> {{trans("latraining.disable")}}</option>
                    <option value="1" {{ ($model->shuffle_answers == 1) ? 'selected' : '' }}> {{trans("latraining.enable")}}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="max_attempts">{{trans('latraining.number_test')}} <span class="text-danger">*</span></label>
                <select name="max_attempts" id="max_attempts" class="form-control select2-default">
                    <option value="0" {{ ($model->max_attempts == 0) ? 'selected' : '' }}> {{trans('latraining.unlimited')}}</option>
                    @for($i = 1; $i <= 10; $i++)
                        @php $ii = $i < 10 ? '0'. $i : $i @endphp
                        <option value="{{$ii}}" {{ ($model->max_attempts == $ii) ? 'selected' : '' }}> {{$ii}}</option>
                    @endfor
                </select>
            </div>

            <div class="form-group">
                <label for="grade_methor">{{trans('latraining.scoring_method')}} <span class="text-danger">*</span></label>
                <select name="grade_methor" id="grade_methor" class="form-control select2-default">
                    <option value="1" {{ ($model->grade_methor == 1) ? 'selected' : '' }}> {{trans('latraining.highest_times')}}</option>
                    <option value="2" {{ ($model->grade_methor == 2) ? 'selected' : '' }}> {{trans('latraining.medium_score')}}</option>
                    <option value="3" {{ ($model->grade_methor == 3) ? 'selected' : '' }}> {{trans('latraining.first_time_score')}}</option>
                    <option value="4" {{ ($model->grade_methor == 4) ? 'selected' : '' }}> {{trans('latraining.last_point')}}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="show_name">{{trans('latraining.view_name_when_grading')}}</label>
                <select name="show_name" id="show_name" class="form-control select2-default">
                    <option value="0" {{ ($model->show_name == 0) ? 'selected' : '' }}> {{trans("latraining.no")}}</option>
                    <option value="1" {{ ($model->show_name == 1) ? 'selected' : '' }}> {{trans("latraining.yes")}}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="new_tab">{{trans('latraining.new_tab')}} <span class="text-danger">*</span></label>
                <select name="new_tab" id="new_tab" class="form-control select2-default">
                    <option value="0" {{ ($model->new_tab == 0) ? 'selected' : '' }}> {{trans('backend.unlimited')}}</option>
                    @for($i = 1; $i <= 10; $i++)
                        @php $ii = $i < 10 ? '0'. $i : $i @endphp
                        <option value="{{$ii}}" {{ ($model->new_tab == $ii) ? 'selected' : '' }}> {{ $ii }}</option>
                    @endfor
                </select>
            </div>

            <div class="form-group row">
                <div class="col-sm-12">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="paper_exam" {{ $model->paper_exam == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="paper_exam">{{trans('latraining.paper_exam')}}</label>
                        <input type="hidden" name="paper_exam" class="check-paper-exam" value="{{ $model->paper_exam ? $model->paper_exam : '0' }}">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-12">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="full_screen" {{ $model->full_screen == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="full_screen">{{ trans('laquiz.anti_cheat') }} (Full screen)</label>
                        <input type="hidden" name="full_screen" class="check-full-screen" value="{{ $model->full_screen ? $model->full_screen : '0' }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
