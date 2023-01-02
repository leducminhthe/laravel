@extends('layouts.backend')

@section('page_title', trans('labutton.add_new'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.promotions'),
                'url' => route('module.promotion')
            ],
            [
                'name' => trans('labutton.add_new'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="nav-item"><a href="#base" class="nav-link active" role="tab" data-toggle="tab">{{ trans('latraining.info') }}</a></li>
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    <form method="post" action="{{ route('module.promotion.save') }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('backend.gift_code') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input name="code" type="text" class="form-control" value="" >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('backend.gift_name') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input name="name" type="text" class="form-control" value="" >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('backend.promotion_category') }}</label><span class="text-danger"> * </span>
                                    </div>
                                    <div class="col-md-9">
                                        <select name="promotion_group" id="promotion_group" class="form-control select2" data-placeholder="{{ trans('backend.promotion_category') }}">
                                            <option value=""></option>
                                            @if($promotion_groups)
                                                @foreach($promotion_groups as $promotion_group)
                                                    <option value="{{ $promotion_group->id }}" >{{ $promotion_group->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('backend.quantity') }}<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input name="amount" type="number" class="form-control" value=""  min="0">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('backend.points_change') }}<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input name="point" type="number" class="form-control" value=""  min="0">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('backend.duration') }}</label><span class="text-danger"> * </span>
                                    </div>
                                    <div class="col-md-9">
                                        <span>
                                            <input name="period" type="text" placeholder="{{ trans('latraining.end_date') }}" class="form-control d-inline-block w-25" autocomplete="off" value="" id="datetimepicker">
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('backend.contacts') }}</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input name="contact" type="text" class="form-control" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('backend.rules') }}</label>
                                    </div>
                                    <div class="col-md-9">
                                        <textarea name="rules" id="rules" class="form-control"></textarea>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-3">
                                <div class="row row-acts-btn">
                                    <div class="col-sm-12">
                                        <div class="btn-group act-btns">
                                            @can('promotion-create')
                                            <button type="submit" class="btn @if(\App\Models\Permission::isUnitManager()) hidden @endif" data-must-checked="false"><i class="fa fa-save"></i> {{ trans('labutton.save') }}</button>
                                            @endcan
                                            <a href="{{ route('module.promotion') }}" class="btn @if(\App\Models\Permission::isUnitManager()) hidden @endif"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h4>{{trans('backend.picture')}} (290 x 290)</h4>
                                    <div>
                                        <a href="javascript:void(0)" id="select-image">{{trans('latraining.choose_picture')}}</a>
                                        <div id="image-review"></div>
                                        <input name="images" id="image-select" type="text" class="d-none" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{trans('latraining.status')}}</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="radio-inline"><input type="radio" name="status" checked value="1">{{trans("backend.enable")}}</label>
                                        <label class="radio-inline"><input type="radio" name="status" value="0">{{trans("backend.disable")}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script type="text/javascript">
                            CKEDITOR.replace('rules', {
                                filebrowserImageBrowseUrl: '/filemanager?type=image',
                                filebrowserBrowseUrl: '/filemanager?type=file',
                                filebrowserUploadUrl : null, //disable upload tab
                                filebrowserImageUploadUrl : null, //disable upload tab
                                filebrowserFlashUploadUrl : null, //disable upload tab
                            });
                        </script>
                        <script>
                            $('#datetimepicker').datetimepicker({
                                locale:'vi',
                                format: 'DD/MM/YYYY',
                                minDate:new Date(),
                                disabledDates: [new Date()]
                            });
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
                        </script>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
