@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lasetting.banner_login_mobile'),
                'url' => route('backend.banner_login_mobile')
            ],
            [
                'name' => $page_title,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="tPanel">
            <div class="tab-content">
                <form method="post" action="{{ route('backend.banner_login_mobile.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="{{ $model->id }}">
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4 text-right">
                            <div class="btn-group act-btns">
                                <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                                <a href="{{ route('backend.banner_login_mobile') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label for="image">{{trans('lasetting.picture')}} <span class="text-danger">*</span> <br>({{trans('lasetting.size')}}: Mobile)</label>
                        </div>

                        <div class="col-sm-6">
                            <a href="javascript:void(0)" id="select-image-web">{{trans('lasetting.choose_picture')}}</a>
                            <div id="image-review-web">
                                @if($model->image) <img src="{{ image_file($model->image) }}" class="w-25"> @endif
                            </div>
                            <input type="hidden" class="form-control" name="image" id="image-select-web" value="{{ $model->image }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label for="status">{{trans('lasetting.status')}} <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-6">
                            <select name="status" id="status" class="form-control select2-default" data-placeholder="-- {{trans('lasetting.status')}} --" required>
                                <option value="1" {{ $model->status == 1 ? 'selected' : '' }}> {{ trans("lasetting.enable") }}</option>
                                <option value="0" {{ (!is_null($model->status) && $model->status == 0) ? 'selected' : '' }}> {{ trans("lasetting.disable") }}</option>
                            </select>
                        </div>
                    </div>

                    {{-- <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label for="url">Url <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="url" class="form-control" placeholder="Nhập đường dẫn">
                        </div>
                    </div> --}}

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