
<form method="post" action="{{ route('backend.slider.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{ $model_web->id }}">
    <input type="hidden" name="type" value="1">
    <div class="row">
        <div class="col-md-8">

        </div>
        <div class="col-md-4 text-right">
            <div class="btn-group act-btns">
                @canany(['banner-create', 'banner-edit'])
                    <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                @endcanany
                <a href="{{ route('backend.slider') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-3 control-label">
            <label for="image">{{trans('lasetting.picture')}} <span class="text-danger">*</span> <br>({{trans('lasetting.size')}}: 1500x300)</label>
        </div>

        <div class="col-sm-6">
            <a href="javascript:void(0)" id="select-image-web">{{trans('lasetting.choose_picture')}}</a>
            <div id="image-review-web">@if($model_web->image) <img src="{{ image_file($model_web->image) }}" class="w-25"> @endif</div>
            <input type="hidden" class="form-control" name="image" id="image-select-web" value="{{ $model_web->image }}">
        </div>
    </div>

    {{-- <div class="form-group row">
        <div class="col-sm-3 control-label">
            <label for="description">{{trans('lasetting.description')}}</label>
        </div>
        <div class="col-sm-6">
            <textarea name="description" id="description" class="form-control" rows="4">{{ $model_web->description }}</textarea>
        </div>
    </div> --}}

    <div class="form-group row">
        <div class="col-sm-3 control-label">
            <label for="location">{{ trans('lasetting.object') }} </label>
        </div>
        <div class="col-sm-6">
            <select name="object[]" id="object" class="form-control select2" data-placeholder="-- {{ trans('lasetting.object') }} --" multiple>
                <option value=""></option>
                @foreach($unit as $item)
                    <option value="{{ $item->id }}" {{ !empty($get_slider_web) && in_array($item->id, $get_slider_web) ? 'selected' : '' }}> {{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- <div class="form-group row">
        <div class="col-sm-3 control-label">
            <label for="display_order">{{trans('lasetting.order')}} <span class="text-danger">*</span></label>
        </div>
        <div class="col-sm-6">
            <input type="text" name="display_order" id="display_order" class="form-control is-number"
                   value="{{ if_empty($model_web->display_order, 1) }}">
        </div>
    </div> --}}

    <div class="form-group row">
        <div class="col-sm-3 control-label">
            <label for="location">{{ trans('lasetting.location') }}</label>
        </div>
        <div class="col-sm-6">
            <span class="cursor_pointer" onclick="showModal()">{{ trans('lasetting.location') }}</span>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-3 control-label">
            <label for="url">{{ trans('lasetting.link') }}</label>
        </div>
        <div class="col-sm-6">
            <input type="text" name="url" id="url" class="form-control" value="{{ $model_web->url }}">
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-3 control-label">
            <label for="status">{{trans('lasetting.status')}} <span class="text-danger">*</span></label>
        </div>
        <div class="col-sm-6">
            <select name="status" id="status" class="form-control select2-default" data-placeholder="-- {{trans('lasetting.status')}} --" required>

                <option value="1" {{ $model_web->status == 1 ? 'selected' : '' }}>{{trans("lasetting.enable")}}</option>
                <option value="0" {{ (!is_null($model_web->status) && $model_web->status == 0) ? 'selected' : '' }}>{{trans("lasetting.disable")}}</option>

            </select>
        </div>
    </div>

    {{-- CHỌN VỊ TRÍ --}}
    <div class="modal fade" id="modal-training-program">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('lasetting.location') }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="modal-body-description">
                    <div class="row">
                        <div class="col-6 checkbox_modal">
                            <input type='checkbox' id="info" name="position[]" value='news-react'
                                @if (!empty($slider_position) && in_array('news-react', $slider_position)) checked @endif
                            />
                            <label for="info">{{ trans('lamenu.news') }}</label><br>
                        </div>
                        <div class="col-6 checkbox_modal">
                            <input type='checkbox' id="course_online" name="position[]" value='course_online'
                                @if (!empty($slider_position) && in_array('course_online', $slider_position)) checked @endif
                            />
                            <label for="course_online">{{ trans('lamenu.online_course') }}</label><br>
                        </div>
                        <div class="col-6 checkbox_modal">
                            <input type='checkbox' id="course_offline" name="position[]" value='course_offline'
                                @if (!empty($slider_position) && in_array('course_offline', $slider_position)) checked @endif
                            />
                            <label for="course_offline">{{ trans('lamenu.offline_course') }}</label><br>
                        </div>
                        <div class="col-6 checkbox_modal">
                            <input type='checkbox' id="my_course" name="position[]" value='my_course'
                                @if (!empty($slider_position) && in_array('my_course', $slider_position)) checked @endif
                            />
                            <label for="my_course">{{ trans('latraining.my_course') }}</label><br>
                        </div>
                        <div class="col-6 checkbox_modal">
                            <input type='checkbox' id="daily_training" name="position[]" value='daily-training-react'
                                @if (!empty($slider_position) && in_array('daily-training-react', $slider_position)) checked @endif
                            />
                            <label for="daily_training">{{ trans('lamenu.training_video') }}</label><br>
                        </div>
                        <div class="col-6 checkbox_modal">
                            <input type='checkbox' id="survey" name="position[]" value='survey-react'
                                @if (!empty($slider_position) && in_array('survey-react', $slider_position)) checked @endif
                            />
                            <label for="survey">{{ trans('lamenu.survey') }}</label><br>
                        </div>
                        <div class="col-6 checkbox_modal">
                            <input type='checkbox' id="rating-level" name="position[]" value='rating-level'
                                @if (!empty($slider_position) && in_array('rating-level', $slider_position)) checked @endif
                            />
                            <label for="rating-level">{{ trans('lamenu.kirkpatrick_model') }}</label><br>
                        </div>
                        <div class="col-6 checkbox_modal">
                            <input type='checkbox' id="library" name="position[]" value='library'
                                @if (!empty($slider_position) && in_array('library', $slider_position)) checked @endif
                            />
                            <label for="library">{{ trans('lamenu.library') }}</label><br>
                        </div>
                        <div class="col-6 checkbox_modal">
                            <input type='checkbox' id="forums" name="position[]" value='forums-react'
                                @if (!empty($slider_position) && in_array('forums-react', $slider_position)) checked @endif
                            />
                            <label for="forums">{{ trans('lamenu.forum') }}</label><br>
                        </div>
                        <div class="col-6 checkbox_modal">
                            <input type='checkbox' id="suggest" name="position[]" value='suggest-react'
                                @if (!empty($slider_position) && in_array('suggest-react', $slider_position)) checked @endif
                            />
                            <label for="suggest">{{ trans('lamenu.suggestion') }}</label><br>
                        </div>
                        <div class="col-6 checkbox_modal">
                            <input type='checkbox' id="topic-situation" name="position[]" value='topic-situation-react'
                                @if (!empty($slider_position) && in_array('topic-situation-react', $slider_position)) checked @endif
                            />
                            <label for="topic-situation">{{ trans('lamenu.situations_proccessing') }}</label><br>
                        </div>
                        <div class="col-6 checkbox_modal">
                            <input type='checkbox' id="promotion" name="position[]" value='promotion-react'
                                @if (!empty($slider_position) && in_array('promotion-react', $slider_position)) checked @endif
                            />
                            <label for="promotion">{{ trans('lamenu.study_promotion_program') }}</label><br>
                        </div>
                        <div class="col-6 checkbox_modal">
                            <input type='checkbox' id="usermedal" name="position[]" value='usermedal'
                                @if (!empty($slider_position) && in_array('usermedal', $slider_position)) checked @endif
                            />
                            <label for="usermedal">{{ trans('lamenu.emulation_program') }}</label><br>
                        </div>
                        <div class="col-6 checkbox_modal">
                            <input type='checkbox' id="usermedal-history" name="position[]" value='usermedal-history'
                                @if (!empty($slider_position) && in_array('usermedal-history', $slider_position)) checked @endif
                            />
                            <label for="usermedal-history">{{ trans('latraining.medal_history') }}</label><br>
                        </div>
                        <div class="col-6 checkbox_modal">
                            <input type='checkbox' id="userpoint" name="position[]" value='userpoint'
                                @if (!empty($slider_position) && in_array('userpoint', $slider_position)) checked @endif
                            />
                            <label for="userpoint">{{ trans('latraining.get_point_history') }}</label><br>
                        </div>
                        <div class="col-6 checkbox_modal">
                            <input type='checkbox' id="info" name="position[]" value='info'
                                @if (!empty($slider_position) && in_array('info', $slider_position)) checked @endif
                            />
                            <label for="info">{{ trans('lacore.info') }}</label><br>
                        </div>
                        {{-- <div class="col-6 checkbox_modal">
                            <input type='checkbox' id="roadmap" name="position[]" value='menu_roadmap'
                                @if (!empty($slider_position) && in_array('menu_roadmap', $slider_position)) checked @endif
                            />
                            <label for="roadmap">{{ trans('lamenu.learning_path') }}</label><br>
                        </div> --}}
                        {{-- <div class="col-6 checkbox_modal">
                            <input type='checkbox' id="trainingprocess" name="position[]" value='menu_trainingprocess'
                                @if (!empty($slider_position) && in_array('menu_trainingprocess', $slider_position)) checked @endif
                            />
                            <label for="trainingprocess">{{ trans('laprofile.training_process') }}</label><br>
                        </div> --}}
                        {{-- <div class="col-6 checkbox_modal">
                            <input type='checkbox' id="another" name="position[]" value='menu_another'
                                @if (!empty($slider_position) && in_array('menu_another', $slider_position)) checked @endif
                            />
                            <label for="another">{{ trans('laprofile.other ') }}</label><br>
                        </div> --}}
                        <div class="col-6 checkbox_modal">
                            <input type='checkbox' id="dashboard" name="position[]" value='dashboard'
                                @if (!empty($slider_position) && in_array('dashboard', $slider_position)) checked @endif
                            />
                            <label for="dashboard">Dashboard</label><br>
                        </div>
                        <div class="col-6 checkbox_modal">
                            <input type='checkbox' id="dashboard_by_user" name="position[]" value='dashboard_by_user'
                                @if (!empty($slider_position) && in_array('dashboard_by_user', $slider_position)) checked @endif
                            />
                            <label for="dashboard_by_user">{{ trans('lamenu.summary') }}</label><br>
                        </div>
                        <div class="col-6 checkbox_modal">
                            <input type='checkbox' id="calendar" name="position[]" value='calendar'
                                @if (!empty($slider_position) && in_array('calendar', $slider_position)) checked @endif
                            />
                            <label for="calendar">{{ trans('lamenu.training_calendar') }}</label><br>
                        </div>
                        <div class="col-6 checkbox_modal">
                            <input type='checkbox' id="quiz" name="position[]" value='quiz-react'
                                @if (!empty($slider_position) && in_array('quiz-react', $slider_position)) checked @endif
                            />
                            <label for="quiz">{{ trans('lamenu.quiz_manager') }}</label><br>
                        </div>
                        <div class="col-6 checkbox_modal">
                            <input type='checkbox' id="note" name="position[]" value='note-react'
                                @if (!empty($slider_position) && in_array('note-react', $slider_position)) checked @endif
                            />
                            <label for="note">{{ trans('latraining.my_note') }}</label><br>
                        </div>
                        <div class="col-6 checkbox_modal">
                            <input type='checkbox' id="interaction_history" name="position[]" value='interaction_history'
                                @if (!empty($slider_position) && in_array('interaction_history', $slider_position)) checked @endif
                            />
                            <label for="interaction_history">{{ trans('lamenu.interaction_history_clear') }}</label><br>
                        </div>
                        <div class="col-6 checkbox_modal">
                            <input type='checkbox' id="guide" name="position[]" value='guide-react'
                                @if (!empty($slider_position) && in_array('guide-react', $slider_position)) checked @endif
                            />
                            <label for="guide">{{ trans('lamenu.guide') }}</label><br>
                        </div>
                        <div class="col-6 checkbox_modal">
                            <input type='checkbox' id="faq" name="position[]" value='faq-react'
                                @if (!empty($slider_position) && in_array('faq-react', $slider_position)) checked @endif
                            />
                            <label for="faq">{{ trans('lamenu.faq') }}</label><br>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    function showModal() {
        $('#modal-training-program').modal();
    }

    $("#select-image-web").on('click', function () {
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'image'}, function (url, path) {
            $("#image-review-web").html('<img src="' + path + '" class="w-25">');
            $("#image-select-web").val(path);
        });
    });
</script>
