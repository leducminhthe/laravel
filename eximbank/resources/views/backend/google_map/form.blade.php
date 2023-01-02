@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.setting'),
                'url' => ''
            ],
            [
                'name' => trans('latraining.training_location'),
                'url' => route('backend.google.map')
            ],
            [
                'name' => trans('laother.list_training_location'),
                'url' => ''
            ]
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <form method="post" action="{{ route('backend.google.map.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{ $model->id }}">
            <div class="row">
                <div class="col-md-8">
                </div>
                <div class="col-md-4 text-right">
                    <div class="btn-group act-btns">
                        @canany(['google-map-create', 'google-map-edit'])
                            <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                        @endcanany
                        <a href="{{ route('backend.contact') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
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
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="name">{{ trans('laother.contact_name') }}<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="title" class="form-control" value="{{ $model->title }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3"><label>{{ trans('lasetting.lng') }}</label></div>
                            <div class="col-md-6">
                                <input type="text" name="lng" class="form-control" value="{{ $model->lng }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3"><label>{{ trans('lasetting.lat') }}</label></div>
                            <div class="col-md-6">
                                <input type="text" name="lat" class="form-control" value="{{ $model->lat }}">
                            </div>
                        </div>
                        <div class="form-group row" id="select_posts">
                            <div class="col-md-3"><label>{{ trans("latraining.content") }}</label></div>
                            <div class="col-md-6">
                                <textarea name="description" rows="5" id="content" placeholder="{{ trans('backend.content') }}" class="form-control">{{ $model->description }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row" id="select_posts">
                            <div class="col-md-3"><label>{{ trans('latraining.note') }}</label></div>
                            <div class="col-md-6">
                                <textarea name="note" rows="5" placeholder="{{ trans('lasetting.note') }}" class="form-control">{{ $model->note }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop
