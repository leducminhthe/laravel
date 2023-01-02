@extends('layouts.backend')

@section('page_title', trans('lahandle_situations.add_new'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.situations_proccessing'),
                'url' => route('module.topic_situations')
            ],
            [
                'name' => trans('lahandle_situations.add_new') . ' ' . trans('lahandle_situations.situations_discuss'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
<form method="POST" action="{{ route('module.save.situations',['id' => $topic_id]) }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-8">
        </div>
        <div class="col-md-4 text-right mb-3">
            <div class="btn-group act-btns">
                @can('situation-create')
                    <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                @endcan
                <a href="{{ route('module.topic_situations') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
            </div>
        </div>
    </div>
    <div class="row" id="armorial_id">
        <div class="col-md-12">
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="name_situations">{{ trans('lahandle_situations.name_situations') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="name_situations" type="text" class="form-control" value="" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="code_situations">{{ trans('lahandle_situations.code_situations') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="code_situations" type="text" class="form-control" value="" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="description_situations">{{ trans('lahandle_situations.description') }}<span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <textarea id="content" name="description_situations" class="form-control" placeholder="{{ trans('lahandle_situations.description') }}"></textarea>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    CKEDITOR.replace('content', {
        filebrowserImageBrowseUrl: '/filemanager?type=image',
        filebrowserBrowseUrl: '/filemanager?type=file',
        filebrowserUploadUrl : null, //disable upload tab
        filebrowserImageUploadUrl : null, //disable upload tab
        filebrowserFlashUploadUrl : null, //disable upload tab
    });
</script>
@endsection

