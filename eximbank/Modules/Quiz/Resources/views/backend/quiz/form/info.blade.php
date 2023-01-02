<form method="post" action="{{ route('module.quiz.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{ $model->id }}">
    <input type="hidden" name="is_unit" value="{{ $is_unit }}">
    <input type="hidden" name="course_id" value="{{ $course_id }}">
    <input type="hidden" name="course_type" value="{{ $course_type }}">
    <input type="hidden" name="quiz_type_by_offline" value="{{ $quiz_type_by_offline ? $quiz_type_by_offline : $model->quiz_type_by_offline }}">

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
                            <option value="{{ $template->id }}" {{ $model->quiz_template_id == $template->id ? 'selected' : '' }}> {{ $template->name }}{{ $template->is_open==0?" (Đã tắt)":"" }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.quiz_code')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <input name="code" type="text" class="form-control" value="{{ $model->code }}" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.quiz_name')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="type_id">{{ trans('backend.quiz_type') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <select name="type_id" id="type_id" class="form-control select2" data-placeholder="-- {{ trans('backend.quiz_type') }} --" required>
                        <option value=""></option>
                        @foreach($quiz_type as $type)
                            <option value="{{ $type->id }}" {{ $type->id == $model->type_id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('latraining.quiz_location') }}</label>
                </div>
                <div class="col-md-9">
                    <textarea name="quiz_location" type="text" class="form-control" rows="4">{{ $model->quiz_location }}</textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('backend.content') }} </label>
                </div>
                <div class="col-md-9">
                    <textarea name="description" type="text" class="form-control" rows="4">{{ $model->description }}</textarea>
                </div>
            </div>

            {{-- ĐƠN VỊ TẠO ĐỀ --}}
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('laquiz.unit_create_quiz') }} </label>
                </div>
                <div class="col-md-9">
                    <input name="unit_create_quiz" type="text" class="form-control d-inline-block" value="{{ $model->unit_create_quiz }}">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.time_quiz')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="limit_time" required type="text" class="is-number w-75 form-control d-inline-block date-custom" autocomplete="off" value="{{ $model->limit_time }}"> {{trans('backend.minutes')}}
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.benchmarks')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="pass_score" required type="text" class="is-number w-75 form-control d-inline-block date-custom" autocomplete="off" value="{{ $model->pass_score }}"> {{trans('backend.score')}}
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.maximum_score')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="max_score" required type="text" class="is-number form-control w-75 d-inline-block date-custom" autocomplete="off" value="{{ $model->max_score }}"> {{trans('backend.score')}}
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.number_question_page')}}<span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="questions_perpage" required type="number" class="is-number form-control w-75 d-inline-block date-custom" autocomplete="off" value="{{ $model->questions_perpage }}">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.required_webcam')}}</label>
                </div>
                <div class="col-md-6">
                    <input name="webcam_require" type="checkbox" value="{{ $model->webcam_require }}" @if($model->webcam_require == 1) checked @endif>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.number_shots')}}</label>
                </div>
                <div class="col-md-6">
                    <input name="times_shooting_webcam" type="text" value="{{ $model->times_shooting_webcam }}" class="form-control is-number w-75" @if($model->webcam_require != 1) disabled @endif>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.require_answer')}}</label>
                </div>
                <div class="col-md-6">
                    <input name="question_require" type="checkbox" value="{{ $model->question_require }}" @if($model->question_require == 1) checked @endif>
                </div>
            </div>
            <div class="form-group row">
                {{--  <div class="col-sm-3 control-label">
                    <label>{{trans('backend.number_replies')}}</label>
                </div>  --}}
                <div class="col-md-6">
                    <input name="times_shooting_question" type="hidden" value="{{ $model->times_shooting_question ?? 0 }}" class="form-control is-number w-75">
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-right">
                <div class="btn-group act-btns">
                    @canany(['quiz-create', 'quiz-edit'])
                        @if (!isset($result))
                            <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                        @endif
                    @endcanany
                    <a href="{{ route('module.quiz.manager') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                </div>
            </div>
            <p></p>
            <div class="form-group">
                <label class="w-100">
                    {{trans('backend.picture')}} (300 x 200)
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
                <input type="hidden" name="quiz_type" id="quiz_type" value="{{ $type_quiz }}">

                <label for="exam_form">{{trans('backend.quiz_form')}}: </label>
                @if ($model->quiz_type == 1 || $type_quiz == 1)
                    Online
                @elseif ($model->quiz_type == 2 || $type_quiz == 2)
                    {{trans('latraining.offline')}}
                @else
                    {{trans('backend.independent_exam')}}
                @endif
                {{--  <select name="quiz_type" id="quiz_type" class="form-control select2 disable" data-placeholder="{{trans('latraining.choose_form')}}" required>
                    <option value=""></option>
                    <option value="1" {{ $model->quiz_type == 1 || $type_quiz == 1 ? 'selected' : '' }}> Online</option>
                    <option value="2" {{ $model->quiz_type == 2 || $type_quiz == 2 ? 'selected' : '' }}> {{trans('latraining.offline')}}</option>
                    <option value="3" {{ $model->quiz_type == 3 || $type_quiz == 3 ? 'selected' : '' }}> {{trans('backend.independent_exam')}}</option>
                </select>  --}}
            </div>

            <div class="form-group">
                <label for="shuffle_question">{{trans('backend.shuffled_question')}}</label>
                <select name="shuffle_question" id="shuffle_question" class="form-control select2-default">
                    <option value="0" {{ ($model->shuffle_question == 0) ? 'selected' : '' }}> {{trans("backend.disable")}}</option>
                    <option value="1" {{ ($model->shuffle_question == 1) ? 'selected' : '' }}> {{trans("backend.enable")}}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="shuffle_answers">{{trans('backend.shuffle_answer')}}</label>
                <select name="shuffle_answers" id="shuffle_answers" class="form-control select2-default">
                    <option value="0" {{ ($model->shuffle_answers == 0) ? 'selected' : '' }}> {{trans("backend.disable")}}</option>
                    <option value="1" {{ ($model->shuffle_answers == 1) ? 'selected' : '' }}> {{trans("backend.enable")}}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="max_attempts">{{trans('backend.number_test')}} <span class="text-danger">*</span></label>
                <select name="max_attempts" id="max_attempts" class="form-control select2-default">
                    <option value="0" {{ ($model->max_attempts == 0) ? 'selected' : '' }}> {{trans('backend.unlimited')}}</option>
                    @for($i = 1; $i <= 10; $i++)
                        @php $ii = $i < 10 ? '0'. $i : $i @endphp
                        <option value="{{$ii}}" {{ ($model->max_attempts == $ii) ? 'selected' : '' }}> {{$ii}}</option>
                    @endfor
                </select>
            </div>

            <div class="form-group">
                <label for="grade_methor">{{trans('backend.scoring_method')}} <span class="text-danger">*</span></label>
                <select name="grade_methor" id="grade_methor" class="form-control select2-default">
                    <option value="1" {{ ($model->grade_methor == 1) ? 'selected' : '' }}> {{trans('backend.highest_time')}}</option>
                    <option value="2" {{ ($model->grade_methor == 2) ? 'selected' : '' }}> {{trans('backend.medium_score')}}</option>
                    <option value="3" {{ ($model->grade_methor == 3) ? 'selected' : '' }}> {{trans('backend.first_time_score')}}</option>
                    <option value="4" {{ ($model->grade_methor == 4) ? 'selected' : '' }}> {{trans('backend.last_point')}}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="show_name">{{ trans('latraining.view_name_when_grading') }}</label>
                <select name="show_name" id="show_name" class="form-control select2-default">
                    <option value="0" {{ ($model->show_name == 0) ? 'selected' : '' }}> {{trans("backend.no")}}</option>
                    <option value="1" {{ ($model->show_name == 1) ? 'selected' : '' }}> {{trans("backend.yes")}}</option>
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
                        <label class="form-check-label" for="paper_exam">{{trans('backend.paper_exam')}}</label>
                        <input type="hidden" name="paper_exam" class="check-paper-exam" value="{{ $model->paper_exam ? $model->paper_exam : '0' }}">
                    </div>
                </div>
            </div>

            <div class="form-group row wrapped_quiz_not_register">
                <div class="col-sm-12">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="quiz_not_register" {{ $model->quiz_not_register == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="quiz_not_register">{{ trans('laquiz.quiz_not_register') }} </label>
                        <input type="hidden" name="quiz_not_register" class="check-quiz-not-register" value="{{ $model->quiz_not_register ? $model->quiz_not_register : '0' }}">
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
<script>
    $('#full_screen').on('click', function () {
        if ($(this).is(':checked')) {
            $(this).closest('.form-check').find('.check-full-screen').val(1);
        } else {
            $(this).closest('.form-check').find('.check-full-screen').val(0);
        }
    });

    $('#quiz_not_register').on('click', function () {
        if ($(this).is(':checked')) {
            $(this).closest('.form-check').find('.check-quiz-not-register').val(1);
        } else {
            $(this).closest('.form-check').find('.check-quiz-not-register').val(0);
        }
    });

    $('#paper_exam').on('click', function () {
        if ($(this).is(':checked')) {
            $(this).closest('.form-check').find('.check-paper-exam').val(1);
        } else {
            $(this).closest('.form-check').find('.check-paper-exam').val(0);
        }
    });

    $('input[name=webcam_require]').on('click', function () {
        if ($(this).is(':checked')) {
            $(this).val(1);
            $('input[name=times_shooting_webcam]').prop('disabled', false);
        } else {
            $(this).val(0);
            $('input[name=times_shooting_webcam]').prop('disabled', true);
        }
    });
    $('input[name=question_require]').on('click', function () {
        if ($(this).is(':checked')) {
            $(this).val(1);
            $('input[name=times_shooting_question]').val(1);
        } else {
            $(this).val(0);
            $('input[name=times_shooting_question]').val(0);
        }
    });

    $('#quiz_template_id').on('change',function () {
        var $this = $(this);
        $.ajax({
            type: 'POST',
            url: '{{ route('module.quiz.load.exam.template') }}',
            dataType: 'json',
            data: {
                exam_template_id:$this.val()
            }
        }).done(function(result) {
            var data = result.data;
            var attemp = (data.attempts < 10 && data.attempts > 0) ? '0'+(data.attempts) : data.attempts;
            if (result.status=='success'){
                var attemps = (data.max_attempts < 10 && data.max_attempts > 0) ? '0'+ data.max_attempts : data.max_attempts
                $('input[name=code]').val(data.code);
                $('input[name=name]').val(data.name);
                $('textarea[name=description]').val(data.description);
                $('input[name=limit_time]').val(data.limit_time);
                $('input[name=pass_score]').val(data.pass_score);
                $('input[name=max_score]').val(data.max_score);
                $('input[name=questions_perpage]').val(data.questions_perpage);
                $('select[name=quiz_type]').val(data.quiz_type).trigger('change');
                $('select[name=max_attempts]').val(attemps).trigger('change');
                $('select[name=shuffle_question]').val(data.shuffle_question).trigger('change');
                $('select[name=shuffle_answers]').val(data.shuffle_answers).trigger('change');
                $('select[name=attempts]').val(attemp).trigger('change');
                $('select[name=grade_methor]').val(data.grade_methor).trigger('change');
                $('select[name=type_id]').val(data.type_id).trigger('change');
                $('input[name=paper_exam]').val(data.paper_exam);
                $('#paper_exam').attr('checked',data.paper_exam==1?true:false);
                $('input[name=img]').val(data.img);
                $("#image-review").html('<img src="'+ data.img_view +'" class="w-50">');
                $('input[name=webcam_require]').val(data.webcam_require);
                $('input[name=question_require]').val(data.question_require);
                $('input[name=times_shooting_webcam]').val(data.times_shooting_webcam);
                $('input[name=times_shooting_question]').val(data.times_shooting_question);
                $('input[name=webcam_require]').attr('checked',data.webcam_require==1?true:false);
                $('input[name=question_require]').attr('checked',data.question_require==1?true:false);
                $('input[name=times_shooting_webcam]').attr('disabled',data.webcam_require==1?false:true);
                $('input[name=times_shooting_question]').attr('disabled',data.question_require==1?false:true);
            }
        }).fail(function(data) {
            return false;
        });
    });

    $("#select-image").on('click', function () {
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'image'}, function (url, path) {
            $("#image-review").html('<img src="'+ path +'" class="w-50">');
            $("#image-select").val(path);
        });
    });


    if($('#quiz_type').val() == 3) {
        $('.wrapped_quiz_not_register').show();
    } else {
        $('.wrapped_quiz_not_register').hide();
    }
    $('#quiz_type').on('change', function() {
        if($(this).val() == 3) {
            $('.wrapped_quiz_not_register').show();
        } else {
            $('.wrapped_quiz_not_register').hide();
            $('#quiz_not_register').attr('checked', false)
            $('.check-quiz-not-register').val(0)
        }
    })
</script>
