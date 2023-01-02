@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.category'),
                'url' => route('backend.category')
            ],
            [
                'name' => trans('backend.commit'),
                'url' => route('backend.category.commit_month')
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
    <form method="post" action="{{ route('backend.category.commit_month.save_group') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">

            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['commit-month-create', 'commit-month-edit'])
                        <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp; {{ trans('labutton.save') }}</button>
                    @endcan
                    <a href="{{ route('backend.category.commit_month') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
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
                                    <label>Nhóm <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <input name="group" required type="text" placeholder="Nhập tên nhóm" class="form-control" value="{{$model->group}}" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.title_rank') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6" id="title-rank">
                                    <select name="titles[]" class="load-title-rank" required multiple data-placeholder="{{trans('backend.choose_title')}}">
                                        @foreach ($titles as $title)
                                            <option value="{{$title->id}}" selected>{{$title->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
