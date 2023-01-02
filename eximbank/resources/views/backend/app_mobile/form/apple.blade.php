<form method="post" action="{{ route('backend.app_mobile.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{ $model_apple ? $model_apple->id : '' }}">
    <input type="hidden" name="type" value="2">
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
            <a href="javascript:void(0)" id="select-image-apple">{{trans('lasetting.choose_picture')}}</a>
            <div id="image-review-apple">@if($model_apple) <img src="{{ image_file($model_apple->image) }}" class="w-25"> @endif</div>
            <input type="hidden" class="form-control" name="image" id="image-select-apple" value="{{ $model_apple ? $model_apple->image : '' }}">
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-3 control-label">
            <label for="">{{ trans('lasetting.link') }}/{{ trans('latraining.file') }}</label>
        </div>
        <div class="col-sm-6">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="options_apple" id="options_apple_link" value="link" {{ $model_apple && $model_apple->link ? 'checked' : '' }}>
                <label class="form-check-label" for="options_apple_link">{{ trans('lasetting.link') }}</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="options_apple" id="options_apple_file" value="file"{{ $model_apple && $model_apple->file ? 'checked' : '' }}>
                <label class="form-check-label" for="options_apple_file">{{ trans('latraining.file') }}</label>
            </div>
        </div>
    </div>

    <div class="form-group row" id="form_apple_link" >
        <div class="col-sm-3 control-label">
        </div>
        <div class="col-sm-6">
            <input name="link" class="form-control" value="{{ $model_apple ? $model_apple->link : '' }}">
        </div>
    </div>

    <div class="form-group row" id="form_apple_file" >
        <div class="col-sm-3 control-label">
        </div>
        <div class="col-sm-6">
            <input type="file" name="file" value="{{ $model_apple ? $model_apple->file : '' }}"> <br> {{ $model_apple ? basename($model_apple->file) : '' }}
        </div>
    </div>
</form>
<script type="text/javascript">
    if('{{ $model_apple && $model_apple->link }}' == 1){
        $('#form_apple_link').show();
        $('#form_apple_file').hide();
    }else if('{{ $model_apple && $model_apple->file }}' == 1){
        $('#form_apple_link').hide();
        $('#form_apple_file').show();
    }else{
        $('#form_apple_link').hide();
        $('#form_apple_file').hide();
    }

    $('#options_apple_link').on('click', function(){
        $('#form_apple_link').show();
        $('#form_apple_file').hide();
    });

    $('#options_apple_file').on('click', function(){
        $('#form_apple_link').hide();
        $('#form_apple_file').show();
    });

    $("#select-image-apple").on('click', function () {
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'image'}, function (url, path) {
            $("#image-review-apple").html('<img src="' + path + '" class="w-25">');
            $("#image-select-apple").val(path);
        });
    });
</script>
