@extends('layouts.backend')

@section('page_title', trans('lamenu.usermedal_setting'))
@section('header')
    <script type="text/javascript" src="{{asset('styles/module/usermedal-setting/js/setting.js')}}"></script>
    <link href="{{asset('styles/module/usermedal-setting/css/css.css')}}" rel="stylesheet">
@endsection
@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.usermedal_setting'),
                'url' => route('module.usermedal-setting.list'),
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
    <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-6 text-right act-btns">
            <div class="btn-group">
                @can(['usermedal-setting-edit', 'usermedal-setting-create'])
                    @if(!$ro)
                    <button id="btnsave" type="button" class="btn"><i class="fa fa-save"></i> {{ trans('labutton.save') }}</button>
                        @endif
                @endcan
                <a href="{{ route('module.promotion') }}" class="btn"><i class="fa fa-times"></i> {{ trans('labutton.cancel') }}</a>
            </div>
        </div>
    </div>
    <div class="mt-3"></div>
    @if($ro)
    <div class="alert alert-danger" role="alert">
        {{ trans('latraining.notify_edit_usermedal_setting') }}
    </div>
    @endif
    <div class="tPanel">
        <ul class="nav nav-pills mb-4" role="tablist">
            <li class="nav-item"><a class="nav-link @if($tabs == 'base' || empty($tabs)) active @endif" href="#base" role="tab" data-toggle="tab">{{ trans('latraining.info') }}</a></li>
            @if($model->id)
            <li class="nav-item"><a class="nav-link @if($tabs == 'object') active @endif" href="#pnobject" role="tab" data-toggle="tab">{{ trans('latraining.object') }}</a></li>
            @endif
        </ul>

        <div class="tab-content">
            <div id="base" class="tab-pane @if($tabs == 'base' || empty($tabs)) active @endif">
                <form id="frmMainMedal" action="{{ route('module.usermedal-setting.save') }}" method="post" class="form-ajax" enctype="multipart/form-data">
                    <div>&nbsp;</div>
                    <input type="hidden" name="id" value="{{ $model->id }}">

                    <div class="form-group row">
                        <div class="col-sm-2 control-label">
                            <label>{{ trans('lamenu.compete_title') }}</label>
                        </div>
                        <div class="col-md-9">
                            <select {{$ro?'disabled':''}} name="usermedal_id" id="usermedal_id" class="form-control"  required>
                                <option value="0">{{ trans('lamenu.compete_title') }}</option>
                                @php
                                    $img = '';
                                @endphp
                                @foreach($medal as $k =>$v)
                                    @if($v->id==$model->usermedal_id)
                                        @php
                                            $img = $v->photo;
                                        @endphp
                                    @endif
                                    <option value="{{ $v->id }}"{{$model->usermedal_id==$v->id?' selected':''}}> {{ $v->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if($img)
                    <div class="form-group row">
                        <div class="col-sm-2 control-label"><label>{{ trans('latraining.picture') }}</label></div>
                        <div class="col-md-9">
                            <img src="{{ image_file($img) }}" alt="" style="height: 100px; width: auto;">
                        </div>
                    </div>
                    @endif
                    <div class="form-group row">
                        <label class="col-sm-2 control-label"><label>{{ trans('lapromotion.choose_time') }}</label></label>
                        <div class="col-sm-9">
                            <div>
                                <input {{$ro?'disabled':''}} name="start_date" type="text" class="datepicker form-control w-25 d-inline-block date-custom" placeholder="{{trans('latraining.start')}}" autocomplete="off" value="{{ $model->start_date ? date('d/m/Y', $model->start_date) : '' }}">

                                <select {{$ro?'disabled':''}} name="start_hour" id="start_hour" class="form-control d-inline-block w-25 date-custom">
                                    @for($i = 0 ; $i <= 23 ; $i++)
                                        <option value="{{ sprintf('%02d', $i) }}" {{ ($model->start_date && date('H', $model->start_date) == $i) ? 'selected' : '' }}>
                                            {{ sprintf('%02d', $i) }}
                                        </option>
                                    @endfor
                                </select>
                                {{ trans('latraining.hour') }}
                                <select {{$ro?'disabled':''}} name="start_minute" id="start_minute" class="form-control d-inline-block  w-25 date-custom">
                                    @for($i = 0; $i <= 59; $i += 1)
                                        <option value="{{ sprintf('%02d', $i) }}" {{ ($model->start_date && date('i', $model->start_date) == $i) ? 'selected' : '' }}>
                                            {{ sprintf('%02d', $i) }}
                                        </option>
                                    @endfor
                                </select>
                                {{ trans('latraining.minute') }}
                            </div>
                            <div style="margin-top: 15px;">
                                <input {{$ro?'disabled':''}} name="end_date" type="text" class="datepicker form-control w-25 d-inline-block date-custom" placeholder="{{trans('latraining.over')}}" autocomplete="off" value="{{ $model->end_date ? date('d/m/Y', $model->end_date) : '' }}">
                                <select {{$ro?'disabled':''}} name="end_hour" id="end_hour" class="form-control d-inline-block w-25 date-custom">
                                    @for($i = 0 ; $i <= 23 ; $i++)
                                        <option value="{{ sprintf('%02d', $i) }}" {{ ($model->end_date && date('H', $model->end_date) == $i) ? 'selected' : '' }}>
                                            {{ sprintf('%02d', $i) }}
                                        </option>
                                    @endfor
                                </select>
                                {{ trans('latraining.hour') }}
                                <select {{$ro?'disabled':''}} name="end_minute" id="end_minute" class="form-control d-inline-block w-25 date-custom">
                                    @for($i = 0; $i <= 59; $i += 1)
                                        <option value="{{ sprintf('%02d', $i) }}" {{ ($model->end_date && date('i', $model->end_date) == $i) ? 'selected' : '' }}>
                                            {{ sprintf('%02d', $i) }}
                                        </option>
                                    @endfor
                                </select>
                                {{ trans('latraining.minute') }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2 control-label">
                            <label>{{ trans('latraining.status') }}</label>
                        </div>
                        <div class="col-md-9">
                            <label>
                                <input {{$ro?'disabled':''}} type="radio" id="status" name="status" value="1" {{ $model->status == 1 ? 'checked' :  '' }}>&nbsp;&nbsp;{{ trans('labutton.enable') }}
                                <input {{$ro?'disabled':''}} type="radio" id="status" name="status" value="0" {{ $model->status == 0 ? 'checked' :  '' }} >&nbsp;&nbsp;{{ trans('labutton.disable') }}
                            </label>
                        </div>
                    </div>

                    @if($model->id)
                        <hr/>
                        @include('usermedal::backend.usermedal-settings.child-usermedal')
                    @endif
                </form>
                @if($model->id)
                    @include('usermedal::backend.usermedal-settings.form-online')
                    @include('usermedal::backend.usermedal-settings.form-offline')
                    @include('usermedal::backend.usermedal-settings.form-quiz')
                @endif
            </div>
            <div id="pnobject" class="tab-pane @if($tabs == 'object') active @endif">
                @if($model->id)
                    @include('usermedal::backend.usermedal-settings.object')
                @endif
            </div>
        </div>
    </div>

    <script type="text/javascript" language="javascript">
        $("#btnsave").click(function(){
            $("#frmMainMedal").submit();
        });

        var start_date_main = '{{ date('Y-m-d H:i:s',$model->start_date) }}';
        var end_date_main = '{{ date('Y-m-d H:i:s',$model->end_date) }}';

        var url_load_course = '{{route('module.usermedal-setting.load-courses')}}';
        var url_remove_item = '{{route('module.usermedal-setting.remove')}}';
        var url_edit_item = '{{route('module.usermedal-setting.edit-item')}}';
    </script>
@endsection
