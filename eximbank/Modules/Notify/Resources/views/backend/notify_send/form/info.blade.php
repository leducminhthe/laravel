<form method="post" action="{{ route('module.notify_send.save') }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{ $model->id }}">
    <div class="row">
        <div class="col-md-8">
        </div>
        <div class="col-md-4 text-right">
            <div class="btn-group act-btns">
                @canany(['notify-create', 'notify-edit'])
                <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                @endcanany
                <a href="{{ route('module.notify_send') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
            </div>
        </div>
    </div>
    <div class="row">
    <div class="col-md-9">
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('lasetting.notify_name') }} <span class="text-danger">*</span> </label>
            </div>
            <div class="col-md-9">
                <input name="subject" type="text" class="form-control" value="{{ $model->subject }}" required>
            </div>
        </div>
        {{--<div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>Đường link </label>
            </div>
            <div class="col-md-9">
                <input type="text" class="form-control" name="url" autocomplete="off" value="{{ $model->url }}" required>
            </div>
        </div>--}}
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('lasetting.content') }} </label>
            </div>
            <div class="col-md-9">
                <textarea type="text" id="text" class="form-control" name="content"> {{ $model->content }} </textarea>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('lasetting.time_send') }} </label>
            </div>
            <div class="col-md-9">
                <span>
                    <input name="start_time" type="text" class="form-control time_picker d-inline-block w-25" autocomplete="off" value="{{ get_date($model->time_send, 'H:i') }}">
                    <input type="text" class="form-control datepicker d-inline-block w-25" name="time_send" value="{{ get_date($model->time_send, 'd/m/Y') }}">
                </span>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('lasetting.important') }}</label>
            </div>
            <div class="col-md-6">
                <label class="radio-inline"><input type="radio" name="important" value="1" @if($model->important == 1) checked @endif> {{trans("lasetting.yes")}}</label>
                <label class="radio-inline"><input type="radio" name="important" value="0" @if($model->important == 0) checked @endif> {{trans("lasetting.no")}}</label>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{trans('lasetting.status')}}</label>
            </div>
            <div class="col-md-6">
                <label class="radio-inline"><input type="radio" name="status" value="1" @if($model->status == 1) checked @endif> {{trans("lasetting.enable")}}</label>
                <label class="radio-inline"><input type="radio" name="status" value="0" @if($model->status == 0) checked @endif> {{trans("lasetting.disable")}}</label>
            </div>
        </div>
    </div>
</div>
</form>
<script type="text/javascript">
    $(document).ready(function () {
        $('.time_picker').datetimepicker({
            locale:'vi',
            format: 'HH:mm'
        });
    });
</script>
<script>
    CKEDITOR.replace('text', {
        filebrowserImageBrowseUrl: '/filemanager?type=image',
        filebrowserBrowseUrl: '/filemanager?type=file',
        filebrowserUploadUrl : null, //disable upload tab
        filebrowserImageUploadUrl : null, //disable upload tab
        filebrowserFlashUploadUrl : null, //disable upload tab
    });
</script>
