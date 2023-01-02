@extends('layouts.backend')

@section('page_title', trans('lamenu.setting_alert'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.setting_alert'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="tPanel">
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    <form method="post" action="{{ route('module.quiz.save_setting_alert') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="{{ $model ? $model->id : '' }}">

                        <div class="row">
                            <div class="col-md-9 col-sm-12">
                                <div class="form-group row">
                                    <div class="col-md-9 form-inline">
                                        {{ trans('latraining.from_date') }}
                                        <span class="ml-2 mr-2"><input name="from_time" type="text" class="form-control is-number" min="1" value="{{ $model ? $model->from_time : '' }}" required></span>
                                        {{ trans('latraining.to_date') }}
                                        <span class="ml-2 mr-2"><input name="to_time" type="text" class="form-control is-number" min="2" value="{{ $model ? $model->to_time : '' }}" required></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group text-right">
                                    <div class="btn-group act-btns">
                                        @can('quiz-setting-alert-create')
                                            <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                                        @endcan
                                        <a href="{{ route('module.quiz.setting_alert') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
