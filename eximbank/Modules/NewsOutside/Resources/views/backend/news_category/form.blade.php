@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => 'Tin tức chung',
                'url' => ''
            ],
            [
                'name' => trans("backend.category_post"),
                'url' => route('module.news_outside.category')
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
    <form method="post" action="{{ route('module.news_outside.category.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">

            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @can('news-outside-category-edit')
                        <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                    @endcan
                    <a href="{{ route('module.news_outside.category') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                </div>
            </div>
        </div>

        <div class="clear"></div>

        <br>
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="active"><a href="#base" role="tab" data-toggle="tab">{{ trans('latraining.info') }}</a></li>
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    <div class="row">
                        <div class="col-md-12">
                            {{-- <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="icon">Icon <span class="text-danger">*</span> (50x50)</label>
                                </div>
                                <div class="col-md-6">
                                    <a href="javascript:void(0)" id="select-icon">{{ trans('latraining.choose_picture') }}</a>
                                    <div id="icon-review" >
                                        @if($model->icon)
                                            <img src="{{ image_file($model->icon) }}" alt="" class="w-25">
                                        @endif
                                    </div>
                                    <input name="icon" id="icon-select" type="text" class="d-none" value="{{ $model->icon ? $model->icon : '' }}">
                                </div>
                            </div> --}}
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="name">{{ trans('backend.category_post_name') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="parent_id">{{ trans('backend.father_level') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <select name="parent_id" id="parent_id" class="form-control select2" data-placeholder="--{{trans('backend.choose_category_parent')}}--" >
                                        <option value=""></option>
                                        @foreach($categories as $parent)
                                            <option value="{{ $parent['id'] }}" {{ $model->parent_id == $parent->id ? 'selected' : '' }} > {{ $parent['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row"  id="stt_sort_category_parent">
                                <div class="col-sm-3 control-label">
                                    <label for="stt_sort_parent">Sắp xếp cấp cha</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="stt_sort_parent" id="stt_sort_parent" class="form-control" placeholder="Nhập số thứ tự sắp xếp cấp cha" value="{{ $model->stt_sort_parent ? $model->stt_sort_parent : '' }}">
                                </div>
                            </div>

                            <div class="form-group row"  id="status_category_parent">
                                <div class="col-sm-3 control-label">
                                    <label for="status">Hiện trên trang chủ</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="checkbox" {{ $model->status == 1 ? 'checked' : ''}} id="status" name="status" value="1">
                                </div>
                            </div>

                            <div class="form-group row" id="stt_sort_category">
                                <div class="col-sm-3 control-label">
                                    <label for="stt_sort">Số thứ tự</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="stt_sort" id="stt_sort" class="form-control" placeholder="Nhập số thứ tự sắp xếp" value="{{ $model->stt_sort ? $model->stt_sort : '' }}">
                                </div>
                            </div>
                            <div class="form-group row" id="sort_category">
                                <div class="col-sm-3 control-label">
                                    <label for="sort">Sắp xếp bên phải</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="checkbox" {{ $model->sort == 2 ? 'checked' : ''}} id="sort" name="sort" value="2">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        var check_parent_id = $('#parent_id').val();
        if (check_parent_id) {
            $('#sort_category').show();
            $('#stt_sort_category').show();
            $('#stt_sort_category_parent').hide();
            $('#stt_sort_parent').val('');
            $('#status_category_parent').hide();
        } else {
            $('#sort_category').hide();
            $('#stt_sort_category').hide();
            $('#stt_sort_category_parent').show();
            $('#status_category_parent').show();
        }

        $("#select-icon").on('click', function () {
            var lfm = function (options, cb) {
                var route_prefix = '/filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
                window.SetUrl = cb;
            };

            lfm({type: 'image'}, function (url, path) {
                $("#icon-review").html('<img src="' + path + '" class="w-25">');
                $("#icon-select").val(path);
            });
        });

        $('#parent_id').on('change',function() {
            var parent_id = $('#parent_id').val();
            if (parent_id) {
                $('#sort_category').show();
                $('#stt_sort_category').show();
                $('#stt_sort_category_parent').hide();
                $('#stt_sort_parent').val('');
                $('#status_category_parent').hide();
            } else {
                $('#sort_category').hide();
                $('#stt_sort_category').hide();
                $('#stt_sort_category_parent').show();
                $('#status_category_parent').show();
            }
        })
        
    </script>
</div>
@stop
