@extends('layouts.backend')

@section('page_title', $model->name)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lasetting.mailtemplate'),
                'url' => route('backend.mailtemplate')
            ],
            [
                'name' => $model->name,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')

    <div role="main">

        <form method="post" action="{{ route('backend.mailtemplate.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{ $model->id }}">
            <div class="row">
                <div class="col-md-8">

                </div>
                <div class="col-md-4 text-right">
                    <div class="btn-group act-btns">
                        @can('mail-template-edit')
                        <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                        @endcan
                        <a href="{{ route('backend.mailtemplate') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                    </div>
                </div>
            </div>

            <div class="clear"></div>

            <br>
            <div class="tPanel">
                <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                    <li class="active"><a href="#base" role="tab" data-toggle="tab">{{ trans('lasetting.info') }}</a></li>
                </ul>
                <div class="tab-content">
                    <div id="base" class="tab-pane active">
                        <div class="form-group row">
                            <div class="col-sm-2 control-label">
                                <label for="name">{{ trans('lasetting.name') }} </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="name" value="{{ $model->name }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-2 control-label">
                                <label for="name">{{trans('lasetting.titles')}} </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="title" value="{{ $model->title }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-2 control-label">
                                <label for="name">{{ trans('lasetting.content') }} </label>
                            </div>
                            <div class="col-sm-9">
                                <textarea name="content" id="ckeditor" class="form-control" rows="10">{!! $model->content !!}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        /*CKEDITOR.replace( 'ckeditor', {
            disallowedContent: 'img a'
        } );*/
        CKEDITOR.replace('content', {
            filebrowserImageBrowseUrl: '/filemanager?type=image',
            filebrowserBrowseUrl: '/filemanager?type=file',
            filebrowserUploadUrl : null, //disable upload tab
            filebrowserImageUploadUrl : null, //disable upload tab
            filebrowserFlashUploadUrl : null, //disable upload tab
        });
    </script>
@stop
