@extends('layouts.app')

@section('page_title', trans('latraining.create_content_skills'))

@section('content')
    <div class="sa4d25">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="ibox-content forum-container">
                        <h2 class="st_title">
                            <a href="/">
                                <i class="uil uil-apps"></i>
                                <span>{{ trans('lamenu.home_page') }}</span>
                            </a>
                            <i class="uil uil-angle-right"></i>
                            <a href="{{ route('module.coaching.frontend') }}">Coaching</a>
                            @if ($coaching_teacher_user_id == profile()->user_id)
                            <i class="uil uil-angle-right"></i>
                            <a href="{{ route('module.coaching.frontend.history') }}">{{ trans('latraining.history') }}</a>
                            @endif
                            <i class="uil uil-angle-right"></i>
                            <span class="font-weight-bold">{{ trans('latraining.your_coaching_plan') }}</span>
                        </h2>
                    </div>
                </div>
                <div class="col-md-12 pt-2 pb-5">
                    <form method="POST" action="{{ route('module.coaching.frontend.save_content_skill') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="{{ $model->id }}">
                        <input type="hidden" name="view" value="{{ $coaching_teacher_user_id == profile()->user_id ? 'history' : 'index' }}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-sm-2 control-label">
                                        <label for="content">{{ trans('latraining.content_skills') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-10">
                                        <textarea name="content" id="content" placeholder="{{ trans('latraining.content_skills') }}" class="form-control" rows="3">{{ $model->content }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2 control-label">
                                        <label>{{ trans('latraining.time') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-10">
                                        <span>
                                            <input name="start_date" type="text" class="datepicker form-control d-inline-block w-25" placeholder="{{trans('latraining.start_date')}}" autocomplete="off" value="{{ $model->start_date ? get_date($model->start_date) : date('d/m/Y') }}">
                                        </span>
                                        <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                                        <span>
                                            <input name="end_date" type="text" class="datepicker form-control d-inline-block w-25" placeholder='{{trans("latraining.end_date")}}' autocomplete="off" value="{{ $model->end_date ? get_date($model->end_date) :date('t/m/Y') }}">
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2 control-label">
                                        <label for="coaching_teacher_id">Coacher <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-10">
                                        <select name="coaching_teacher_id" id="coaching_teacher_id" class="form-control select2" data-placeholder="Coacher">
                                            <option value=""></option>
                                            @foreach ($coaching_teachers as $coaching_teacher)
                                                <option value="{{ $coaching_teacher->id }}" 
                                                    {{ ($coaching_teacher_id == $coaching_teacher->id || $model->coaching_teacher_id == $coaching_teacher->id) ? 'selected' : '' }}
                                                >
                                                    {{ $coaching_teacher->user->lastname .' '. $coaching_teacher->user->firstname }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2 control-label">
                                        <label for="training_objectives">{{ trans('lasuggest_plan.training_objectives') }}</label>
                                    </div>
                                    <div class="col-md-9">
                                        <textarea name="training_objectives" id="training_objectives" placeholder="{{ trans('lasuggest_plan.training_objectives') }}" class="form-control" rows="3">{{ $model->training_objectives }}</textarea>
                                    </div>
                                    <div class="col-md-1 pl-0">
                                        <select name="score_training_objectives" id="score_training_objectives" class="form-control select2" data-placeholder="{{ trans('latraining.score') }}">
                                            <option value=""></option>
                                            @for ($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}" {{ $model->score_training_objectives == $i ? 'selected' : '' }}> {{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2 control-label">
                                        <label for="students">{{ trans('latraining.student') }}</label>
                                    </div>
                                    <div class="col-md-10">
                                        <select name="students[]" id="students" class="form-control load-user-other" data-placeholder="{{ trans('latraining.student') }}" multiple>
                                            @if($profile_view)
                                                @foreach ($profile_view as $profile)
                                                    <option value="{{ $profile->user_id }}" selected> {{ $profile->code .' - '. $profile->full_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2 control-label">
                                        <label for="comment_status_student">{{ trans('latraining.comment_status_student') }}</label>
                                    </div>
                                    <div class="col-md-9">
                                        <textarea name="comment_status_student" id="comment_status_student" placeholder="{{ trans('latraining.comment_status_student') }}" class="form-control" rows="3" {{ $disable_not_teacher }}>{{ $model->comment_status_student }}</textarea>
                                    </div>
                                    <div class="col-md-1 pl-0">
                                        <select name="score_comment_status_student" id="score_comment_status_student" class="form-control select2" data-placeholder="{{ trans('latraining.score') }}" {{ $disable_not_teacher }}>
                                            <option value=""></option>
                                            @for ($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}" {{ $model->score_comment_status_student == $i ? 'selected' : '' }}> {{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2 control-label">
                                        <label for="plan">{{ trans('latraining.plan') }}</label>
                                        @if (!$model->score_student_comment)
                                            <a href="javascript:void(0)" class="btn" id="add_criteria">
                                                {{ trans('latraining.add_criteria') }}
                                            </a>
                                        @endif
                                    </div>
                                    <div class="col-md-10">
                                        <table class="tDefault table table-hover" id="table_plan">
                                            <thead>
                                                <tr>
                                                    <th>{{ trans('latraining.content') }}</th>
                                                    <th>{{ trans('latraining.start') }}</th>
                                                    <th>{{ trans('latraining.perform') }}</th>
                                                    <th>{{ trans('latraining.note') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($plan_content)
                                                    @foreach ($plan_content as $i => $plan)
                                                        <tr>
                                                            <th>
                                                                <input type="text" class="form-control" name="plan_content[]" value="{{ isset($plan_content[$i]) ? $plan_content[$i] : '' }}">
                                                            </th>
                                                            <th>
                                                                <input type="text" class="form-control" name="plan_start[]" value="{{ isset($plan_start[$i]) ? $plan_start[$i] : '' }}">
                                                            </th>
                                                            <th>
                                                                <input type="text" class="form-control" name="plan_perform[]" value="{{ isset($plan_perform[$i]) ? $plan_perform[$i] : '' }}">
                                                            </th>
                                                            <th>
                                                                <input type="text" class="form-control" name="plan_note[]" value="{{ isset($plan_note[$i]) ? $plan_note[$i] : '' }}">
                                                            </th>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2 control-label">
                                        <label for="coaching_mentor_method_id">{{ trans('lamenu.coaching_mentor_method') }}</label>
                                    </div>
                                    <div class="col-md-10">
                                        <select name="coaching_mentor_method_id" id="coaching_mentor_method_id" class="form-control select2" data-placeholder="{{ trans('lamenu.coaching_mentor_method') }}">
                                            <option value=""></option>
                                            @if ($coaching_mentor_methor)
                                                @foreach ($coaching_mentor_methor as $mentor_methor)
                                                    <option value="{{ $mentor_methor->id }}" {{ $model->coaching_mentor_method_id == $mentor_methor->id ? 'selected' : '' }}>
                                                        {{ $mentor_methor->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2 control-label">
                                        <label for="teacher_comment">{{ trans('latraining.teacher_comment') }}</label>
                                    </div>
                                    <div class="col-md-9">
                                        <textarea name="teacher_comment" id="teacher_comment" placeholder="{{ trans('latraining.teacher_comment') }}" class="form-control" rows="3" {{ $disable_not_teacher }}>{{ $model->teacher_comment }}</textarea>
                                    </div>
                                    <div class="col-md-1 pl-0">
                                        <select name="score_teacher_comment" id="score_teacher_comment" class="form-control select2" data-placeholder="{{ trans('latraining.score') }}" {{ $disable_not_teacher }}>
                                            <option value=""></option>
                                            @for ($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}" {{ $model->score_teacher_comment == $i ? 'selected' : '' }}> {{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2 control-label">
                                    </div>
                                    <div class="col-md-9">
                                        <textarea name="note_teacher_comment" id="note_teacher_comment" placeholder="{{ trans('latraining.note') }}" class="form-control" rows="3" {{ $disable_not_teacher }}>{{ $model->note_teacher_comment }}</textarea>
                                    </div>
                                    <div class="col-md-1 pl-0">
                                        <input type="checkbox" name="metor_again" id="metor_again" value="{{ $model->metor_again ? $model->metor_again : 0 }}" {{ $model->metor_again == 1 ? 'checked' : '' }} {{ $disable_not_teacher }}> <label for="metor_again">{{ trans('latraining.metor_again') }}</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2 control-label">
                                        <label for="student_comment">{{ trans('latraining.student_comment') }}</label>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="rating-box mb-2">
                                            @for ($i = 1; $i <= 10; $i++)
                                                <span class="rating-star {{ $i <= $model->score_student_comment ? 'full-star' : 'empty-star score_student_comment' }}" 
                                                    data-value={{ $i }}>
                                                </span>
                                            @endfor
                                            
                                            <input type="hidden" name="score_student_comment" id="score_student_comment" value="{{ $model->score_student_comment }}">
                                        </div>
                                        <textarea name="student_comment" id="student_comment" placeholder="{{ trans('latraining.student_comment') }}" class="form-control" rows="3">{{ $model->student_comment }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2 control-label">
                                    </div>
                                    <div class="col-md-10">
                                        <textarea name="note_student_comment" id="note_student_comment" placeholder="{{ trans('latraining.note') }}" class="form-control" rows="3">{{ $model->note_student_comment }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <div class="btn-group act-btns">
                                    @if (!$model->score_student_comment || $coaching_teacher_user_id == profile()->user_id)
                                    <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                                    @endif
                                    <a href="{{ route('module.coaching.frontend') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#metor_again').on('click', function(){
            if($(this).is(':checked')){
                $('#metor_again').val(1);
            }else{
                $('#metor_again').val(0);
            }
        })
        $('#add_criteria').on('click',function (e) {
            e.preventDefault();
            let html="";
            html += '<tr>\
                <td><input type="text" class="form-control" name="plan_content[]"></td>\
                <td><input type="text" class="form-control" name="plan_start[]"></td>\
                <td><input type="text" class="form-control" name="plan_perform[]"></td>\
                <td><input type="text" class="form-control" name="plan_note[]"></td>\
                <td class="text-center"><a href="javascript:void(0)"><i class="fa fa-trash text-danger"></i></a></td>\
            </tr>';
            $('#table_plan tbody').append(html);
        });

        $('#table_plan').on('click','a',function (e) {
            $(this).closest('tr').remove();
        });

        var score_student_comment = $('.score_student_comment');
        var id_score_student_comment = $('#score_student_comment');
        ratingStars(score_student_comment, id_score_student_comment);

        function ratingStars(element, obj_id) {
            element.on('mouseover',function () {
                var onStar = parseInt($(this).data('value'), 10);
                $(this).parent().children('.rating-star').each(function(e){
                    if (e < onStar) {
                        $(this).addClass('full-star');
                    }
                    else {
                        $(this).removeClass('selected');
                        $(this).removeClass('full-star')
                    }
                });
            }).on('mouseout', function(){
                $(this).each(function(e){
                    $(this).removeClass('full-star');
                });
            })

            element.on("click", function(){
                var onStar = parseInt($(this).data('value'), 10);
                var stars = $(this).parent().children('.rating-star');
                for (i = 0; i < stars.length; i++) {
                    $(stars[i]).removeClass('full-star');
                }

                for (i = 0; i < onStar; i++) {
                    $(stars[i]).addClass('selected');
                    $(stars[i]).removeClass('full-star');
                }

                var ratingValue = parseInt($('.rating-star.selected').last().data('value'), 10);
                
                obj_id.val(ratingValue);
            })
        };
    </script>
@stop

