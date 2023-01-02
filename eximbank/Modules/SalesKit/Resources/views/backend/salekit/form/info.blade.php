<form method="POST" action="{{ route('module.saleskit.save', ['cate_id' => $categories->id]) }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{ $model->id }}">

    <div class="row">
        <div class="col-md-8">

        </div>
        <div class="col-md-4 text-right">
            <div class="btn-group act-btns">
                @canany(['saleskit-create', 'saleskit-edit'])
                    <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                @endcanany
                <a href="{{ route('module.saleskit', ['cate_id' => $categories->id]) }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="name">{{trans('backend.name')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.picture')}} ({{trans('backend.size')}}: 350x500)</label>
                </div>
                <div class="col-md-4">
                    <a href="javascript:void(0)" id="select-image">{{trans('latraining.choose_picture')}}</a>
                    <div id="image-review" >
                        @if($model->image)
                            <img class="w-100" src="{{ image_file($model->image) }}" alt="">
                        @endif
                    </div>
                    <input name="image" id="image-select" type="text" class="d-none" value="{{ $model->image }}">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="name_author">{{ trans('lalibrary.author_name') }}</label>
                </div>
                <div class="col-md-6">
                    <input name="name_author" type="text" class="form-control" placeholder="--{{ trans('lalibrary.author_name') }}--" value="{{ $model->name_author }}">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans("backend.choose_file")}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <a href="javascript:void(0)" id="select-form-review">{{trans("backend.choose_file")}} (.pdf)</a>
                    <div id="form-review">
                        @if($model->attachment)
                            {{ basename($model->attachment) }}
                        @endif
                    </div>
                    <input name="attachment" id="item-select" type="text" class="d-none" value="{{ $model->attachment }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="status" class="hastip" data-toggle="tooltip" data-placement="right" title="{{trans('backend.choose_status')}}">{{trans('latraining.status')}}</label>
                </div>
                <div class="col-sm-6">
                    <div class="radio">
                        <label><input type="radio" id="status" name="status" value="1" {{ $model->status == 1 ? 'checked' :  '' }}>&nbsp;&nbsp;{{trans('latraining.enable')}}</label>
                        <label><input type="radio" id="status" name="status" value="0" {{ $model->status == 0 ? 'checked' :  '' }} >&nbsp;&nbsp;{{trans("backend.disable")}}</label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="description">{{trans('latraining.description')}}</label>
                </div>
                <div class="col-md-6">
                    <textarea name="description" id="description" placeholder="{{trans('latraining.description')}}" class="form-control" value="">{!! $model->description  !!}</textarea>
                </div>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    {{--  Hình ảnh  --}}
    $("#select-image").on('click', function () {
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
            window.SetUrl = cb;
        };
        lfm({type: 'image'}, function (url, path) {
            $("#image-review").html('<img src="'+ path +'">');
            $("#image-select").val(path);
        });
    });

    {{--  File pdf  --}}
    $("#select-form-review").on('click', function () {
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'files'}, function (url, path) {
            var path2 =  path.split("/");
            $("#form-review").html(path2[path2.length - 1]);
            $("#item-select").val(path);
        });
    });

    {{--  File zip  --}}
    $("#select-file-activity").on('click', function () {
        open_filemanager({type: 'scorm'}, function (url, path, name) {
            $("#path-name").html(name);
            $("#path").val(path);
        });
    });

    CKEDITOR.replace('description', {
        filebrowserImageBrowseUrl: '/filemanager?type=image',
        filebrowserBrowseUrl: '/filemanager?type=file',
        filebrowserUploadUrl : null, //disable upload tab
        filebrowserImageUploadUrl : null, //disable upload tab
        filebrowserFlashUploadUrl : null, //disable upload tab
    });
</script>
