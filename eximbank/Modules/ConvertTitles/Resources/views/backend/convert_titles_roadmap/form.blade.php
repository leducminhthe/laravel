@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('module.trainingroadmap.list') }}">{{ trans('latraining.list_roadmap') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.convert_titles.roadmap.list_title') }}">{{trans('latraining.title_conversion_program')}}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.convert_titles.roadmap' ,['id' => $title_id]) }}">{{$page_title_name}}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
        </h2>
    </div>
@endsection

@section('content')

<div role="main">
    <form method="post" action="{{ route('module.convert_titles.roadmap.save',['id' => $title_id] ) }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">

            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['convert-titles-roadmap-create', 'convert-titles-roadmap-edit'])
                    <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                    @endcanany
                    <a href="{{ route('module.convert_titles.roadmap',['id' => $title_id] ) }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
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
                                    <label>{{ trans('latraining.training_program') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <select name="training_program_id" id="training_program_id" class="form-control load-training-program" data-placeholder="-- {{trans('latraining.training_program')}} --" required>
                                        @if(isset($training_program))
                                            <option value="{{ $training_program->id }}" selected> {{ $training_program->name }} </option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="subject_id">{{ trans('latraining.subject_name') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <select name="subject_id" id="subject_id" class="form-control load-subject" data-training-program="{{ $model->training_program_id }}" data-placeholder="-- {{ trans('lasuggest_plan.choose_subject') }} --" required>
                                        @if(isset($subject))
                                            <option value="{{ $subject->id }}" selected> {{ $subject->name }} </option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="name">{{trans('backend.form')}} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <select name="training_form" id="training_form" class="form-control select2"
                                            data-placeholder="-- {{trans('latraining.choose_form')}} --">
                                        <option value=""></option>
                                        <option value="1" {{ $model->training_form == 1 ? 'selected' : '' }}>{{trans('lasuggest_plan.online')}}</option>
                                        <option value="2" {{ $model->training_form == 2 ? 'selected' : '' }}>{{trans('latraining.offline')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="completion_time">{{trans('backend.required_time_complete_course')}} ({{trans('latraining.date')}})</label>
                                </div>
                                <div class="col-md-1">
                                    <input name="completion_time" type="text" class="form-control is-number" value="{{$model->completion_time}}">
                                </div>
                                <span style="color: #737373">({{trans('laother.calculated_from_date')}})</span>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="order">{{ trans('lasetting.order') }} </label>
                                </div>
                                <div class="col-md-1">
                                    <input name="order" type="text" class="form-control is-number"  value="{{$model->order}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="name">{{ trans('latraining.description') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <textarea name="content" type="text" class="form-control" rows="5" value="">{{ $model->content }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script src="{{ asset('styles/module/convert_titles/js/convert_titles_roadmap.js') }}"></script>
@stop
