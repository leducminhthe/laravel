@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('lamenu.setting') }}
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('backend.login_image') }}">{{ trans('lasetting.login_wallpaper') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="tPanel">
            <div class="tab-content">
                <form method="post" action="{{ route('backend.login_image.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="{{ $model->id }}">
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4 text-right">
                            <div class="btn-group act-btns">
                                <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                                <a href="{{ route('backend.login_image') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label for="image">{{trans('backend.picture')}} <span class="text-danger">*</span> <br>({{trans('backend.size')}}: 2000x1000)</label>
                        </div>

                        <div class="col-sm-6">
                            <a href="javascript:void(0)" id="select-image-web">{{trans('latraining.choose_picture')}}</a>
                            <div id="image-review-web">
                                @if($model->image) <img src="{{ image_file($model->image) }}" class="w-25"> @endif
                            </div>
                            <input type="hidden" class="form-control" name="image" id="image-select-web" value="{{ $model->image }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label for="status">{{trans('latraining.status')}} <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-6">
                            <select name="status" id="status" class="form-control select2-default" data-placeholder="-- {{trans('latraining.status')}} --" required>
                                <option value="1" {{ $model->status == 1 ? 'selected' : '' }}> {{ trans("backend.enable") }}</option>
                                <option value="0" {{ (!is_null($model->status) && $model->status == 0) ? 'selected' : '' }}> {{ trans("backend.disable") }}</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
@stop
