<form method="POST" action="{{ route('module.libraries.ebook.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{ $model->id }}">
    <input type="hidden" name="type" value="2">
    <div class="row">
        <div class="col-md-8">

        </div>
        <div class="col-md-4 text-right">
            <div class="btn-group act-btns">
                @canany(['libraries-ebook-create', 'libraries-ebook-edit'])
                    <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                @endcanany
                <a href="{{ route('module.libraries.ebook') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="name">{{trans('backend.ebook_name')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="category_id">{{trans('backend.ebook_category')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <select name="category_id" id="category_id" class="form-control select2" data-placeholder="--Chọn danh mục ebook--" required>
                        <option value=""></option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $model->category_id == $category->id ? 'selected': '' }} >{{ $category->name }}</option>
                        @endforeach
                    </select>
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
                    <label for="name_author">{{ trans('lalibrary.author_name') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="name_author" type="text" class="form-control" placeholder="--{{ trans('lalibrary.author_name') }}--" value="{{ $model->name_author }}" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans("backend.choose_file")}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-6">
                            <a href="javascript:void(0)" id="select-form-review">{{trans("backend.choose_file")}}</a>
                            <div id="form-review">
                                @if($model->attachment)
                                    {{ basename($model->attachment) }}
                                @endif
                            </div>
                            <input name="attachment" id="item-select" type="text" class="d-none" value="{{ $model->attachment }}">
                        </div>
                        <div class="col-6">
                            <a href="javascript:void(0)" id="select-file-activity">{{ trans('backend.choose_file') }} (*.zip)</a>
                            <br>
                            <em id="path-name">
                                @if (isset($lib_zip))
                                    {{ basename($lib_zip->origin_path) }}
                                @endif
                            </em>
                            <input type="hidden" name="path" id="path" value="{{ isset($lib_zip) ? $lib_zip->origin_path : '' }}">
                        </div>
                    </div>
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
                    <label for="description">{{trans('latraining.description')}} <span class="text-danger">*</span></label>
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
