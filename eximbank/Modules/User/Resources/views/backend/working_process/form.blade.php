@extends('layouts.backend')

@section('page_title', trans('laprofile.working_process'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.user'),
                'url' => route('module.backend.user')
            ],
            [
                'name' => $full_name,
                'url' => route('module.backend.user.edit',['id' => $user_id])
            ],
            [
                'name' => trans('laprofile.working_process'),
                'url' => route('module.backend.working_process',['user_id'=>$user_id])
            ],
            [
                'name' => trans('lamenu.addnew'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    @if($user_id)
        @include('user::backend.layout.menu')
    @endif
    <div role="main">
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="active">{{ trans('laprofile.info') }}</li>
            </ul>
            <div class="tab-content">
                <form method="post" action="{{ route('module.backend.working_process.save', ['user_id' => $user_id]) }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" value="{{ $user_id }}">
                    <input type="hidden" name="id" value="{{ $model->id }}">
                    <div class="form-group row m-2">
                        <div class="col-md-10">
                            <div class="form-group row">
                                <div class="col-md-2 control-label"> {{ trans('laprofile.title') }}</div>
                                <div class="col-md-8">
                                    <select name="title_id" class="form-control load-title" data-placeholder="{{ trans('laprofile.title') }}">
                                        <option value=""></option>
                                        @if(isset($title))
                                            <option value="{{ $title->id }}" selected>{{ $title->name }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2 control-label"> {{ trans('laprofile.unit') }} </div>
                                <div class="col-md-8">
                                    <select name="unit_id" class="form-control load-unit" data-placeholder="{{ trans('laprofile.unit') }}">
                                        <option value=""></option>
                                        @if(isset($unit))
                                            <option value="{{ $unit->id }}" selected>{{ $unit->name }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2 control-label">
                                    {{ trans('laprofile.time') }}
                                </div>
                                <div class="col-md-4">
                                    <input name="start_date" type="text" class="datepicker form-control" placeholder="{{ trans('laprofile.start_date') }}" autocomplete="off" value="{{ get_date($model->start_date) }}">
                                </div>
                                <div class="col-md-4">
                                    <input name="end_date" type="text" class="datepicker form-control" placeholder="{{ trans('laprofile.end_date') }}" autocomplete="off" value="{{ get_date($model->end_date) }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2 control-label"> {{ trans('laprofile.note') }} </div>
                                <div class="col-md-8">
                                    <textarea name="note" id="note" rows="5" class="form-control">{{ $model->note }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2 text-right">
                            <div class="btn-group act-btns">
                                <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> {{ trans('labutton.save') }}</button>
                                <a href="{{ route('module.backend.working_process', ['user_id' => $user_id]) }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
