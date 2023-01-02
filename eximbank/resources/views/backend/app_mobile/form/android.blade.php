<form method="post" action="{{ route('backend.app_mobile.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{ $model_android ? $model_android->id : '' }}">
    <input type="hidden" name="type" value="1">
    <div class="row">
        <div class="col-md-8">

        </div>
        <div class="col-md-4 text-right">
            <div class="btn-group act-btns">
                <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-3 control-label">
            <label for="image">{{trans('lasetting.picture')}} <span class="text-danger">*</span> <br>({{trans('lasetting.size')}}: 132x42)</label>
        </div>

        <div class="col-sm-6">
            <a href="javascript:void(0)" id="select-image-android">{{trans('lasetting.choose_picture')}}</a>
            <div id="image-review-android">@if($model_android) <img src="{{ image_file($model_android->image) }}" class="w-25"> @endif</div>
            <input type="hidden" class="form-control" name="image" id="image-select-android" value="{{ $model_android ? $model_android->image : '' }}">
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-3 control-label">
            <label for="">{{ trans('lasetting.link') }}/{{ trans('latraining.file') }}</label>
        </div>
        <div class="col-sm-6">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="options_android" id="options_android_link" value="link" {{ $model_android && $model_android->link ? 'checked' : '' }}>
                <label class="form-check-label" for="options_android_link">{{ trans('lasetting.link') }}</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="options_android" id="options_android_file" value="file" {{ $model_android && $model_android->file ? 'checked' : '' }}>
                <label class="form-check-label" for="options_android_file">{{ trans('latraining.file') }}</label>
            </div>
        </div>
    </div>

    <div class="form-group row" id="form_android_link">
        <div class="col-sm-3 control-label">

        </div>
        <div class="col-sm-6">
            <input name="link" class="form-control" value="{{ $model_android ? $model_android->link : '' }}">
        </div>
    </div>

    <div class="form-group row" id="form_android_file">
        <div class="col-sm-3 control-label">

        </div>
        <div class="col-sm-6">
            <input type="file" name="file" value="{{ $model_android ? $model_android->file : '' }}"> <br> {{ $model_android ? basename($model_android->file) : '' }}
        </div>
    </div>
</form>

<script type="text/javascript">
    if('{{ $model_android && $model_android->link }}' == 1){
        $('#form_android_link').show();
        $('#form_android_file').hide();
    }else if('{{ $model_android && $model_android->file }}' == 1){
        $('#form_android_link').hide();
        $('#form_android_file').show();
    }else{
        $('#form_android_link').hide();
        $('#form_android_file').hide();
    }

    $('#options_android_link').on('click', function(){
        $('#form_android_link').show();
        $('#form_android_file').hide();
    });

    $('#options_android_file').on('click', function(){
        $('#form_android_link').hide();
        $('#form_android_file').show();
    });

    $("#select-image-android").on('click', function () {
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'image'}, function (url, path) {
            $("#image-review-android").html('<img src="' + path + '" class="w-25">');
            $("#image-select-android").val(path);
        });
    });
</script>
