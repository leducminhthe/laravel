<form method="post" action="{{ route('backend.logo.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{ $logo->id }}">
    <input type="hidden" name="type" value="1">
    <div class="row">
        <div class="col-md-8">

        </div>
        <div class="col-md-4 text-right">
            <div class="btn-group act-btns">
                <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                <a href="{{ route('backend.logo') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-3 control-label">
            <label for="image">{{trans('lasetting.picture')}} <span class="text-danger">*</span> <br>({{trans('lasetting.size')}}: 300x80)</label>
        </div>

        <div class="col-sm-6">
            <a href="javascript:void(0)" id="select-image-web">{{trans('lasetting.choose_picture')}}</a>
            <div id="image-review-web">@if($logo->image) <img src="{{ image_file($logo->image) }}" class="w-25"> @endif</div>
            <input type="hidden" class="form-control" name="image" id="image-select-web" value="{{ $logo->image }}">
        </div>
    </div>

    {{--<div class="form-group row">
        <div class="col-sm-3 control-label">
            <label for="object">{{ trans('lasetting.object') }} </label>
        </div>
        <div class="col-sm-6">
            <select name="object[]" id="object" class="form-control select2" data-placeholder="-- {{ trans('lasetting.object') }} --" multiple>
                <option value=""></option>
                @foreach($unit as $item)
                    <option value="{{ $item->id }}" {{ !empty($get_logo) && in_array($item->id, $get_logo) ? 'selected' : '' }}> {{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>--}}

    <div class="form-group row">
        <div class="col-sm-3 control-label">
            <label for="status">{{trans('lasetting.status')}} <span class="text-danger">*</span></label>
        </div>
        <div class="col-sm-6">
            <select name="status" id="status" class="form-control select2-default" data-placeholder="-- {{trans('lasetting.status')}} --" required>

                <option value="1" {{ $logo->status == 1 ? 'selected' : '' }}>{{trans("lasetting.enable")}}</option>
                <option value="0" {{ (!is_null($logo->status) && $logo->status == 0) ? 'selected' : '' }}>{{trans("lasetting.disable")}}</option>

            </select>
        </div>
    </div>

</form>
<script type="text/javascript">
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
