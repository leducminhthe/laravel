@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.guide'),
                'url' => route('backend.guide')
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
        <form method="post" action="{{ route('backend.guide.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{ $model->id }}">
            <div class="row">
                <div class="col-md-8">
                </div>
                <div class="col-md-4 text-right">
                    <div class="btn-group act-btns">
                        @canany(['guide-create', 'guide-edit'])
                        <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                        @endcanany
                        <a href="{{ route('backend.guide') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <br>
            <div class="tPanel">
                <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                    <li class="active"><a href="#base" role="tab" data-toggle="tab">{{ trans('laguide.info') }}</a></li>
                </ul>
                <div class="tab-content">
                    <input type="hidden" name="flag" id="flag" value="{{ $model->id ? 1 : 0 }}">
                    <input type="hidden" name="content_of_id" id="content_of_id" value="{{ $model->attach }}">
                    <div id="base" class="tab-pane active">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="name">{{ trans('laguide.guide_name') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="name" class="form-control" value="{{ $model->name }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="type">{{ trans('laguide.type') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-9">
                                <select name="type" id="type" class="form-control w-25">
                                    <option value="1" {{ $model->type == 1 ? 'selected' : ''}}>{{ trans('laguide.file_pdf') }}</option>
                                    <option value="2" {{ $model->type == 2 ? 'selected' : ''}}>{{ trans('laguide.video') }}</option>
                                    <option value="3" {{ $model->type == 3 ? 'selected' : ''}}>{{ trans('laguide.post') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" id="select_file">
                            <div class="col-md-3"><label>{{ trans('laguide.attachments') }}</label></div>
                            <div class="col-md-6">
                                <a href="javascript:void(0)" id="select-attach">{{trans("laguide.choose_file")}}</a>
                                <div id="attach-review">
                                    @if($model->attach)
                                        {{ basename($model->attach) }}
                                    @endif
                                </div>
                                <input name="attach" id="attach-select" type="text" class="d-none" value="{{ $model->attach }}">
                            </div>
                        </div>
                        <div class="form-group row" id='select_video'>
                            <div class="col-md-3"><label>{{ trans('laguide.video') }}</label></div>
                            <div class="col-md-6">
                                <input type="file" name="video" id="video" class="myfrm form-control" style="height:auto;">
                                @if ($model->type == 2)
                                    <div class="mt-2">
                                        <video width="100%" height="auto" controls>
                                            <source src="{{ image_file($model->attach) }}" type="video/mp4">
                                        </video>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row" id="select_posts">
                            <div class="col-md-3"><label>{{ trans('laguide.post') }}</label></div>
                            <div class="col-md-6">
                                <textarea name="content" id="content" placeholder="{{ trans('laguide.content') }}" class="form-control">{{ $model->attach }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
<script>
    $(document).ready(function() {
        $('#select_video').hide();
        $('#select_posts').hide();
        var type = `<?php if (isset($model->type)) {
                        echo $model->type  ;
                    } else {
                        echo '';
                    } ?>`;
        if (type !== '' && type == 3) {
            $('#select_video').hide();
            $('#select_posts').show();
            $('#select_file').hide();
        } else if (type !== '' && type == 2) {
            $('#select_video').show();
            $('#select_posts').hide();
            $('#select_file').hide();
        }
        if (type !== '') {
            $('#type option').attr('disabled',true);
            var op = document.getElementById("type").getElementsByTagName("option");
            for (var i = 0; i < op.length; i++) {
                var get_value_op = op[i].value;
                if (op[i].value == type) {
                    op[i].disabled = false;
                }
            }
        }
        $('#type').on('change',function() {
            var type = $('#type').val();
            if ( type == 1 ) {
                $('#select_file').show();
                $('#select_video').hide();
                $('#select_posts').hide();
            } else if ( type == 2 ) {
                $('#select_video').show();
                $('#select_file').hide();
                $('#select_posts').hide();
            } else {
                $('#select_posts').show();
                $('#select_file').hide();
                $('#select_video').hide();
            }
        })
        $('#select_video').on('change',function() {
            var get_value = $('#video').val();
            $('#flag').val('0');
        })
        $('#select_posts').on('change',function() {
            console.log('change');
            $('#flag').val('0');
        })
    })
</script>
<script type="text/javascript">
    $("#select-attach").on('click', function () {
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'file'}, function (url, path) {
            var path2 =  path.split("/");
            $("#attach-review").html(path2[path2.length - 1]);
            $("#attach-select").val(path);
        });
    });

    CKEDITOR.replace('content', {
        filebrowserImageBrowseUrl: '/filemanager?type=image',
        filebrowserBrowseUrl: '/filemanager?type=file',
        filebrowserUploadUrl : null, //disable upload tab
        filebrowserImageUploadUrl : null, //disable upload tab
        filebrowserFlashUploadUrl : null, //disable upload tab
    });
</script>
@stop
