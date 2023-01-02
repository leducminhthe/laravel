<div role="main">
    <form method="post" action="{{ route('backend.category.training_teacher.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['category-teacher-create', 'category-teacher-edit'])
                    <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                    @endcanany
                    <a href="{{ route('backend.category.training_teacher') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <br>
        <div class="tPanel">
            <div class="tab-content">
                <div id="base" class="tab-pane active">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="type">{{trans('lacategory.form')}} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <select name="type" id="type" class="form-control" required data-placeholder="-- {{trans('latraining.choose_form')}} --" @if(isset($id)) disabled @endif >
                                        <option value="1" {{ $model->type == 1 ? 'selected' : '' }}>{{trans("latraining.internal")}}</option>
                                        <option value="2" {{ $model->type == 2 ? 'selected' : '' }}>{{trans("latraining.outside")}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('laother.teacher_code') }}<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <input name="code" id="code" type="text" class="form-control" value="{{ $model->code }}" required readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('laother.teacher_name') }}<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <input name="name" id="name" type="text" class="form-control" value="{{ $model->name }}" required readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>Email <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <input name="email" id="email" type="text" class="form-control" value="{{ $model->email }}" required readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('laother.teacher_phone') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <input name="phone" id="phone" type="text" class="form-control" value="{{ $model->phone }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('lacategory.account_number') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <input name="account_number" id="account_number" type="text" class="form-control" value="{{ $model->account_number }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('lacategory.unit') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <input id="unit" type="text" class="form-control" value="{{ isset($unit) ? $unit->code . ' - ' . $unit->name : ''
                                     }}" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('lacategory.title') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <input id="title" type="text" class="form-control" value="{{ isset($title) ? $title->code . ' - ' . $title->name :
                                   ''}}" disabled>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="teacher_type_id">{{ trans('latraining.teacher_type') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <select name="teacher_type_id" id="teacher_type_id" class="form-control select2" data-placeholder="-- {{ trans('latraining.teacher_type') }} --" >
                                        <option value=""></option>
                                        @foreach($teacher_types as $teacher_type)
                                            <option value="{{ $teacher_type->id }}" {{ $model->teacher_type_id ==  $teacher_type->id ? 'selected' : '' }}>{{ $teacher_type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if ((isset($model->type) && $model->type == 2) || empty($model->type))
                            <div class="form-group row">
                                <div class="col-md-3 control-label">
                                    <label for="training_partner_id">{{ trans('lacategory.partner') }} </label>
                                </div>
                                <div class="col-md-6">
                                    <select name="training_partner_id" id="training_partner" class="form-control select2" data-placeholder="-- {{ trans('laother.choose_partner') }} --" >
                                        <option value=""></option>
                                        @foreach($training_partner as $item)
                                            <option value="{{ $item->id }}" {{ $model->training_partner_id ==  $item->id ? 'selected' : ''}}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('latraining.cost') }} <br> ({{ trans('latraining.main_lecturer') }})</label>
                                </div>
                                <div class="col-md-6">
                                    <input name="cost_teacher_main" id="cost_teacher_main" type="text" class="form-control is-number" value="{{ $model->cost_teacher_main }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('latraining.cost') }} <br> ({{ trans('latraining.tutors') }})</label>
                                </div>
                                <div class="col-md-6">
                                    <input name="cost_teach_type" id="cost_teach_type" type="text" class="form-control is-number" value="{{ $model->cost_teach_type }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('latraining.convention') }}</label>
                                </div>
                                <div class="col-md-6 form-inline">
                                    {{ trans('latraining.session') }} =
                                    <input name="num_hour" id="num_hour" type="text" class="form-control is-number w-5" value="{{ $model->num_hour }}"> {{ trans('latraining.hour') }}
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('latraining.status') }}<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <label class="radio-inline"><input type="radio" required name="status" value="1" @if($model->status == 1) checked @endif>{{ trans('lacategory.working') }}</label>
                                    <label class="radio-inline"><input type="radio" required name="status" value="0" @if($model->status == 0) checked @endif>{{ trans('lacategory.lay_off') }}</label>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    var ajax_get_user = "{{ route('backend.category.ajax_get_user') }}";
    $('#type').attr('disabled',true)
</script>

<script src="{{ asset('styles/module/training_teacher/js/training_teacher.js?v='.time()) }}"></script>
