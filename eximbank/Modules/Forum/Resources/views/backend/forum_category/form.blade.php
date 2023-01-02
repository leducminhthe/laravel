@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.forum'),
                'url' => route('module.forum.category')
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
    <form method="post" action="{{ route('module.forum.category.save') }}" class="form-validate form-ajax" role="form"
          enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">

            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['forum-create', 'forum-edit'])
                    <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                    @endcanany
                        <a href="{{ route('module.forum.category') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
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
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="image">Icon <span class="text-danger">*</span> <br>({{ trans('backend.size') }}: 50x50)</label>
                                </div>

                                <div class="col-sm-5">
                                    <a href="javascript:void(0)" id="select-image-icon">{{ trans('latraining.choose_picture') }} (*.png, *.jpg)</a>
                                    <div id="image-review-icon">@if($model->icon) <img src="{{ image_file($model->icon) }}" class="w-25"> @endif</div>
                                    <input type="hidden" class="form-control" name="icon" id="image-select-icon" value="{{ $model->icon }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="name">{{ trans('backend.category_name') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <input name="name" type="text" class="form-control" value="{{ $model->name }}">
                                </div>
                            </div>
                            {{-- <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="unit_id">{{trans('lamenu.unit')}}</label>
                                </div>
                                <div class="col-md-6">
                                    <select name="unit_id[]" id="unit_id" class="form-control select2" data-placeholder="-- {{ trans('backend.choose_unit') }} --" multiple >
                                        <option value=""></option>
                                        @foreach($units as $item)
                                            <option value="{{ $item->id }}" {{ isset($unit) && in_array($item->id, $unit) ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="status" class="hastip" data-toggle="tooltip" data-placement="right" title="{{trans('backend.choose_status')}}">{{ trans('latraining.status') }}</label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="radio">
                                        <input type="radio" id="status" name="status" value="1" {{ $model->status == 1 ? 'checked' :  '' }}>&nbsp;&nbsp;{{ trans('latraining.enable') }}
                                        <input type="radio" id="status" name="status" value="0" {{ $model->status == 0 ? 'checked' :  '' }} >&nbsp;&nbsp;{{ trans('latraining.disable') }}
                                    </div>
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
    $("#select-image-icon").on('click', function () {
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'image'}, function (url, path) {
            $("#image-review-icon").html('<img src="' + path + '" class="w-25">');
            $("#image-select-icon").val(path);
        });
    });
</script>

@stop
