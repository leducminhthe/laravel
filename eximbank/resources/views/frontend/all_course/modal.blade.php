{{-- MODAL Chủ đề --}}
<div class="modal fade" id="modal-training-program">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('latraining.education_program') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @foreach ($training_programs as $training_program)
                        <div class="col-6 checkbox_modal">
                            <input type='checkbox' class="training_program_checkbox" name="training_program_id" value='{{ $training_program->id }}' id="training_program_{{ $training_program->id }}" />
                            <label style="margin-top: 2px" class="mb-0" for="training_program_{{ $training_program->id }}">{{ $training_program->name }}</label><br>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL HỌC PHẦN --}}
<div class="modal fade" id="modal-level-subject">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('latraining.subject') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @foreach ($level_subjects as $level_subject)
                        <div class="col-6 checkbox_modal">
                            <input type='checkbox' class="level_subject_checkbox" name="level_subject_id" value='{{ $level_subject->id }}' id="level_subject_{{ $level_subject->id }}" />
                            <label style="margin-top: 2px" class="mb-0" for="level_subject_{{ $level_subject->id }}">{{ $level_subject->name }}</label><br>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL HIỂN THỊ --}}
<div class="modal fade" id="modal-filter-show">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('laother.form_course_info') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-3">
                        <span>{{ trans('laother.type_form') }}</span>
                    </div>
                    <div class="col-9">
                        <label class="label_course_type" for="filter_basic">
                            <input type="radio" id="filter_basic" name="filter_show_form" onclick="filterBasic()" class="filter_show_form" value="1" checked>
                            <span>{{ trans('laother.basic') }}</span>
                        </label><br>
                        <label class="label_course_type" for="filer_detail">
                            <input type="radio" id="filer_detail" name="filter_show_form" onclick="filterDetail()" class="filter_show_form" value="2">
                            <span>{{ trans('lacore.detail') }}</span>
                        </label><br>
                        <label class="label_course_type" for="filter_progress">
                            <input type="radio" id="filter_progress" name="filter_show_form" onclick="filterProgress()" class="filter_show_form" value="3">
                            <span>{{ trans('laother.progress') }}</span>
                        </label><br>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL ĐĂNG KÝ --}}
<form action="" id="frm-course" method="post" class="form-ajax"></form>

{{-- HÉT HẠN ĐĂNG KÝ --}}
<div class="modal fade" id="modal-end-course">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title modal_title_notification">
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body modal_body_notification">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL LINK SHARE --}}
<div class="modal fade" id="modal-share">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('lacore.copy') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="modal-body-share">
            </div>
            <div class="modal-footer">
                <div id="btn-copy">
                </div>
                <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL MÔ TẢ --}}
<div class="modal fade" id="modal-description">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('latraining.description') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="modal-body-description">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TÓM TẮT --}}
<div class="modal fade" id="modal-summary">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('latraining.brief') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="modal-body-summary">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
            </div>
        </div>
    </div>
</div>

{{-- MOdal SHOW ĐỐI TƯỢNG --}}
<div class="modal fade" id="modal_object" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="" method="post" class="form-ajax">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ trans('latraining.object') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body model_body_object pt-0 mt-2">
                    <table class="table table-bordered table-striped" id="table_object">
                        <thead>
                            <tr>
                                <th data-field="title_name">{{trans('latraining.title')}}</th>
                                <th data-align="center" data-field="type" data-width="10%" data-formatter="type_formatter">{{trans('latraining.type_object')}}</th>
                            </tr>
                        </thead>
                        <tbody id="tbody_object">
                            
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- MODAL ĐIỂM THƯỞNG --}}
<div class="modal fade modal-add-activity" id="modal-bonus">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <img class="image_bonus_courses" src="{{asset("images/level/point.png")}}" alt="" width="20px" height="15px">
                    {{ trans('laother.reward_points') }}
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="promotion-enable">
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <h6>{{ trans('laother.scoring_method') }}:</h6>
                        </div>
                        <div class="col-md-9" id="checkbox_promotion">
                            
                        </div>
                    </div>
                    
                    <div id="promotion_description">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>            
            </div>
        </div>
    </div>
</div>

{{-- MODAL THÔNG BÁO THIẾT LẬP THAM GIA --}}
<div class="modal fade modal-noty-setting-join" id="modal-setting-join">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    {{ trans('latraining.join_setup') }}
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <h3 class="noty_setting_join"></h3>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>            
            </div>
        </div>
    </div>
</div>