@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('latraining.training_evaluation'),
                'url' => ''
            ],
            [
                'name' => 'Mô hình Kirkpatrick',
                'url' => route('module.rating_organization')
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
    <form method="post" action="{{ route('module.rating_organization.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['rating-levels-create', 'rating-levels-edit'])
                    <button type="submit" class="btn" id="save-template" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                    @endcanany
                    <a href="{{ route('module.rating_organization') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
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
                                <div class="col-sm-2 control-label">
                                    <label>Tên kỳ đánh giá <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-7">
                                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-2 control-label">
                                    <label>Khóa học <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-7">
                                    @if($course_views->count() > 0)
                                        <select name="courses[]" id="" class="form-control select2" data-placeholder="Chọn khóa" multiple>
                                            <option value=""></option>
                                            @foreach($course_views as $course)
                                                <option value="{{ $course->id }}" {{ in_array($course->id, $rating_levels_courses) ? 'selected' : '' }} >
                                                    <p>{{ '('. $course->code .') '. $course->name }}</p>
                                                    <p>{{ get_date($course->start_date) . ($course->end_date ? ' - '. get_date($course->end_date) : '') }}</p>
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-2 control-label">
                                    <label>{{ trans('latraining.status') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <label class="radio-inline"><input required type="radio" name="status" value="1" @if($model->status == 1 || is_null($model->status)) checked @endif> {{ trans('latraining.enable') }}</label>
                                    <label class="radio-inline"><input required type="radio" name="status" value="0" @if($model->status == 0 && !is_null($model->status)) checked @endif> {{ trans('latraining.disable') }}</label>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@stop
